<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerBucketEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\block\Block;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\level\Position;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Settings;

class EventListener implements Listener {

  private $plugin;

  private $level;

  public function __construct($plugin, $level) {

    $this->plugin = $plugin;
    $this->level = $level;
    $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
  }
  public function onPlace(BlockPlaceEvent $event) {

    $player = $event->getPlayer();
    $block = $event->getBlock();
    $level = $this->level;
    $plugin = $this->plugin;
    $valuableBlocks = $plugin->cfg->get("Valuable Blocks", []);
    $worldsArray = $plugin->cfg->get("SkyBlockWorlds", []);

    if (in_array($player->getLevel()->getFolderName(), $worldsArray)) {

      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
      $blockX = $block->getX();
      $blockY = $block->getY();
      $blockZ = $block->getZ();
      $islandOwner = "";

      foreach (array_keys($skyblockArray) as $skyblocks) {

        $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
        $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
        $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
        $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
        $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
        $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];

        if (($blockX > $startX && $blockY > $startY && $blockZ > $startZ && $blockX < $endX && $blockY < $endY && $blockZ < $endZ) && ($player->getLevel()->getFolderName() === $skyblockArray[$skyblocks]["World"])) {

          $islandOwner = $skyblocks;
          break;
        }
      }
      if ($islandOwner === "") {

        if ($player->hasPermission("skyblock.bypass")) {

          return;
        }

        $event->setCancelled(true);
        return;
      } else if (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {

        if (array_key_exists($block->getID(), $valuableBlocks)) {

          $skyblockArray[$islandOwner]["Value"] += $valuableBlocks[$block->getID()];
          $plugin->skyblock->set("SkyBlock", $skyblockArray);
          $plugin->skyblock->save();
        }
        return;
      } else {

        if ($player->hasPermission("skyblock.bypass") || $skyblockArray[$islandOwner]["Settings"]["Build"] === "off") {

          if (array_key_exists($block->getID(), $valuableBlocks)) {

            $skyblockArray[$islandOwner]["Value"] += $valuableBlocks[$block->getID()];
            $plugin->skyblock->set("SkyBlock", $skyblockArray);
            $plugin->skyblock->save();
          }
          return;
        }

        $event->setCancelled(true);
        return;
      }
    }
  }
  public function onBreak(BlockBreakEvent $event) {

    $player = $event->getPlayer();
    $block = $event->getBlock();
    $level = $this->level;
    $plugin = $this->plugin;
    $valuableBlocks = $plugin->cfg->get("Valuable Blocks", []);
    $worldsArray = $plugin->cfg->get("SkyBlockWorlds", []);

    if (in_array($player->getLevel()->getFolderName(), $worldsArray)) {

      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
      $blockX = $block->getX();
      $blockY = $block->getY();
      $blockZ = $block->getZ();
      $islandOwner = "";

      foreach (array_keys($skyblockArray) as $skyblocks) {

        $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
        $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
        $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
        $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
        $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
        $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];

        if (($blockX > $startX && $blockY > $startY && $blockZ > $startZ && $blockX < $endX && $blockY < $endY && $blockZ < $endZ) && ($player->getLevel()->getFolderName() === $skyblockArray[$skyblocks]["World"])) {

          $islandOwner = $skyblocks;
          break;
        }
      }
      if ($islandOwner === "") {

        if ($player->hasPermission("skyblock.bypass")) {

          return;
        }

        $event->setCancelled(true);
        $player->sendMessage(TextFormat::RED . "You cannot break blocks here!");
        return;
      } elseif (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {

        if (array_key_exists($block->getID(), $valuableBlocks)) {

          if (!($skyblockArray[$islandOwner]["Value"] <= 0)) {

            $skyblockArray[$islandOwner]["Value"] -= $valuableBlocks[$block->getID()];
            $plugin->skyblock->set("SkyBlock", $skyblockArray);
            $plugin->skyblock->save();
          }
        }
        return;
      } else {

        if ($player->hasPermission("skyblock.bypass") || $skyblockArray[$islandOwner]["Settings"]["Break"] === "off") {

          if (array_key_exists($block->getID(), $valuableBlocks)) {

            if (!($skyblockArray[$islandOwner]["Value"] <= 0)) {

              $skyblockArray[$islandOwner]["Value"] -= $valuableBlocks[$block->getID()];
              $plugin->skyblock->set("SkyBlock", $skyblockArray);
              $plugin->skyblock->save();
            }
          }
          return;
        }

        $event->setCancelled(true);
        $player->sendMessage(TextFormat::RED . "You cannot break blocks here!");
        return;
      }
    }
  }
  public function onInteract(PlayerInteractEvent $event) {

    $player = $event->getPlayer();
    $block = $event->getBlock();
    $item = $event->getItem();
    $level = $this->level;
    $plugin = $this->plugin;
    $worldsArray = $plugin->cfg->get("SkyBlockWorlds", []);

    if ($player->hasPermission("skyblock.bypass")) {

      return;
    }

    if ($block->getID() === 54 || $block->getID() === 61 || $block->getID() === 62 || $block->getID() === 138 || $block->getID() === 130 || $item->getID() === 259 || $block->getID() === 145 || $block->getID() === 58 || $block->getID() === 154 || $block->getID() === 117) {

      if (in_array($player->getLevel()->getFolderName(), $worldsArray)) {

        $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
        $playerX = $player->getX();
        $playerY = $player->getY();
        $playerZ = $player->getZ();
        $islandOwner = "";

        foreach (array_keys($skyblockArray) as $skyblocks) {

          $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
          $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
          $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
          $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
          $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
          $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];

          if (($playerX > $startX && $playerY > $startY && $playerZ > $startZ && $playerX < $endX && $playerY < $endY && $playerZ < $endZ) && ($player->getLevel()->getFolderName() === $skyblockArray[$skyblocks]["World"])) {

            $islandOwner = $skyblocks;
            break;
          }
        }
        if ($islandOwner === "") {

          $event->setCancelled(true);
          $player->sendMessage(TextFormat::RED . "You cannot use this here!");
          return;
        } elseif (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {

          return;
        } else {

          if ($block->getID() === 54 && $skyblockArray[$islandOwner]["Settings"]["Chest"] === "off") return;
          if (($block->getID() === 61 || $block->getID() === 62) && $skyblockArray[$islandOwner]["Settings"]["Furnace"] === "off") return;
          if ($block->getID() === 138 && $skyblockArray[$islandOwner]["Settings"]["Beacon"] === "off") return;
          if ($block->getID() === 130 && $skyblockArray[$islandOwner]["Settings"]["EnderChest"] === "on") return;
          if ($block->getID() === 259 && $skyblockArray[$islandOwner]["Settings"]["FlintAndSteel"] === "off") return;
          if ($block->getID() === 145 && $skyblockArray[$islandOwner]["Settings"]["Anvil"] === "off") return;
          if ($block->getID() === 58 && $skyblockArray[$islandOwner]["Settings"]["CraftingTable"] === "on") return;
          if ($block->getID() === 154 && $skyblockArray[$islandOwner]["Settings"]["Hopper"] === "off") return;
          if ($block->getID() === 117 && $skyblockArray[$islandOwner]["Settings"]["Brewing"] === "on") return;

          $event->setCancelled(true);
          $player->sendMessage(TextFormat::RED . "You cannot use this here!");
          return;
        }
      }
    }
  }
  public function onBucketEvent(PlayerBucketEvent $event) {

    $player = $event->getPlayer();
    $level = $this->level;
    $plugin = $this->plugin;
    $worldsArray = $plugin->cfg->get("SkyBlockWorlds", []);

    if (in_array($player->getLevel()->getFolderName(), $worldsArray)) {

      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
      $playerX = $player->getX();
      $playerY = $player->getY();
      $playerZ = $player->getZ();
      $islandOwner = "";

      foreach (array_keys($skyblockArray) as $skyblocks) {

        $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
        $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
        $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
        $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
        $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
        $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];

        if (($playerX > $startX && $playerY > $startY && $playerZ > $startZ && $playerX < $endX && $playerY < $endY && $playerZ < $endZ) && ($player->getLevel()->getFolderName() === $skyblockArray[$skyblocks]["World"])) {

          $islandOwner = $skyblocks;
          break;
        }
      }
      if ($islandOwner === "") {

        if ($player->hasPermission("skyblock.bypass")) {

          return;
        }

        $event->setCancelled(true);
        $player->sendMessage(TextFormat::RED . "You cannot use this here!");
        return;
      } else if (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {

        return;
      } else {

        if ($player->hasPermission("skyblock.bypass") || $skyblockArray[$islandOwner]["Settings"]["Buckets"] === "on") {

          return;
        }

        $event->setCancelled(true);
        $player->sendMessage(TextFormat::RED . "You cannot use this here!");
        return;
      }
    }
  }
  public function onMove(PlayerMoveEvent $event) {

    $plugin = $this->plugin;
    $hunger = $plugin->cfg->get("Hunger");
    $void = $plugin->cfg->get("Void");
    $level = $this->level;
    $player = $event->getPlayer();
    $name = strtolower($player->getName());
    $to = $event->getTo();
    $newX = $to->x;
    $newZ = $to->z;
    $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
    $worldsArray = $plugin->cfg->get("SkyBlockWorlds", []);

    if (!$void) {

      if (in_array($player->getLevel()->getFolderName(), $worldsArray)) {

        if ($player->getY() <= 0) {

          if ($player->hasPermission("skyblock.safevoid")) {

            if ($plugin->cfg->get("Island Spawn")) {

              if (array_key_exists($name, $skyblockArray)) {

                $player->teleport(new Position((int) $skyblockArray[$name]["Spawn"]["X"], (int) $skyblockArray[$name]["Spawn"]["Y"], (int) $skyblockArray[$name]["Spawn"]["Z"], $plugin->getServer()->getLevelByName($skyblockArray[$name]["World"])));
              } else {

                $player->teleport($player->getSpawn());
              }
            } else {

              $player->teleport($player->getSpawn());
            }
          }
        }
      }
    }
    if (!$hunger) {

      if (in_array($player->getLevel()->getFolderName(), $worldsArray)) {

        if ($player->getFood() < 20) {

          if ($player->hasPermission("skyblock.nohunger")) {

            $player->setFood(20);
          }
        }
      }
    }
    if (in_array($player->getLevel()->getFolderName(), $worldsArray)) {

      $island = $plugin->getIslandAt($player);
      if ($island === false) return;

      if ($newX <= $skyblockArray[$island]["Area"]["start"]["X"] || $newZ <= $skyblockArray[$island]["Area"]["start"]["Z"] || $newX >= $skyblockArray[$island]["Area"]["end"]["X"] || $newZ >= $skyblockArray[$island]["Area"]["end"]["Z"]) {

        if (!$player->hasPermission("skyblock.bypass")) {

          $event->setCancelled(true);
        }
      }
    }
  }
  public function onDeath(PlayerDeathEvent $event) {

    $plugin = $this->plugin;
    $keepInventory = $plugin->cfg->get("KeepInventory");

    if ($keepInventory) {

      $event->setKeepInventory(true);
    }
  }
  public function onLevelChange(EntityLevelChangeEvent $event) {

    $entity = $event->getEntity();
    $target = $event->getTarget();
    $plugin = $this->plugin;
    $worldsArray = $plugin->cfg->get("SkyBlockWorlds", []);

    if ($entity instanceof Player) {

      if (!in_array($target->getFolderName(), $worldsArray)) {

        if ($entity->getAllowFlight()) {

          if ($entity->getGamemode() !== 1) {

            if ($entity->isFlying()) {

              $entity->setFlying(false);
            }
            $entity->setAllowFlight(false);
          }
        }
      }
    }
  }
  public function onDamage(EntityDamageByEntityEvent $event) {

    $entity = $event->getEntity();
    $entityX = $entity->getX();
    $entityY = $entity->getY();
    $entityZ = $entity->getZ();
    $damager = $event->getDamager();
    $plugin = $this->plugin;
    $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
    $islandOwner = "";
    $worldsArray = $plugin->cfg->get("SkyBlockWorlds", []);

    if ($entity instanceof Player && $damager instanceof Player) {

      if (!$plugin->cfg->get("PVP")) {

        if (in_array($entity->getLevel()->getFolderName(), $worldsArray)) {

          $event->setCancelled();
        }
      } else {

        foreach (array_keys($skyblockArray) as $skyblocks) {

          $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
          $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
          $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
          $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
          $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
          $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];

          if (($entityX > $startX && $entityY > $startY && $entityZ > $startZ && $entityX < $endX && $entityY < $endY && $entityZ < $endZ) && ($entity->getLevel()->getFolderName() === $skyblockArray[$skyblocks]["World"])) {

            $islandOwner = $skyblocks;
            break;
          }
        }
        if ($islandOwner === "") {

          return;
        } else {

          if ($skyblockArray[$islandOwner]["Settings"]["PVP"] === "off") {

            $event->setCancelled(true);
          }
        }
      }
    }
  }
  public function onPickup(InventoryPickupItemEvent $event) {

    $viewers = $event->getViewers();
    $entity;
    foreach($viewers as $key => $viewer) {

      $entity = $viewer;
    }
    $entityX = $entity->getX();
    $entityY = $entity->getY();
    $entityZ = $entity->getZ();
    $plugin = $this->plugin;
    $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
    $islandOwner = "";
    $worldsArray = $plugin->cfg->get("SkyBlockWorlds", []);

    if ($entity instanceof Player) {

      if (in_array($entity->getLevel()->getFolderName(), $worldsArray)) {

        foreach (array_keys($skyblockArray) as $skyblocks) {

          $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
          $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
          $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
          $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
          $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
          $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];

          if (($entityX > $startX && $entityY > $startY && $entityZ > $startZ && $entityX < $endX && $entityY < $endY && $entityZ < $endZ) && ($entity->getLevel()->getFolderName() === $skyblockArray[$skyblocks]["World"])) {

            $islandOwner = $skyblocks;
            break;
          }
        }
        if ($islandOwner === "") {

          return;
        } else if (in_array($entity->getName(), $skyblockArray[$islandOwner]["Members"])) {

          return;
        } else {

          if ($skyblockArray[$islandOwner]["Settings"]["Pickup"] === "on") {

            $event->setCancelled(true);
          }
        }
      }
    }
  }
  public function onEDamage(EntityDamageEvent $event) {

    $entity = $event->getEntity();
    $plugin = $this->plugin;

    if ($entity instanceof Player) {

      $island = $plugin->getIslandAt($entity);
      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);

      if ($island === false) {

        return;
      } else {

        if (in_array($entity->getName(), $skyblockArray[$island]["Members"])) {

          return;
        } else {

          if ($plugin->cfg->get("Invincible Visitors")) {

            if ($entity->hasPermission("skyblock.invincibleV")) {

              $event->setCancelled();
            }
          }
        }
      }
    }
  }
}

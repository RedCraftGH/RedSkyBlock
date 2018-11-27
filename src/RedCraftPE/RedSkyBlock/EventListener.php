<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerBucketEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\block\Block;
use pocketmine\block\Water;
use pocketmine\utils\TextFormat;

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

    if ($player->hasPermission("skyblock.bypass")) {

      return;
    }

    if ($player->getLevel() === $level) {

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

        if ($blockX > $startX && $blockY > $startY && $blockZ > $startZ && $blockX < $endX && $blockY < $endY && $blockZ < $endZ) {

          $islandOwner = $skyblocks;
          break;
        }
      }
      if ($islandOwner === "") {

        $event->setCancelled(true);
        $player->sendMessage(TextFormat::RED . "You cannot build here!");
        return;
      } else if (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {

        return;
      } else {

        $event->setCancelled(true);
        $player->sendMessage(TextFormat::RED . "You cannot build here!");
        return;
      }
    }
  }
  public function onBreak(BlockBreakEvent $event) {

    $player = $event->getPlayer();
    $block = $event->getBlock();
    $level = $this->level;

    if ($player->hasPermission("skyblock.bypass")) {

      return;
    }
    if ($player->getLevel() === $level) {

      $plugin = $this->plugin;
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

        if ($blockX > $startX && $blockY > $startY && $blockZ > $startZ && $blockX < $endX && $blockY < $endY && $blockZ < $endZ) {

          $islandOwner = $skyblocks;
          break;
        }
      }
      if ($islandOwner === "") {

        $event->setCancelled(true);
        $player->sendMessage(TextFormat::RED . "You cannot break blocks here!");
        return;
      } elseif (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {

        return;
      } else {

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

    if ($player->hasPermission("skyblock.bypass")) {

      return;
    }

    if ($block->getID() === 54 || $block->getID() === 61 || $block->getID() === 62 || $block->getID() === 138 || $block->getID() === 130 || $item->getID() === 259) {

      if ($player->getLevel() === $level) {

        $plugin = $this->plugin;
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

          if ($playerX > $startX && $playerY > $startY && $playerZ > $startZ && $playerX < $endX && $playerY < $endY && $playerZ < $endZ) {

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

    if ($player->hasPermission("skyblock.bypass")) {

      return;
    }

    if ($player->getLevel() === $level) {

      $plugin = $this->plugin;
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

        if ($playerX > $startX && $playerY > $startY && $playerZ > $startZ && $playerX < $endX && $playerY < $endY && $playerZ < $endZ) {

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

        $event->setCancelled(true);
        $player->sendMessage(TextFormat::RED . "You cannot use this here!");
        return;
      }
    }
  }
  public function onUpdate(BlockUpdateEvent $event) {

    $plugin = $this->plugin;

    if ($plugin->cfg->get("CobbleGen")) {

      $block = $event->getBlock();
      $isTouchingLava = false;

      if ($block instanceof Water) {

        for ($side = 2;$side <= 5;$side++) {

          if ($block->getSide($side)->getId() === 10) {

            $isTouchingLava = true;
            break;
          }
        }
      }
      if ($isTouchingLava) {

        $oresArray = SkyBlock::getInstance()->cfg->get("MagicCobbleGen Ores", []);
        $blockID = intval($oresArray[array_rand($oresArray)]);

        $block->getLevel()->setBlock($block, Block::get($blockID));
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

    if ($void === "off") {

      if ($player->getLevel() === $level) {

        if ($player->getY() < 0) {

          $player->teleport($player->getSpawn());
        }
      }
    }
    if ($hunger === "off") {

      if ($player->getLevel() === $level) {

        if ($player->getFood() < 20) {

          $player->setFood(20);
        }
      }
    }
  }
  public function onDeath(PlayerDeathEvent $event) {

    $plugin = $this->plugin;
    $keepInventory = $plugin->cfg->get("KeepInventory");

    if ($keepInventory === "on") {

      $event->setKeepInventory(true);
    }
  }
}

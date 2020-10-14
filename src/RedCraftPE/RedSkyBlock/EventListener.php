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
use pocketmine\event\player\PlayerExhaustEvent;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Settings;

class EventListener implements Listener {

  private $plugin;

  public function __construct($plugin) {

    $this->plugin = $plugin;
    $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
  }
  public function onPlace(BlockPlaceEvent $event) {

    $player = $event->getPlayer();
    $block = $event->getBlock();
    $plugin = $this->plugin;

    if ($player->getLevel()->getFolderName() === $plugin->skyblock->get("Master World")) {

      $owner = $plugin->getIslandAtBlock($block);
      $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
      $playerData;

      if ($owner === null) {

        $event->setCancelled();
        return;
      }

      if (file_exists($filePath)) {

        $playerDataEncoded = file_get_contents($filePath);
        $playerData = (array) json_decode($playerDataEncoded, true);
      }
      if ($owner === strtolower($player->getName()) || in_array(strtolower($player->getName()), $playerData["Island Members"])) {

        return;
      } else {

        $event->setCancelled();
        return;
      }
    }
  }
  public function onBreak(BlockBreakEvent $event) {

    $player = $event->getPlayer();
    $block = $event->getBlock();
    $plugin = $this->plugin;

    if ($player->getLevel()->getFolderName() === $plugin->skyblock->get("Master World")) {

      $owner = $plugin->getIslandAtBlock($block);
      $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
      $playerData;

      if ($owner === null) {

        $event->setCancelled();
        return;
      }

      if (file_exists($filePath)) {

        $playerDataEncoded = file_get_contents($filePath);
        $playerData = (array) json_decode($playerDataEncoded, true);
      }
      if ($owner === strtolower($player->getName()) || in_array(strtolower($player->getName()), $playerData["Island Members"])) {

        return;
      } else {

        $event->setCancelled();
        return;
      }
    }
  }
  public function onInteract(PlayerInteractEvent $event) {

    $player = $event->getPlayer();
    $block = $event->getBlock();
    $item = $event->getItem();
    $plugin = $this->plugin;

    if ($block->getID() === 52 ||$block->getID() === 54 || $block->getID() === 61 || $block->getID() === 62 || $block->getID() === 138 || $block->getID() === 130 || $item->getID() === 259 || $block->getID() === 145 || $block->getID() === 58 || $block->getID() === 154 || $block->getID() === 117) {

      if ($player->getLevel()->getFolderName() === $plugin->skyblock->get("Master World")) {

        $owner = $plugin->getIslandAtBlock($block);
        $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
        $playerData;

        if ($owner === null) {

          $event->setCancelled();
          $player->sendMessage(TextFormat::RED . "You cannot use this here!");
          return;
        }

        if (file_exists($filePath)) {

          $playerDataEncoded = file_get_contents($filePath);
          $playerData = (array) json_decode($playerDataEncoded, true);
        }

        if ($owner === strtolower($player->getName()) || in_array(strtolower($player->getName()), $playerData["Island Members"])) {

          return;
        } else {

          $event->setCancelled();
          $player->sendMessage(TextFormat::RED . "You cannot use this here!");
          return;
        }
      }
    }
  }
  public function onBucketEvent(PlayerBucketEvent $event) {

    $player = $event->getPlayer();
    $plugin = $this->plugin;

    if ($player->getLevel()->getFolderName() === $plugin->skyblock->get("Master World")) {

      $owner = $plugin->getIslandAtPlayer($player);
      $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
      $playerData;

      if ($owner === null) {

        $event->setCancelled();
        $player->sendMessage(TextFormat::RED . "You cannot use this here!");
        return;
      }

      if (file_exists($filePath)) {

        $playerDataEncoded = file_get_contents($filePath);
        $playerData = (array) json_decode($playerDataEncoded, true);
      }

      if ($owner === strtolower($player->getName()) || in_array(strtolower($player->getName()), $playerData["Island Members"])) {

        return;
      } else {

        $event->setCancelled();
        $player->sendMessage(TextFormat::RED . "You cannot use this here!");
        return;
      }
    }
  }
  public function onDamageByEntity(EntityDamageByEntityEvent $event) {

    $entity = $event->getEntity();
    $damager = $event->getDamager();
    $plugin = $this->plugin;
    $masterWorld = $plugin->getServer()->getLevelByName($plugin->skyblock->get("Master World"));

    if (($entity instanceof Player && $damager instanceof Player) && $entity->getLevel()->getFolderName() === $masterWorld) {

      if ($plugin->cfg->get("Island PVP") === "off") {

        $event->setCancelled();
        return;
      }
    }
  }
  public function onPickup(InventoryPickupItemEvent $event) {

    $viewers = $event->getViewers();
    $entity;
    foreach($viewers as $key => $viewer) {

      $entity = $viewer;
    }
    $plugin = $this->plugin;

    if ($entity instanceof Player) {

      if ($entity->getLevel()->getFolderName() === $plugin->skyblock->get("Master World")) {

        $owner = $plugin->getIslandAtPlayer($entity);
        $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
        $playerData;

        if ($owner === null) {

          $event->setCancelled();
          return;
        }

        if (file_exists($filePath)) {

          $playerDataEncoded = file_get_contents($filePath);
          $playerData = (array) json_decode($playerDataEncoded, true);
        }

        if ($owner === strtolower($entity->getName()) || in_array(strtolower($entity->getName()), $playerData["Island Members"])) {

          return;
        } else {

          $event->setCancelled();
          return;
        }
      }
    }
  }
  public function onJoin(PlayerJoinEvent $event) {

    $plugin = $this->plugin;
    $player = $event->getPlayer();
    $spawn = $plugin->getServer()->getDefaultLevel()->getSafeSpawn();

    $player->teleport($spawn);
  }
  public function onExhaust(PlayerExhaustEvent $event) {

    $player = $event->getPlayer();
    $plugin = $this->plugin;

    if ($player->getLevel()->getFolderName() === $plugin->getServer()->getDefaultLevel()->getFolderName() && $plugin->cfg->get("Spawn Hunger" === "off")) {

      $event->setCancelled();
      return;
    } elseif ($player->getLevel()->getFolderName() === $plugin->skyblock->get("Master World") && $plugin->cfg->get("Island Hunger" === "off")) {

      $event->setCancelled();
      return;
    }
  }
  public function onDamage(EntityDamageEvent $event) {

    $entity = $event->getEntity();
    $plugin = $this->plugin;
    $spawn = $plugin->getServer()->getDefaultLevel()->getSafeSpawn();
    $masterWorld = $plugin->getServer()->getLevelByName($plugin->skyblock->get("Master World"));

    if (($entity instanceof Player && $entity->getLevel()->getFolderName() === $plugin->getServer()->getDefaultLevel()->getFolderName()) && $plugin->cfg->get("Spawn Damage") === "off") {

      $event->setCancelled();
      return;
    } elseif ($entity instanceof Player && $entity->getLevel()->getFolderName() === $masterWorld) {

      if ($event->getBaseDamage() >= $entity->getHealth()) {

        $event->setCancelled();
        $entity->setHealth($entity->getMaxHealth());
        $entity->setFood($entity->getMaxFood());
        $entity->teleport($spawn);
        $plugin->getServer()->broadcastMessage(TextFormat::WHITE . $entity->getName() . " has died.");
        return;
      }
    }
  }
}

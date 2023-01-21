<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;
use pocketmine\world\Position;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\player\Player;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\StringToItemParser;

//import events:
//block events:
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
//player events:
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerBucketEvent;
//entity events:
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityItemPickupEvent;

use RedCraftPE\RedSkyBlock\Utils\ZoneManager;
use RedCraftPE\RedSkyBlock\Island;
use RedCraftPE\RedSkyBlock\Utils\HeadManager;

class SkyblockListener implements Listener {

  private $plugin;

  public function __construct(SkyBlock $plugin) {

    $this->plugin = $plugin;
    $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
  }

  public function onForm(BlockFormEvent $event) {

    $plugin = $this->plugin;
    $block = $event->getBlock();
    $world = $block->getPosition()->getWorld()->getFolderName();

    $generatorOres = $plugin->cfg->get("Generator Ores", []);
    $masterWorld = $plugin->skyblock->get("Master World");

    if ($world === $masterWorld || $world === $masterWorld . "-Nether") {

      if (count($generatorOres) !== 0) {

        if (array_sum($generatorOres) !== 100) {

          $message = $plugin->mShop->construct("GEN_FORMAT");
          $plugin->getLogger()->info($message);
        } else {

          $genBlock = null;
          $randomNumber = random_int(1, 100);
          $percentChance = 0;

          foreach ($generatorOres as $blockName => $oreChance) {

            $percentChance += $oreChance;

            if ($randomNumber <= $percentChance) {

              $genBlock = StringToItemParser::getInstance()->parse($blockName)->getBlock();
              break;
            }
          }
          if ($genBlock instanceof Block) {

            $event->cancel();
            $block->getPosition()->getWorld()->setBlock($block->getPosition(), $genBlock);
          }
        }
      }
    }
  }

  public function onJoin(PlayerJoinEvent $event) {

    $player = $event->getPlayer();
    ZoneManager::clearZoneTools($player);
  }

  public function onQuit(PlayerQuitEvent $event) {

    $player = $event->getPlayer();
    $zoneShovel = ZoneManager::getZoneShovel();
    $spawnFeather = ZoneManager::getSpawnFeather();

    if (ZoneManager::getZoneKeeper() === $player) {

      ZoneManager::setZoneKeeper();
      ZoneManager::clearZoneTools($player);
    }
  }

  public function onDrop(PlayerDropItemEvent $event) {

    $item = $event->getItem();
    $player = $event->getPlayer();
    $zoneShovel = ZoneManager::getZoneShovel();
    $spawnFeather = ZoneManager::getSpawnFeather();

    if ($item->equals($zoneShovel) || $item->equals($spawnFeather)) {

      $event->cancel();
      $index = array_search($item, $player->getInventory()->getContents());
      $player->getInventory()->setItem($index, VanillaItems::AIR());
    }
  }

  public function onInteract(PlayerInteractEvent $event) {

    $plugin = $this->plugin;
    $player = $event->getPlayer();
    $block = $event->getBlock();
    $item = $event->getItem();
    $action = $event->getAction();
    $zoneShovel = ZoneManager::getZoneShovel();

    $blockPos = $block->getPosition();
    $blockX = round($blockPos->x);
    $blockY = round($blockPos->y);
    $blockZ = round($blockPos->z);

    $zoneWorld = ZoneManager::getZoneWorld();
    $blockWorld = $blockPos->world;

    // check if using a zonetool and take appropriate actions if true:

    if ($item->equals($zoneShovel)) {

      if ($action === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {

        ZoneManager::setFirstPosition($blockPos);

        if ($zoneWorld === null || $zoneWorld != $blockWorld) {

          ZoneManager::setZoneWorld($blockWorld);
          ZoneManager::setSecondPosition(); //reset the other position because it was selected in a different world
          $zoneWorld = $blockWorld;
        }

        $message = $plugin->mShop->construct("SET_POS1");
        $message = str_replace("{X}", $blockX, $message);
        $message = str_replace("{Y}", $blockY, $message);
        $message = str_replace("{Z}", $blockZ, $message);
        $message = str_replace("{ZWORLD}", $zoneWorld->getFolderName(), $message);
        $player->sendMessage($message);
        return;
      }
    }
    //check if interacting with a block on an island and if yes cancel if not a part of that island:

    $island = $plugin->islandManager->getIslandAtBlock($block);
    if ($island instanceof Island) {

      $members = $island->getMembers();
      if (array_key_exists(strtolower($player->getName()), $members)) {

        $islandPermissions = $island->getPermissions();
        $playerRank = $members[strtolower($player->getName())];
        if (!in_array("island.interact", $islandPermissions[$playerRank])) {

          $event->cancel();
        }
      } elseif (!($player->getName() === $island->getCreator() || $player->hasPermission("redskyblock.bypass"))) {

        $event->cancel();
      }
    }
  }

  public function onBreak(BlockBreakEvent $event) {

    $plugin = $this->plugin;
    $player = $event->getPlayer();
    $block = $event->getBlock();
    $item = $event->getItem();
    $zoneShovel = ZoneManager::getZoneShovel();
    $spawnFeather = ZoneManager::getSpawnFeather();

    $blockPos = $block->getPosition();
    $blockX = round($blockPos->x);
    $blockY = round($blockPos->y);
    $blockZ = round($blockPos->z);

    $zoneWorld = ZoneManager::getZoneWorld();
    $blockWorld = $blockPos->world;

    // check if using a zone tool and take the appropriate actions if true:

    if ($item->equals($zoneShovel)) {

      ZoneManager::setSecondPosition($blockPos);
      $event->cancel();

      if ($zoneWorld === null || $zoneWorld != $blockWorld) {

        ZoneManager::setZoneWorld($blockWorld);
        ZoneManager::setFirstPosition(); //reset the other position because it was selected in a different world
        $zoneWorld = $blockWorld;
      }

      $message = $plugin->mShop->construct("SET_POS2");
      $message = str_replace("{X}", $blockX, $message);
      $message = str_replace("{Y}", $blockY, $message);
      $message = str_replace("{Z}", $blockZ, $message);
      $message = str_replace("{ZWORLD}", $zoneWorld->getFolderName(), $message);
      $player->sendMessage($message);
      return;

    } elseif ($item->equals($spawnFeather)) {

      $event->cancel();
      $zonePos1 = ZoneManager::getFirstPosition();
      $zonePos2 = ZoneManager::getSecondPosition();

      if ($zonePos1 !== null && $zonePos2 !== null) {

        if ($blockWorld === $zoneWorld) {

          ZoneManager::setSpawnPosition($blockPos);
          ZoneManager::createZone();
          ZoneManager::setZoneKeeper();
          ZoneManager::setFirstPosition();
          ZoneManager::setSecondPosition();
          ZoneManager::clearZoneTools($player);

          $message = $plugin->mShop->construct("SET_CUSTOM_SPAWN");
          $player->sendMessage($message);
          return;
        } else {

          $message = $plugin->mShop->construct("WRONG_WORLD");
          $player->sendMessage($message);
          return;
        }
      } else {

        $message = $plugin->mShop->construct("SPAWN_FEATHER_NOT_READY");
        $player->sendMessage($message);
        return;
      }
    }

    // Check if allowed to break blocks or if island value should decrease if on an island:

    $island = $plugin->islandManager->getIslandAtBlock($block);
    if ($island instanceof Island) {

      $members = $island->getMembers();
      $creator = $island->getCreator();
      $playerName = $player->getName();
      $playerNameLower = strtolower($playerName);

      if (array_key_exists($playerNameLower, $members) || $playerName === $creator || $player->hasPermission("redskyblock.bypass")) {

        if (array_key_exists($playerNameLower, $members)) {

          $islandPermissions = $island->getPermissions();
          $playerRank = $members[$playerNameLower];
          if (!in_array("island.break", $islandPermissions[$playerRank])) {

            $event->cancel();
            return;
          }
        }
        $valuableArray = $plugin->cfg->get("Valuable Blocks", []);
        $blockName = str_replace(" ", "_", strtolower($block->getName()));
        if (array_key_exists($blockName, $valuableArray)) {

          $island->removeValue((int) $valuableArray[$blockName]);
        }

        $island->addToStat("blocks_broken", 1);
      } else {

        $event->cancel();
      }

    } elseif (!$player->hasPermission("redskyblock.bypass")) {

      $event->cancel();
    }
  }

  public function onPlace(BlockPlaceEvent $event) {

    $plugin = $this->plugin;
    $masterWorld = $plugin->islandManager->getMasterWorld();
    $block = $event->getBlock();
    $blockWorld = $block->getPosition()->world;
    $player = $event->getPlayer();

    $island = $plugin->islandManager->getIslandAtBlock($block);
    if ($island instanceof Island) {

      $members = $island->getMembers();
      $creator = $island->getCreator();
      $playerName = $player->getName();
      $playerNameLower = strtolower($playerName);

      if (array_key_exists($playerNameLower, $members) || $playerName === $creator || $player->hasPermission("redskyblock.bypass")) {

        if (array_key_exists($playerNameLower, $members)) {

          $islandPermissions = $island->getPermissions();
          $playerRank = $members[$playerNameLower];
          if (!in_array("island.place", $islandPermissions[$playerRank])) {

            $event->cancel();
            return;
          }
        }

        $valuableArray = $plugin->cfg->get("Valuable Blocks", []);
        $blockName = str_replace(" ", "_", strtolower($block->getName()));
        if (array_key_exists($blockName, $valuableArray)) {

          $island->addValue((int) $valuableArray[$blockName]);
        }

        $island->addToStat("blocks_placed", 1);
      } else {

        $event->cancel();
        return;
      }
    } elseif (!$player->hasPermission("redskyblock.bypass")) {

      $event->cancel();
      return;
    }
  }

  public function onTeleport(EntityTeleportEvent $event) {

    $entity = $event->getEntity();
    if ($entity instanceof Player) {

      $entityEndWorld = $event->getTo()->world;
      $masterWorld = $this->plugin->islandManager->getMasterWorld();
      if ($entityEndWorld !== $masterWorld && !$entity->hasPermission("redskyblock.admin")) {

        if ($entity->getAllowFlight()) {

          $entity->setAllowFlight(false);
          $entity->setFlying(false);
        }
      }
    }
  }

  public function onDamage(EntityDamageEvent $event) {

    $plugin = $this->plugin;
    $entity = $event->getEntity();
    $masterWorld = $plugin->islandManager->getMasterWorld();
    if ($entity instanceof Player) {

      $cause = $event->getCause();
      if ($cause === EntityDamageEvent::CAUSE_VOID) {

        $island = $plugin->islandManager->getIslandAtPlayer($entity);
        if ($island instanceof Island) {

          $islandSettings = $island->getSettings();
          if ($islandSettings["safevoid"]) {

            $event->cancel();

            $islandSpawn = $island->getSpawnPoint();
            $entity->teleport(new Position($islandSpawn[0], $islandSpawn[1], $islandSpawn[2], $masterWorld));
          }
        }
      } elseif ($cause === EntityDamageEvent::CAUSE_FALL) {

        $playerWorld = $entity->getWorld();
        if ($playerWorld === $masterWorld) {

          if (!$plugin->cfg->get("Fall Damage")) {

            $event->cancel();
          }
        }
      }
    }
  }

  public function onDamageByEntity(EntityDamageByEntityEvent $event) {

    $plugin = $this->plugin;
    $attackingEntity = $event->getDamager();
    $entity = $event->getEntity();

    if ($attackingEntity instanceof Player && $entity instanceof Player) {

      $island = $plugin->islandManager->getIslandAtPlayer($entity);
      if ($island instanceof Island) {

        $islandSettings = $island->getSettings();
        if (!$islandSettings["pvp"]) {

          $event->cancel();
        }
      }
    }
  }

  public function onExhaust(PlayerExhaustEvent $event) {

    $plugin = $this->plugin;
    $player = $event->getPlayer();
    $playerWorld = $player->getWorld();
    $masterWorld = $plugin->islandManager->getMasterWorld();
    $doHunger = $plugin->cfg->get("Island Hunger");

    if ($playerWorld === $masterWorld && !$doHunger) {

      $player->getHungerManager()->addFood(10);
    }
  }

  public function onPickup(EntityItemPickupEvent $event) {

    $entity = $event->getEntity();
    if ($entity instanceof Player) {

      $island = $this->plugin->islandManager->getIslandAtPlayer($entity);
      if ($island instanceof Island) {

        $islandSettings = $island->getSettings();
        $islandMembers = $island->getMembers();
        $islandCreator = $island->getCreator();

        if ($entity->getName() !== $islandCreator) {

          if (!$entity->hasPermission("redskyblock.bypass")) {

            if (!array_key_exists(strtolower($entity->getName()), $islandMembers)) {

              if (!$islandSettings["visitor_pickup"]) {

                $event->cancel();
              }
            }
          }
        }
      }
    }
  }

  public function onDeath(PlayerDeathEvent $event) {

    $keepInventory = $this->plugin->cfg->get("Keep Inventory");
    $playerWorld = $event->getPlayer()->getWorld();
    $masterWorld = $this->plugin->islandManager->getMasterWorld();

    if ($playerWorld === $masterWorld && $keepInventory) {

      $event->setKeepInventory(true);
    }
  }

  public function onMove(PlayerMoveEvent $event) {

    $player = $event->getPlayer();
    $playerWorld = $player->getWorld();
    $masterWorld = $this->plugin->islandManager->getMasterWorld();

    if ($this->plugin->cfg->get("Island Boundaries")) {

      if ($playerWorld === $masterWorld) {

        $island = $this->plugin->islandManager->getIslandAtPlayer($player);
        if (!$island instanceof Island && !$player->hasPermission("redskyblock.bypass")) {

          $event->cancel();
        }
      }
    }
  }

  public function onChat(PlayerChatEvent $event) {

    $player = $event->getPlayer();
    $message = $event->getMessage();
    $channel = $this->plugin->islandManager->searchIslandChannels($player->getName());

    if ($channel instanceof Island) {

      $recipients = [];
      foreach($channel->getChatters() as $playerName) {

        $recipient = $this->plugin->getServer()->getPlayerExact($playerName);
        $recipients[] = $recipient;
      }

      $event->setMessage(TextFormat::RED . TextFormat::BOLD . $channel->getName() . TextFormat::RESET . ": " . $message);
      $event->setRecipients($recipients);
    }
  }

  public function onBucket(PlayerBucketEvent $event) {

    $player = $event->getPlayer();
    $block = $event->getBlockClicked();

    $island = $this->plugin->islandManager->getIslandAtBlock($block);
    if ($island instanceof Island) {

      $members = $island->getMembers();
      if (array_key_exists(strtolower($player->getName()), $members)) {

        $islandPermissions = $island->getPermissions();
        $playerRank = $members[strtolower($player->getName())];
        if (!in_array("island.interact", $islandPermissions[$playerRank])) {

          $event->cancel();
        }
      } elseif (!($player->getName() === $island->getCreator() || $player->hasPermission("redskyblock.bypass"))) {

        $event->cancel();
      }
    }
  }
}

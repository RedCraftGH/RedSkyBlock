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
use pocketmine\player\Player;
use pocketmine\event\entity\EntityWorldChangeEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\world\Position;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\math\Vector3;

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

    if ($player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") || $player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") . "-Nether") {

      $owner = $plugin->getIslandAtBlock($block);
      $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
      $playerData;

      if ($owner === null) {

        if (!$player->hasPermission("redskyblock.bypass")) {

          $event->cancel();
          return;
        }
      }

      if (file_exists($filePath)) {

        $playerDataEncoded = file_get_contents($filePath);
        $playerData = (array) json_decode($playerDataEncoded, true);
      } else {

        return;
      }
      if ($owner === strtolower($player->getName()) || in_array(strtolower($player->getName()), $playerData["Island Members"]) || $player->hasPermission("skyblock.bypass")) {

        $valuableArray = $plugin->cfg->get("Valuable Blocks", []);

        if (array_key_exists(strval($block->getID()), $valuableArray)) {

          $playerData["Value"] += $valuableArray[strval($block->getID())];
          $playerDataEncoded = json_encode($playerData);
          file_put_contents($filePath, $playerDataEncoded);

          $scoreHud = $plugin->getServer()->getPluginManager()->getPlugin("ScoreHud");
          if ($scoreHud !== null && $scoreHud->isEnabled()) {

            $ev1 = new \Ifera\ScoreHud\event\PlayerTagUpdateEvent(
              $plugin->getServer()->getPlayerExact($owner),
              new \Ifera\ScoreHud\scoreboard\ScoreTag("redskyblock.islevalue", strval($plugin->getIslandValue($plugin->getServer()->getPlayerExact($owner))))
            );
            $ev1->call();
            $ev2 = new \Ifera\ScoreHud\event\PlayerTagUpdateEvent(
              $plugin->getServer()->getPlayerExact($owner),
              new \Ifera\ScoreHud\scoreboard\ScoreTag("redskyblock.islerank", "#" . strval($plugin->getIslandRank($plugin->getServer()->getPlayerExact($owner))))
            );
            $ev2->call();
          }

          return;
        }
      } else {

        $event->cancel();
        return;
      }
    }
  }
  public function onBreak(BlockBreakEvent $event) {

    $player = $event->getPlayer();
    $block = $event->getBlock();
    $plugin = $this->plugin;

    if ($player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") || $player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") . "-Nether") {

      $owner = $plugin->getIslandAtBlock($block);
      $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
      $playerData;

      if ($owner === null) {

        if (!$player->hasPermission("redskyblock.bypass")) {

          $event->cancel();
          return;
        }
      }

      if (file_exists($filePath)) {

        $playerDataEncoded = file_get_contents($filePath);
        $playerData = (array) json_decode($playerDataEncoded, true);
      } else {

        return;
      }
      if ($owner === strtolower($player->getName()) || in_array(strtolower($player->getName()), $playerData["Island Members"]) || $player->hasPermission("skyblock.bypass")) {

        $valuableArray = $plugin->cfg->get("Valuable Blocks", []);

        if (array_key_exists(strval($block->getID()), $valuableArray)) {

          $playerData["Value"] -= $valuableArray[strval($block->getID())];
          if ($playerData["Value"] < 0) {

            $playerData["Value"] = 0;
          }
          $playerDataEncoded = json_encode($playerData);
          file_put_contents($filePath, $playerDataEncoded);

          $scoreHud = $plugin->getServer()->getPluginManager()->getPlugin("ScoreHud");
          if ($scoreHud !== null && $scoreHud->isEnabled()) {

            $ev1 = new \Ifera\ScoreHud\event\PlayerTagUpdateEvent(
              $plugin->getServer()->getPlayerExact($owner),
              new \Ifera\ScoreHud\scoreboard\ScoreTag("redskyblock.islevalue", strval($plugin->getIslandValue($plugin->getServer()->getPlayerExact($owner))))
            );
            $ev1->call();
            $ev2 = new \Ifera\ScoreHud\event\PlayerTagUpdateEvent(
              $plugin->getServer()->getPlayerExact($owner),
              new \Ifera\ScoreHud\scoreboard\ScoreTag("redskyblock.islerank", "#" . strval($plugin->getIslandRank($plugin->getServer()->getPlayerExact($owner))))
            );
            $ev2->call();
          }
          return;
        }
      } else {

        $event->cancel();
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

      if ($player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") || $player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") . "-Nether") {

        $owner = $plugin->getIslandAtBlock($block);
        $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
        $playerData;

        if ($owner === null) {

          if (!$player->hasPermission("redskyblock.bypass")) {

            $event->cancel();
            $player->sendMessage(TextFormat::RED . "You cannot use this here!");
            return;
          }
        }

        if (file_exists($filePath)) {

          $playerDataEncoded = file_get_contents($filePath);
          $playerData = (array) json_decode($playerDataEncoded, true);
        } else {

          return;
        }

        if ($owner === strtolower($player->getName()) || in_array(strtolower($player->getName()), $playerData["Island Members"]) || $player->hasPermission("skyblock.bypass")) {

          return;
        } else {

          $event->cancel();
          $player->sendMessage(TextFormat::RED . "You cannot use this here!");
          return;
        }
      }
    }
  }
  public function onBucketEvent(PlayerBucketEvent $event) {

    $player = $event->getPlayer();
    $plugin = $this->plugin;

    if ($player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") || $player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") . "-Nether") {

      $owner = $plugin->getIslandAtPlayer($player);
      $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
      $playerData;

      if ($owner === null) {

        if (!$player->hasPermission("redskyblock.bypass")) {

          $event->cancel();
          $player->sendMessage(TextFormat::RED . "You cannot use this here!");
          return;
        }
      }

      if (file_exists($filePath)) {

        $playerDataEncoded = file_get_contents($filePath);
        $playerData = (array) json_decode($playerDataEncoded, true);
      } else {

        return;
      }

      if ($owner === strtolower($player->getName()) || in_array(strtolower($player->getName()), $playerData["Island Members"]) || $player->hasPermission("skyblock.bypass")) {

        return;
      } else {

        $event->cancel();
        $player->sendMessage(TextFormat::RED . "You cannot use this here!");
        return;
      }
    }
  }
  public function onDamageByEntity(EntityDamageByEntityEvent $event) {

    $entity = $event->getEntity();
    $damager = $event->getDamager();
    $plugin = $this->plugin;
    $masterWorld = $plugin->skyblock->get("Master World");

    if (($entity instanceof Player && $damager instanceof Player) && ($entity->getWorld()->getFolderName() === $masterWorld || $entity->getWorld()->getFolderName() === $masterWorld . "-Nether")) {

      if ($plugin->cfg->get("Island PVP") === "off") {

        $event->cancel();
        return;
      }
    }
  }
  public function onPickup(EntityItemPickupEvent $event) {

    $entity = $event->getOrigin();
    $plugin = $this->plugin;

    if ($entity instanceof Player) {

      if ($entity->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") || $entity->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") . "-Nether") {

        $owner = $plugin->getIslandAtPlayer($entity);
        $filePath = $plugin->getDataFolder() . "Players/" . $owner . ".json";
        $playerData;

        if ($owner === null) {

          if (!$entity->hasPermission("redskyblock.bypass")) {

            $event->cancel();
            return;
          }
        }

        if (file_exists($filePath)) {

          $playerDataEncoded = file_get_contents($filePath);
          $playerData = (array) json_decode($playerDataEncoded, true);
        } else {

          return;
        }

        if ($owner === strtolower($entity->getName()) || in_array(strtolower($entity->getName()), $playerData["Island Members"]) || $entity->hasPermission("skyblock.bypass")) {

          return;
        } else {

          $event->cancel();
          return;
        }
      }
    }
  }
  public function onJoin(PlayerJoinEvent $event) {

    $plugin = $this->plugin;
    $player = $event->getPlayer();
    $spawn = $plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();

    if ($plugin->cfg->get("Spawn Command") === "on") {

      $player->teleport($spawn);
    }
  }
  public function onExhaust(PlayerExhaustEvent $event) {

    $player = $event->getPlayer();
    $plugin = $this->plugin;

    if ($player->getWorld()->getFolderName() === $plugin->getServer()->getWorldManager()->getDefaultWorld()->getFolderName() && $plugin->cfg->get("Spawn Hunger") === "off") {

      $event->cancel();
      return;
    } elseif (($player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") || $player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") . "-Nether") && $plugin->cfg->get("Island Hunger") === "off") {

      $player->addFood(10);
      $event->cancel();
      return;
    }
  }
  public function onDamage(EntityDamageEvent $event) {

    $entity = $event->getEntity();
    $plugin = $this->plugin;
    $spawn = $plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();
    $masterWorld = $plugin->skyblock->get("Master World");

    if (($entity instanceof Player && $entity->getWorld()->getFolderName() === $plugin->getServer()->getWorldManager()->getDefaultWorld()->getFolderName()) && $plugin->cfg->get("Spawn Damage") === "off") {

      $event->cancel();
      return;
    } elseif ($entity instanceof Player && $entity->getWorld()->getFolderName() === $masterWorld || $entity->getWorld()->getFolderName() === $masterWorld . "-Nether") {

      if ($plugin->cfg->get("Safe Void") && ($entity->getWorld()->getFolderName() === $masterWorld || $entity->getWorld()->getFolderName() === $masterWorld . "-Nether") && $entity->getPosition()->y <= 0) {

        $island = $plugin->getIslandAtPlayer($entity);
        $filePath = $plugin->getDataFolder() . "Players/" . $island . ".json";
        $playerDataEncoded = file_get_contents($filePath);
        $playerData = (array) json_decode($playerDataEncoded);
        $event->cancel();

        if ($entity->getWorld()->getFolderName() === $masterWorld) {

          $x = $playerData["Island Spawn"][0];
          $y = $playerData["Island Spawn"][1];
          $z = $playerData["Island Spawn"][2];
          $entity->teleport(new Position($x, $y, $z, $plugin->getServer()->getWorldManager()->getWorldByName($masterWorld)));
          return;
        } else {

          $x = $playerData["Nether Spawn"][0];
          $y = $playerData["Nether Spawn"][1];
          $z = $playerData["Nether Spawn"][2];
          $entity->teleport(new Position($x, $y, $z, $plugin->getServer()->getWorldManager()->getWorldByName($masterWorld . "-Nether")));
          return;
        }
      }
      if ($entity->getGamemode() === 0) {

        if ($event->getBaseDamage() >= $entity->getHealth()) {

          $event->cancel();
          $entity->setHealth($entity->getMaxHealth());
          $entity->setFood($entity->getMaxFood());
          $entity->teleport($spawn);
          if (!($plugin->cfg->get("Keep Inventory"))) {

            $entity->getInventory()->clearAll();
          }
          $plugin->getServer()->broadcastMessage(TextFormat::WHITE . $entity->getName() . " has died.");
          return;
        }
      }
    }
  }
  public function onMove(PlayerMoveEvent $event) {

    $player = $event->getPlayer();
    $world = $player->getWorld();
    $plugin = $this->plugin;

    if ($plugin->cfg->get("Island Boundaries")) {

      if ($world->getFolderName() === $plugin->skyblock->get("Master World") || $world->getFolderName() === $plugin->skyblock->get("Master World") . "-Nether") {

        $island = $plugin->getIslandAtPlayer($player);
        if ($island === null && (!$player->hasPermission("skyblock.bypass"))) {

          $event->cancel();
        }
      }
    }
  }
  public function onForm(BlockFormEvent $event) {

    $plugin = $this->plugin;
    $block = $event->getBlock();
    $world = $block->getPosition()->getWorld()->getFolderName();

    $generatorOres = $plugin->cfg->get("Generator Ores", []);
    $masterWorld = $plugin->skyblock->get("Master World");

    if ($world === $masterWorld || $world === $masterWorld . "-Nether") {

      if (count($generatorOres) === 0) {

        return;
      } else {

        if (array_sum($generatorOres) !== 100) {

          return;
        } else {

          $event->cancel();

          $blockID;
          $randomNumber = rand(1, 100);
          $percentChance = 0;

          foreach ($generatorOres as $key => $oreChance) {

            $percentChance += $oreChance;

            if ($randomNumber <= $percentChance) {

              $blockID = $key;
              break;
            }
          }
          $block->getPosition()->getWorld()->setBlock($block->getPosition(), BlockFactory::getInstance()->get($blockID, 0));
          return;
        }
      }
    } else {

      return;
    }
  }
}

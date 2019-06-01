<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\Generators\WorldGenerator;
use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class CreateWorld {

  private static $instance;

  private $worldGenerator;

  public function __construct($plugin) {

    self::$instance = $this;

    $this->plugin = $plugin;

    $this->worldGenerator = new WorldGenerator($plugin);
  }

  public function onCreateWorldCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.createworld")) {

        if (count($args) < 2) {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is createworld <world name>");
          return true;
        } else {

          $world = (string) implode(" ", array_slice($args, 1));

          if (SkyBlock::getInstance()->getServer()->isLevelLoaded($world)) {

            $sender->sendMessage(TextFormat::RED . "The world you are trying to create already exists.");
            return true;
          } else {

            $this->worldGenerator->generateWorld($world);
            $worldsArray = SkyBlock::getInstance()->skyblock->get("SkyBlockWorlds", []);
            array_push($worldsArray, $world);
            SkyBlock::getInstance()->cfg->set("SkyBlockWorld Base Name", $world);
            SkyBlock::getInstance()->cfg->set("SkyBlockWorlds", $worldsArray);
            SkyBlock::getInstance()->cfg->save();
            $sender->sendMessage(TextFormat::WHITE . $world . TextFormat::GREEN . " has been created and set as the SkyBlock base world in this server.");
            return true;
          }
        }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

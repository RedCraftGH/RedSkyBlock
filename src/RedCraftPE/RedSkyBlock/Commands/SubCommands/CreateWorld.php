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

    $this->worldGenerator = new WorldGenerator($this->plugin);
  }

  public function onCreateWorldCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.createworld")) {

        if (count($args) < 2) {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is createworld <world name>");
          return true;
        } else {

          $levelName = (string) implode(" ", array_slice($args, 1));

          if (SkyBlock::getInstance()->getServer()->isLevelLoaded($levelName)) {

            $sender->sendMessage(TextFormat::RED . "The world you are trying to create already exists.");
            return true;
          } else {

            $this->worldGenerator->generateWorld($levelName);
            SkyBlock::getInstance()->cfg->set("SkyBlockWorld", $levelName);
            SkyBlock::getInstance()->cfg->save();
            $sender->sendMessage(TextFormat::WHITE . $levelName . TextFormat::GREEN . " has been created and set as the SkyBlock world in this server.");
            return true;
          }
        }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

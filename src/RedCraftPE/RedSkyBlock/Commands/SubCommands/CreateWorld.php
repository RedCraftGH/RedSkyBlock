<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class CreateWorld {

  private $worldGenerator;

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onCreateWorldCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.createworld")) {

        if (count($args) < 2) {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is createworld <world name>");
          return true;
        } else {

          $plugin = $this->plugin;

          $world = (string) implode(" ", array_slice($args, 1));

          if ($plugin->getServer()->loadLevel($world)) {

            $sender->sendMessage(TextFormat::RED . "The world you are trying to create already exists.");
            return true;
          } else {

            $plugin->getServer()->generateLevel($world, null, 'pocketmine\level\generator\Flat', ["preset" => "3;minecraft:air;127;"]);
            $sender->sendMessage(TextFormat::GREEN . "The empty world " . TextFormat::WHITE . $world . TextFormat::GREEN . " has been created for SkyBlock.");
            return true;
          }
        }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

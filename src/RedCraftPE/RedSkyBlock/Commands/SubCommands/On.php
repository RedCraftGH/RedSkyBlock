<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class On {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onOnCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.onisland")) {

      if (array_key_exists(strtolower($sender->getName()), SkyBlock::getInstance()->skyblock->get("SkyBlock", []))) {

        $playerArray = SkyBlock::getInstance()->getPlayersOnIsland($sender);

        if (count($playerArray) <= 0) {

          $sender->sendMessage(TextFormat::RED . "No players are on your island.");
          return true;
        } else {

          $sender->sendMessage(TextFormat::GREEN . implode(", ", $playerArray));
          return true;
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "You do not have an island yet.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

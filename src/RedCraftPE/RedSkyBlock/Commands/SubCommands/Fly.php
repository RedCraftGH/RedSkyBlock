<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Fly {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function onFlyCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.fly")) {

      if ($sender->getLevel()->getFolderName() === SkyBlock::getInstance()->cfg->get("SkyBlockWorld")) {

        if ($sender->getAllowFlight()) {

          $sender->setFlying(false);
          $sender->setAllowFlight(false);
          $sender->sendMessage(TextFormat::GREEN . "Flight has been disabled.");
          return true;
        } else {

          $sender->setAllowFlight(true);
          $sender->sendMessage(TextFormat::GREEN . "Flight has been enabled.");
          return true;
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "You must be in the SkyBlock world to use this command.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

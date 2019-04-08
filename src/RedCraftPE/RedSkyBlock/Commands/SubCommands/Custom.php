<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;

class Custom {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function onCustomCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.custom")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is custom <on/off>");
        return true;
      } else {

        if ($args[1] === "on") {

          if (SkyBlock::getInstance()->skyblock->get("Blocks") === []) {

            $sender->sendMessage(TextFormat::RED . "You must create and set a custom island preset before enabling custom islands.");
            return true;
          } else {

            SkyBlock::getInstance()->skyblock->set("Custom", true);
            SkyBlock::getInstance()->skyblock->save();
            $sender->sendMessage(TextFormat::GREEN . "Custom Islands have been enabled!");
            return true;
          }
        } else if ($args[1] === "off") {

          SkyBlock::getInstance()->skyblock->set("Custom", false);
          SkyBlock::getInstance()->skyblock->save();
          $sender->sendMessage(TextFormat::GREEN . "Custom Islands have been disabled");
          return true;
        } else {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is custom <on/off>");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

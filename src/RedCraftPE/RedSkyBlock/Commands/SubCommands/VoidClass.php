<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class VoidClass {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onVoidCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.void")) {

      $void = SkyBlock::getInstance()->cfg->get("Void");

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is void <on/off>");
        return true;
      } else {

        if ($args[1] === "on") {

          $void = true;
          SkyBlock::getInstance()->cfg->set("Void", $void);
          SkyBlock::getInstance()->cfg->save();
          $sender->sendMessage(TextFormat::GREEN . "The void has been enabled.");
          return true;
        } else if ($args[1] === "off") {

          $void = false;
          SkyBlock::getInstance()->cfg->set("Void", $void);
          SkyBlock::getInstance()->cfg->save();
          $sender->sendMessage(TextFormat::GREEN . "The void has been disabled.");
          return true;
        } else {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is void <on/off>");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

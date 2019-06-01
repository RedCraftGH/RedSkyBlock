<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Hunger {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onHungerCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.hunger")) {

      $hunger = SkyBlock::getInstance()->cfg->get("Hunger");

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is hunger <on/off>");
        return true;
      } else {

        if ($args[1] === "on") {

          $hunger = true;
          SkyBlock::getInstance()->cfg->set("Hunger", $hunger);
          SkyBlock::getInstance()->cfg->save();
          $sender->sendMessage(TextFormat::GREEN . "Hunger has been enabled.");
          return true;
        } else if ($args[1] === "off") {

          $hunger = false;
          SkyBlock::getInstance()->cfg->set("Hunger", $hunger);
          SkyBlock::getInstance()->cfg->save();
          $sender->sendMessage(TextFormat::GREEN . "Hunger has been disabled.");
          return true;
        } else {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is hunger <on/off>");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

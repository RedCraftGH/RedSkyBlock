<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Unlock {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onUnlockCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.lock")) {

      $senderName = strtolower($sender->getName());
      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);

      if (array_key_exists($senderName, $skyblockArray)) {

        if ($skyblockArray[$senderName]["Locked"] === false) {

          $sender->sendMessage(TextFormat::RED . "Your island is not locked.");
          return true;
        } else {

          $skyblockArray[$senderName]["Locked"] = false;
          SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
          SkyBlock::getInstance()->skyblock->save();
          $sender->sendMessage(TextFormat::GREEN . "Your island is now unlocked.");
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

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Members {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onMembersCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.members")) {

      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $senderName = strtolower($sender->getName());

      if (array_key_exists($senderName, $skyblockArray)) {

        $memberArray = $skyblockArray[$senderName]["Members"];
        $members = implode(", ", $memberArray);
        $sender->sendMessage($members);
        return true;
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

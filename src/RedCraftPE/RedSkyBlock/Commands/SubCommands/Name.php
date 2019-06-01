<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Name {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onNameCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.name")) {

      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $senderName = strtolower($sender->getName());
      $nameLimit = SkyBlock::getInstance()->cfg->get("Name Char Limit");
      if (array_key_exists($senderName, $skyblockArray)) {

        $name = $skyblockArray[$senderName]["Name"];

        if (count($args) < 2) {

          $sender->sendMessage(TextFormat::GREEN . "Your island's name is " . TextFormat::WHITE . $name);
          return true;
        } else {

          $name = (string) implode(" ", array_slice($args, 1));
          if (strlen($name) > $nameLimit) {

            $sender->sendMessage(TextFormat::RED . "That name is too long! Island names can be {$nameLimit} characters maximum.");
            return true;
          } else {

            $skyblockArray[$senderName]["Name"] = $name;
            SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
            SkyBlock::getInstance()->skyblock->save();
            $sender->sendMessage(TextFormat::GREEN . "Your island's name is now " . TextFormat::WHITE . $name);
            return true;
          }
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "You have not created an island yet.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Leave {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onLeaveCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.members")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is leave <player>");
        return true;
      } else {

        $name = strtolower(implode(" ", array_slice($args, 1)));
        $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);

        if (array_key_exists($name, $skyblockArray)) {

          if (in_array($sender->getName(), $skyblockArray[$name]["Members"])) {

            if ($name === strtolower($sender->getName())) {

              $sender->sendMessage(TextFormat::RED . "You cannot leave your own island.");
              return true;
            } else {

              unset($skyblockArray[$name]["Members"][array_search($sender->getName(), $skyblockArray[$name]["Members"])]);
              SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
              SkyBlock::getInstance()->skyblock->save();
              $sender->sendMessage(TextFormat::GREEN . "You are no longer a member of " . TextFormat::WHITE . $skyblockArray[$name]["Members"][0] . "'s" . TextFormat::GREEN . "island.");
              return true;
            }
          } else {

            $sender->sendMessage(TextFormat::RED . "You are not a member of this island.");
            return true;
          }
        } else {

          $sender->sendMessage(TextFormat::WHITE . $name . TextFormat::RED . " does not have an island.");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

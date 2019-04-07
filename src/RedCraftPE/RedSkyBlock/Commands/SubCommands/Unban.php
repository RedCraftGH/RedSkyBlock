<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Unban {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onUnbanCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.ban")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is unban <player>");
        return true;
      } else {

        $senderName = strtolower($sender->getName());
        $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
        $player = SkyBlock::getInstance()->getServer()->getPlayerExact(implode(" ", array_slice($args, 1)));

        if (!$player) {

          $sender->sendMessage(TextFormat::WHITE . implode(" ", array_slice($args, 1)) . TextFormat::RED . " does not exist or is not online.");
          return true;
        } else {

          if ($player->getName() === $sender->getName()) {

            $sender->sendMessage(TextFormat::RED . "You are not banned from your own island");
            return true;
          }
          if (array_key_exists($senderName, $skyblockArray)) {

            if (in_array($player->getName(), $skyblockArray[$senderName]["Banned"])) {

              unset($skyblockArray[$senderName]["Banned"][array_search($player->getName(), $skyblockArray[$senderName]["Banned"])]);
              SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
              SkyBlock::getInstance()->skyblock->save();
              $sender->sendMessage(TextFormat::WHITE . $player->getName() . TextFormat::GREEN . " is no longer banned from your island.");
              $player->sendMessage(TextFormat::WHITE . $sender->getName() . TextFormat::RED . " has unbanned you from their island.");
              return true;
            } else {

              $sender->sendMessage(TextFormat::WHITE . "{$player->getName()}" . TextFormat::RED . " is not banned from your island.");
              return true;
            }
          } else {

            $sender->sendMessage(TextFormat::RED . "You do not have an island yet.");
            return true;
          }
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

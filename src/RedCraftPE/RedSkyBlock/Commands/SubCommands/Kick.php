<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Kick {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onKickCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.kick")) {

      $senderName = strtolower($sender->getName());
      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);

      if (array_key_exists($senderName, $skyblockArray)) {

        if (count($args) < 2) {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is kick <player>");
          return true;
        } else {

          $player = SkyBlock::getInstance()->getServer()->getPlayerExact(implode(" ", array_slice($args, 1)));
          if (!$player) {

            $sender->sendMessage(TextFormat::WHITE . implode(" ", array_slice($args, 1)) . TextFormat::RED . " does not exist or is not online.");
            return true;
          } else {

            if ($player !== $sender) {

              $playerX = $player->getX();
              $playerY = $player->getY();
              $playerZ = $player->getZ();
              $startX = $skyblockArray[$senderName]["Area"]["start"]["X"];
              $startY = $skyblockArray[$senderName]["Area"]["start"]["Y"];
              $startZ = $skyblockArray[$senderName]["Area"]["start"]["Z"];
              $endX = $skyblockArray[$senderName]["Area"]["end"]["X"];
              $endY = $skyblockArray[$senderName]["Area"]["end"]["Y"];
              $endZ = $skyblockArray[$senderName]["Area"]["end"]["Z"];

              if ($playerX > $startX && $playerY > $startY && $playerZ > $startZ && $playerX < $endX && $playerY < $endY && $playerZ < $endZ) {

                $player->teleport($player->getSpawn());
                $player->sendMessage(TextFormat::WHITE . $sender->getName() . TextFormat::RED . " has kicked you off of their island.");
                $sender->sendMessage(TextFormat::WHITE . $player->getName() . TextFormat::GREEN . " has been kicked off of your island.");
                return true;
              } else {

                $sender->sendMessage(TextFormat::WHITE . $player->getName() . TextFormat::RED . " is not on your island.");
                return true;
              }
            } else {

              $sender->sendMessage(TextFormat::RED . "You cannot kick yourself off of your island.");
              return true;
            }
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

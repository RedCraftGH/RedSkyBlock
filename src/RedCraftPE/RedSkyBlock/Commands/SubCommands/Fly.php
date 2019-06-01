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
      $worldsArray = SkyBlock::getInstance()->cfg->get("SkyBlockWorlds", []);

      if (in_array($sender->getLevel()->getFolderName(), $worldsArray)) {

        $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
        $playerX = $sender->getX();
        $playerY = $sender->getY();
        $playerZ = $sender->getZ();
        $islandOwner = "";

        if ($sender->getAllowFlight()) {

          $sender->setFlying(false);
          $sender->setAllowFlight(false);
          $sender->sendMessage(TextFormat::GREEN . "Flight has been disabled.");
          return true;
        } else {

          foreach (array_keys($skyblockArray) as $skyblocks) {

            $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
            $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
            $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
            $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
            $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
            $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];

            if ($playerX > $startX && $playerY > $startY && $playerZ > $startZ && $playerX < $endX && $playerY < $endY && $playerZ < $endZ) {

              $islandOwner = $skyblocks;
              break;
            }
          }
          if ($islandOwner === "") {

            $sender->setAllowFlight(true);
            $sender->sendMessage(TextFormat::GREEN . "Flight has been enabled.");
            return true;
          } else if (in_array($sender->getName(), $skyblockArray[$islandOwner]["Members"])) {

            $sender->setAllowFlight(true);
            $sender->sendMessage(TextFormat::GREEN . "Flight has been enabled.");
            return true;
          } else {

            if ($skyblockArray[$islandOwner]["Settings"]["Fly"] === "on") {

              $sender->setAllowFlight(true);
              $sender->sendMessage(TextFormat::GREEN . "Flight has been enabled.");
              return true;
            } else {

              $sender->sendMessage(TextFormat::RED . "The owner of this island has disabled flight here.");
              return true;
            }
          }
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

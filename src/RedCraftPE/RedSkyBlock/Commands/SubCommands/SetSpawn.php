<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;

class SetSpawn {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onSetSpawnCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.setspawn")) {

      $senderName = strtolower($sender->getName());
      $xPos = round($sender->getX());
      $yPos = round($sender->getY());
      $zPos = round($sender->getZ());
      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $startX = $skyblockArray[$senderName]["Area"]["start"]["X"];
      $startY = $skyblockArray[$senderName]["Area"]["start"]["Y"];
      $startZ = $skyblockArray[$senderName]["Area"]["start"]["Z"];
      $endX = $skyblockArray[$senderName]["Area"]["end"]["X"];
      $endY = $skyblockArray[$senderName]["Area"]["end"]["Y"];
      $endZ = $skyblockArray[$senderName]["Area"]["end"]["Z"];

      if (array_key_exists($senderName, $skyblockArray)) {

        if ($xPos > $startX && $yPos > $startY && $zPos > $startZ && $xPos < $endX && $yPos < $endY && $zPos < $endZ) {

          $skyblockArray[$senderName]["Spawn"]["X"] = $xPos;
          $skyblockArray[$senderName]["Spawn"]["Y"] = $yPos;
          $skyblockArray[$senderName]["Spawn"]["Z"] = $zPos;

          SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
          SkyBlock::getInstance()->skyblock->save();

          $sender->sendMessage(TextFormat::GREEN . "Your island spawn position has been set at " . TextFormat::WHITE . "{$xPos}, {$yPos}, {$zPos}" . TextFormat::GREEN . ".");
          return true;
        } else {

          $sender->sendMessage(TextFormat::RED . "You must be within your island borders to set your islands spawn position!");
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

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;

class Pos1 {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function onPos1Command(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.pos")) {

      $xPos = round($sender->getX());
      $yPos = round($sender->getY());
      $zPos = round($sender->getZ());

      SkyBlock::getInstance()->skyblock->set("x1", $xPos);
      SkyBlock::getInstance()->skyblock->set("y1", $yPos);
      SkyBlock::getInstance()->skyblock->set("z1", $zPos);
      SkyBlock::getInstance()->skyblock->set("Pos1", true);
      SkyBlock::getInstance()->skyblock->save();
      $sender->sendMessage(TextFormat::GREEN . "Position 1 has been set at" . TextFormat::WHITE . " {$xPos}, {$yPos}, {$zPos}.");
      return true;
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

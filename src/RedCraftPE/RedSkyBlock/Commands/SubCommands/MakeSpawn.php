<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;

class MakeSpawn {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function onMakeSpawnCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.makespawn")) {

      if (SkyBlock::getInstance()->skyblock->get("Blocks") !== []) {

        $xPos = ceil($sender->getX());
        $yPos = ceil($sender->getY());
        $zPos = ceil($sender->getZ());

        $x = min(0 + SkyBlock::getInstance()->skyblock->get("x1"), 0 + SkyBlock::getInstance()->skyblock->get("x2"));
        $y = min(SkyBlock::getInstance()->skyblock->get("y1"), SkyBlock::getInstance()->skyblock->get("y2"));
        $z = min(0 + SkyBlock::getInstance()->skyblock->get("z1"), 0 + SkyBlock::getInstance()->skyblock->get("z2"));

        $distanceFromX1 = $xPos - $x;
        $distanceFromY1 = ($yPos - $y) + 1;
        $distanceFromZ1 = $zPos - $z;

        SkyBlock::getInstance()->skyblock->set("CustomX", $distanceFromX1);
        SkyBlock::getInstance()->skyblock->set("CustomY", $distanceFromY1);
        SkyBlock::getInstance()->skyblock->set("CustomZ", $distanceFromZ1);
        SkyBlock::getInstance()->skyblock->save();
        $sender->sendMessage(TextFormat::GREEN . "The custom island spawnpoint has been created!");
        return true;
      } else {

        $sender->sendMessage(TextFormat::RED . "You must create and set a custom island preset before making a custom island spawnpoint.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class SetWorld {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onSetWorldCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.setworld")) {

      $world = $sender->getLevel()->getFolderName();
      $worldsArray = SkyBlock::getInstance()->cfg->get("SkyBlockWorlds", []);

      if (in_array($world, $worldsArray)) {

        $sender->sendMessage(TextFormat::RED . "This world is already set as the SkyBlock base world.");
        return true;
      } else {

        $worldsArray = SkyBlock::getInstance()->cfg->get("SkyBlockWorlds", []);
        array_push($worldsArray, $world);
        SkyBlock::getInstance()->skyblock->set("Islands", 0);
        SkyBlock::getInstance()->cfg->set("SkyBlockWorld Base Name", $world);
        SkyBlock::getInstance()->cfg->set("SkyBlockWorlds", $worldsArray);
        SkyBlock::getInstance()->cfg->save();
        $sender->sendMessage(TextFormat::GREEN . $world . " has been set as the SkyBlock base world on this server.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

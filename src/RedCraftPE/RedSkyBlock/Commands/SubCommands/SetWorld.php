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
      SkyBlock::getInstance()->cfg->set("SkyBlockWorld", $world);
      SkyBlock::getInstance()->cfg->save();
      $sender->sendMessage(TextFormat::GREEN . $world . " has been set as the SkyBlock world on this server.");
      return true;
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

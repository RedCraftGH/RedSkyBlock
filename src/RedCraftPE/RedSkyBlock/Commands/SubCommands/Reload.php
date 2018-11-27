<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Reload {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onReloadCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.reload")) {

      SkyBlock::getInstance()->skyblock->reload();
      SkyBlock::getInstance()->cfg->reload();
      $sender->sendMessage(TextFormat::GREEN . "All SkyBlock data has been reloaded.");
      return true;
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

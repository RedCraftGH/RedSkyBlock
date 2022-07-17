<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Utils\ZoneManager;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

class UpdateZone extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.admin;redskyblock.zone");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if ($this->checkZone()) {

      ZoneManager::updateZone();

      $message = $this->getMShop()->construct("UPDATE_ZONE");
      $sender->sendMessage($message);
    } else {

      $message = $this->getMShop()->construct("NO_ZONE");
      $sender->sendMessage($message);
    }
  }
}

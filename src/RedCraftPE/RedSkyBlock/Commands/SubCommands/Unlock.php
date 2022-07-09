<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

class Unlock extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if ($this->checkIsland($sender)) {

      $island = $this->plugin->islandManager->getIsland($sender);

      if ($island->unlock()) {

        $message = $this->getMShop()->construct("UNLOCKED");
        $sender->sendMessage($message);
      } else {

        $message = $this->getMShop()->construct("ALREADY_UNLOCKED");
        $sender->sendMessage($message);
      }
    } else {

      $message = $this->getMShop()->construct("NO_ISLAND");
      $sender->sendMessage($message);
    }
  }
}

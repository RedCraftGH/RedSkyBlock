<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

class OnIsland extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if ($this->checkIsland($sender)) {

      $island = $this->plugin->islandManager->getIsland($sender);
      $playersOnIsland = $this->plugin->islandManager->getPlayersAtIsland($island);
      $playersOnIsland = implode(", ", $playersOnIsland);

      $message = $this->getMShop()->construct("PLAYERS_ON_ISLAND");
      $message = str_replace("{PLAYERS}", $playersOnIsland, $message);
      $sender->sendMessage($message);
    } else {

      $message = $this->getMShop()->construct("NO_ISLAND");
      $sender->sendMessage($message);
    }
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

class Teleport extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if ($this->checkIsland($sender)) {

      $island = $this->plugin->islandManager->getIsland($sender);
      $spawnPoint = $island->getSpawnPoint();
      $masterWorld = $this->plugin->islandManager->getMasterWorld();
      if ($masterWorld === null) return;
      $sender->teleport(new Position($spawnPoint[0], $spawnPoint[1], $spawnPoint[2], $masterWorld));

      $message = $this->getMShop()->construct("GO_HOME");
      $sender->sendMessage($message);
    } else {

      $message = $this->getMShop()->construct("NO_ISLAND");
      $sender->sendMessage($message);
    }
  }
}

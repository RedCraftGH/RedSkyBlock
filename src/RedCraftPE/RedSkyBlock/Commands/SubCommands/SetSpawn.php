<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

class SetSpawn extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if ($this->checkIsland($sender)) {

      $island = $this->plugin->islandManager->getIsland($sender);
      if ($this->plugin->islandManager->isOnIsland($sender, $island)) {

        $senderPos = $sender->getPosition();
        $spawnPoint = [round($senderPos->x), round($senderPos->y), round($senderPos->z)];
        $island->setSpawnPoint($spawnPoint);

        $message = $this->getMShop()->construct("SPAWN_CHANGED");
        $message = str_replace("{X}", round($senderPos->x), $message);
        $message = str_replace("{Y}", round($senderPos->y), $message);
        $message = str_replace("{Z}", round($senderPos->z), $message);
        $sender->sendMessage($message);
      } else {

        $message = $this->getMShop()->construct("NOT_ON_OWN_ISLAND");
        $sender->sendMessage($message);
      }
    } else {

      $message = $this->getMShop()->construct("NO_ISLAND");
      $sender->sendMessage($message);
    }
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Reset extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if ($this->checkIsland($sender)) {

      $island = $this->plugin->islandManager->getIsland($sender);
      $resetCooldown = $island->getResetCooldown();

      if (Time() >= $resetCooldown) {

        $playersOnIsland = $this->plugin->islandManager->getPlayersAtIsland($island);
        $this->plugin->islandManager->deleteIsland($island);
        Create::getInstance()->onRun($sender, "create", $args);

        foreach ($playersOnIsland as $playerName) {

          $player = $this->plugin->getServer()->getPlayerExact($playerName);
          $message = $this->getMShop()->construct("ISLAND_ON_DELETED");
          $player->sendMessage($message);
          $spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();
          $player->teleport($spawn);
        }
      } else {

        $timeLeft = gmdate("H:i:s", $resetCooldown - Time());
        $message = $this->getMShop()->construct("CANT_RESET_YET");
        $message = str_replace("{TIME}", $timeLeft, $message);
        $sender->sendMessage($message);
      }
    } else {

      $message = $this->getMShop()->construct("NO_ISLAND");
      $sender->sendMessage($message);
    }
  }
}

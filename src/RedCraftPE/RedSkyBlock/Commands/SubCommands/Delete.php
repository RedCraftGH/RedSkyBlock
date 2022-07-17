<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\TextArgument;

class Delete extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.admin");
    $this->registerArgument(0, new TextArgument("island", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    $islandName = $args["island"];
    $island = $this->plugin->islandManager->getIslandByName($islandName);
    if ($island instanceof Island) {

      $playersOnIsland = $this->plugin->islandManager->getPlayersAtIsland($island);
      $spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();
      
      $islandCreator = $this->plugin->getServer()->getPlayerExact($island->getCreator());
      if ($islandCreator instanceof Player) {

        $isOnIsland = $this->plugin->islandManager->isOnIsland($islandCreator, $island);
        if ($isOnIsland) $islandCreator->teleport($spawn);
        $message = $this->getMShop()->construct("ISLAND_DELETED");
        $islandCreator->sendMessage($message);
      }
      $this->plugin->islandManager->deleteIsland($island);

      foreach ($playersOnIsland as $playerName) {

        $player = $this->plugin->getServer()->getPlayerExact($playerName);
        $message = $this->getMShop()->construct("ISLAND_ON_DELETED");
        $player->sendMessage($message);
        $player->teleport($spawn);
      }
    } else {

      $message = $this->getMShop()->construct("COULD_NOT_FIND_ISLAND");
      $message = str_replace("{ISLAND_NAME}", $islandName, $message);
      $sender->sendMessage($message);
    }
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\TextArgument;

class SetSize extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.admin");
    $this->registerArgument(0, new IntegerArgument("size", false));
    $this->registerArgument(1, new TextArgument("name", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    $newSize = $args["size"];
    $maxSize = (int) $this->plugin->cfg->get("Island Max Size");
    if ($newSize > $maxSize) $newSize = $maxSize;
    if ($newSize >= 0) {

      $playerName = $args["name"];
      $island = $this->plugin->islandManager->getIslandByCreatorName($playerName);
      if ($island instanceof Island) {

        $island->setSize($newSize);

        $message = $this->getMShop()->construct("PLAYER_ISLAND_SIZE_CHANGE");
        $message = str_replace("{NAME}", $island->getCreator(), $message);
        $message = str_replace("{SIZE}", $newSize, $message);
        $sender->sendMessage($message);

        $player = $this->plugin->getServer()->getPlayerExact($playerName);
        if ($player instanceof Player) {

          $message = $this->getMShop()->construct("ISLAND_SIZE_CHANGED");
          $message = str_replace("{SIZE}", $newSize, $message);
          $player->sendMessage($message);
        }
      } else {

        $message = $this->getMShop()->construct("PLAYER_HAS_NO_ISLAND");
        $message = str_replace("{NAME}", $playerName, $message);
        $sender->sendMessage($message);
      }
    } else {

      $message = $this->getMShop()->construct("INT_LESS_THAN_ZERO");
      $message = str_replace("{VALUE}", $newSize, $message);
      $sender->sendMessage($message);
    }
  }
}

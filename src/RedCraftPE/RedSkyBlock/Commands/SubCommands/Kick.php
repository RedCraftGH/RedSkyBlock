<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\TextArgument;

class Kick extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new TextArgument("name", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["name"])) {

      if ($this->checkIsland($sender)) {

        $name = $args["name"];
        $island = $this->plugin->islandManager->getIsland($sender);
        $player = $this->plugin->getServer()->getPlayerByPrefix($name);
        if ($player instanceof Player) {

          if ($this->plugin->islandManager->isOnIsland($player, $island)) {

            if ($island->getCreator() !== $sender->getName()) {

              $message = $this->getMShop()->construct("KICKED_PLAYER");
              $message = str_replace("{NAME}", $player->getName(), $message);
              $sender->sendMessage($message);

              $message = $this->getMShop()->construct("KICKED_FROM_ISLAND");
              $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
              $player->sendMessage($message);

              $spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();
              $player->teleport($spawn);
            } else {

              $message = $this->getMShop()->construct("CANT_KICK_SELF");
              $sender->sendMessage($message);
            }
          } else {

            $message = $this->getMShop()->construct("TARGET_NOT_FOUND");
            $message = str_replace("{NAME}", $player->getName(), $message);
            $sender->sendMessage($message);
          }
        } else {

          $message = $this->getMShop()->construct("TARGET_NOT_FOUND");
          $message = str_replace("{NAME}", $name, $message);
          $sender->sendMessage($message);
        }
      } else {

        $message = $this->getMShop()->construct("NO_ISLAND");
        $sender->sendMessage($message);
      }
    } else {

      $this->sendUsage();
      return;
    }
  }
}

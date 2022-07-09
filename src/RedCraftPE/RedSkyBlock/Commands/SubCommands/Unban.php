<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\TextArgument;

class Unban extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new TextArgument("name", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["name"])) {

      if ($this->checkIsland($sender)) {

        $name = $args["name"];
        $island = $this->plugin->islandManager->getIsland($sender);
        if ($island->unban($name)) {

          $message = $this->getMShop()->construct("UNBANNED");
          $message = str_replace("{NAME}", $name, $message);
          $sender->sendMessage($message);

          $player = $this->plugin->getServer()->getPlayerExact($name);
          if ($player instanceof Player) {

            $message = $this->getMShop()->construct("NO_LONGER_BANNED");
            $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
            $player->sendMessage($message);
          }
        } else {

          $message = $this->getMShop()->construct("NOT_BANNED");
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

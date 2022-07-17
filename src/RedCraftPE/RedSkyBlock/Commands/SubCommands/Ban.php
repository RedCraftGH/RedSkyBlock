<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Ban extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new TextArgument("name", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["name"])) {

      if ($this->checkIsland($sender)) {

        $name = $args["name"];
        $island = $this->plugin->islandManager->getIsland($sender);
        $creator = $island->getCreator();
        $island->removeMember($name);

        if (strtolower($name) !== strtolower($creator)) {

          if ($island->ban($name)) {

            $message = $this->getMShop()->construct("BANNED_PLAYER");
            $message = str_replace("{NAME}", $name, $message);
            $sender->sendMessage($message);

            $player = $this->plugin->getServer()->getPlayerExact($name);
            if ($player instanceof Player && !$player->hasPermission("redskyblock.admin")) {

              if ($this->plugin->islandManager->isOnIsland($player, $island)) {

                $spawn = $this->plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();
                $player->teleport($spawn);
              }
              $message = $this->getMShop()->construct("BANNED");
              $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
              $player->sendMessage($message);
            }
          } else {

            $message = $this->getMShop()->construct("ALREADY_BANNED");
            $message = str_replace("{NAME}", $name, $message);
            $sender->sendMessage($message);
          }
        } else {

          $message = $this->getMShop()->construct("CANT_BAN_SELF");
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

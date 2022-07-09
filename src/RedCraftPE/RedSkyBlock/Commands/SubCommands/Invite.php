<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\TextArgument;

class Invite extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new TextArgument("name", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["name"])) {

      $name = strtolower($args["name"]);
      if ($this->checkIsland($sender)) {

        $island = $this->plugin->islandManager->getIsland($sender);
        $members = $island->getMembers();
        $banned = $island->getBanned();
        $memberLimit = (int) $this->plugin->cfg->get("Member Limit");

        if ($name !== strtolower($island->getCreator())) {

          if ($memberLimit > count($members) || $sender->hasPermission("redskyblock.members")) {

            if (!in_array($name, $banned)) {

              if ($island->invite($name)) {

                $message = $this->getMShop()->construct("INVITED_PLAYER");
                $message = str_replace("{NAME}", $name, $message);
                $sender->sendMessage($message);

                $player = $this->plugin->getServer()->getPlayerExact($name);
                if ($player instanceof Player) {

                  $message = $this->getMShop()->construct("INVITED_TO_ISLAND");
                  $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
                  $player->sendMessage($message);
                }
              } else {

                $message = $this->getMShop()->construct("ALREADY_INVITED");
                $message = str_replace("{NAME}", $name, $message);
                $sender->sendMessage($message);
              }
            } else {

              $message = $this->getMShop()->construct("CANT_INVITE_BANNED");
              $message = str_replace("{NAME}", $name, $message);
              $sender->sendMessage($message);
            }
          } else {

            $message = $this->getMShop()->construct("MEMBER_LIMIT_REACHED");
            $sender->sendMessage($message);
          }
        } else {

          $message = $this->getMShop()->construct("CANT_INVITE_SELF");
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

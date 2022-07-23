<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Accept extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new TextArgument("island", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    $islandName = $args["island"];
    $island = $this->plugin->islandManager->getIslandByName($islandName);
    if ($island instanceof Island) {

      $members = $island->getMembers();
      $memberCount = count($members);
      $memberLimit = (int) $this->plugin->cfg->get("Member Limit");
      if ($memberLimit > $memberCount) {

        if ($island->acceptInvite($sender)) {

          $message = $this->getMShop()->construct("ACCEPTED_INVITE");
          $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
          $sender->sendMessage($message);

          $islandCreator = $this->plugin->getServer()->getPlayerExact($island->getCreator());
          if ($islandCreator instanceof Player) {

            $message = $this->getMShop()->construct("JOINED_ISLAND");
            $message = str_replace("{NAME}", $sender->getName(), $message);
            $islandCreator->sendMessage($message);
          }
        } else {

          $message = $this->getMShop()->construct("NOT_INVITED");
          $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
          $sender->sendMessage($message);
        }
      } else {

        $message = $this->getMShop()->construct("MEMBER_LIMIT_REACHED");
        $sender->sendMessage($message);
      }
    } else {

      $message = $this->getMShop()->construct("COULD_NOT_FIND_ISLAND");
      $message = str_replace("{ISLAND_NAME}", $islandName, $message);
      $sender->sendMessage($message);
    }
  }
}

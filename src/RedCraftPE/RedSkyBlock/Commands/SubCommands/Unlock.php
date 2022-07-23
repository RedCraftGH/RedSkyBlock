<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Unlock extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    $island = $this->plugin->islandManager->getIslandAtPlayer($sender);
    if ($island instanceof Island) {

      $members = $island->getMembers();
      if (array_key_exists(strtolower($sender->getName()), $members) || $sender->getName() === $island->getCreator() || $sender->hasPermission("redskyblock.admin")) {

        if (array_key_exists(strtolower($sender->getName()), $members)) {

          $islandPermissions = $island->getPermissions();
          $senderRank = $members[strtolower($sender->getName())];

          if (in_array("island.lock", $islandPermissions[$senderRank])) {

            if ($island->unlock()) {

              $message = $this->getMShop()->construct("UNLOCKED");
              $sender->sendMessage($message);
            } else {

              $message = $this->getMShop()->construct("ALREADY_UNLOCKED");
              $sender->sendMessage($message);
            }
          } else {

            $message = $this->getMShop()->construct("RANK_TOO_LOW");
            $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
            $sender->sendMessage($message);
          }
        } else {

          if ($island->unlock()) {

            $message = $this->getMShop()->construct("UNLOCKED");
            $sender->sendMessage($message);
          } else {

            $message = $this->getMShop()->construct("ALREADY_UNLOCKED");
            $sender->sendMessage($message);
          }
        }
      } else {

        $message = $this->getMShop()->construct("NOT_A_MEMBER_SELF");
        $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
        $sender->sendMessage($message);
      }
    } elseif ($this->checkIsland($sender)) {

      $island = $this->plugin->islandManager->getIsland($sender);

      if ($island->unlock()) {

        $message = $this->getMShop()->construct("UNLOCKED");
        $sender->sendMessage($message);
      } else {

        $message = $this->getMShop()->construct("ALREADY_UNLOCKED");
        $sender->sendMessage($message);
      }
    } else {

      $message = $this->getMShop()->construct("NO_ISLAND");
      $sender->sendMessage($message);
    }
  }
}

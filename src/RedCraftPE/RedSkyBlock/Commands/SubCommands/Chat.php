<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Chat extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new TextArgument("island", true));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["island"])) {

      $islandName = $args["island"];
      $island = $this->plugin->islandManager->getIslandByName($islandName);
      if ($island instanceof Island) {

        if (array_key_exists(strtolower($sender->getName()), $island->getMembers()) || $sender->getName() === $island->getCreator() || $sender->hasPermission("redskyblock.admin")) {

          $islandChatters = $island->getChatters();
          if (in_array(strtolower($sender->getName()), $islandChatters)) {

            //if already in island chat be removed from it
            $island->removeChatter($sender->getName());
            $this->plugin->islandManager->addToMainChat($sender);

            $message = $this->getMShop()->construct("LEAVE_ISLAND_CHAT");
            $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
            $sender->sendMessage($message);

          } else {

            //if not already in chat then check if in any other island chats. if yes be removed from them.
            $currentChannel = $this->plugin->islandManager->searchIslandChannels($sender);
            if ($currentChannel instanceof Island) {

              $currentChannel->removeChatter($sender->getName());
              $this->plugin->islandManager->addToMainChat($sender);

              $message = $this->getMShop()->construct("LEAVE_ISLAND_CHAT");
              $message = str_replace("{ISLAND_NAME}", $currentChannel->getName(), $message);
              $sender->sendMessage($message);
            }

            $island->addChatter($sender->getName());
            $this->plugin->islandManager->removeFromMainChat($sender);

            $message = $this->getMShop()->construct("JOIN_ISLAND_CHAT");
            $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
            $sender->sendMessage($message);
          }
        } else {

          $message = $this->getMShop()->construct("NOT_A_MEMBER_SELF");
          $message = str_replace("{ISLAND_NAME}", $island->getName());
          $sender->sendMessage($message);
        }
      } else {

        $message = $this->getMShop()->construct("COULD_NOT_FIND_ISLAND");
        $message = str_replace("{ISLAND_NAME}", $islandName, $message);
        $sender->sendMessage($message);
      }
    } else {

      $currentChannel = $this->plugin->islandManager->searchIslandChannels($sender->getName());

      if ($currentChannel instanceof Island) {

        $currentChannel->removeChatter($sender->getName());
        $this->plugin->islandManager->addToMainChat($sender);

        $message = $this->getMShop()->construct("LEAVE_ISLAND_CHAT");
        $message = str_replace("{ISLAND_NAME}", $currentChannel->getName(), $message);
        $sender->sendMessage($message);
      } else {

        if ($this->checkIsland($sender)) {

          $island = $this->plugin->islandManager->getIsland($sender);
          $island->addChatter($sender->getName());
          $this->plugin->islandManager->removeFromMainChat($sender);

          $message = $this->getMShop()->construct("JOIN_ISLAND_CHAT");
          $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
          $sender->sendMessage($message);
        } else {

          $message = $this->getMShop()->construct("NO_ISLAND");
          $sender->sendMessage($message);
        }
      }
    }
  }
}

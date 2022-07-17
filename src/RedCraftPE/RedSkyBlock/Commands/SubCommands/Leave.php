<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Leave extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new TextArgument("island", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["island"])) {

      $islandName = $args["island"];
      $island = $this->plugin->islandManager->getIslandByName($islandName);
      if ($island instanceof Island) {

        if ($island->removeMember($sender->getName())) {

          $message = $this->getMShop()->construct("REMOVED_FROM_ISLAND");
          $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
          $sender->sendMessage($message);
        } else {

          $message = $this->getMShop()->construct("NOT_A_MEMBER_SELF");
          $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
          $sender->sendMessage($message);
        }
      } else {

        $message = $this->getMShop()->construct("COULD_NOT_FIND_ISLAND");
        $message = str_replace("{ISLAND_NAME}", $islandName, $message);
        $sender->sendMessage($message);
      }
    } else {

      $this->sendUsage();
      return;
    }
  }
}

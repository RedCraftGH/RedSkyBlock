<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Value extends SBSubCommand {

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

        $value = $island->getValue();

        $message = $this->getMShop()->construct("ISLAND_VALUE_OTHER");
        $message = str_replace("{VALUE}", $value, $message);
        $message = str_replace("{NAME}", $islandName, $message);
        $sender->sendMessage($message);
      } else {

        $message = $this->getMShop()->construct("COULD_NOT_FIND_ISLAND");
        $message = str_replace("{ISLAND_NAME}", $islandName, $message);
        $sender->sendMessage($message);
      }
    } else {

      if ($this->checkIsland($sender)) {

        $island = $this->plugin->islandManager->getIsland($sender);
        $value = $island->getValue();

        $message = $this->getMShop()->construct("ISLAND_VALUE_SELF");
        $message = str_replace("{VALUE}", $value, $message);
        $sender->sendMessage($message);
      } else {

        $message = $this->getMShop()->construct("NO_ISLAND");
        $sender->sendMessage($message);
      }
    }
  }
}

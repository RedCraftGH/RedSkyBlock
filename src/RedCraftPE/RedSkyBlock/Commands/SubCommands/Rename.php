<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Rename extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new TextArgument("name", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["name"])) {

      $name = $args["name"];

      if ($this->checkIsland($sender)) {

        if (!$this->plugin->islandManager->checkRepeatIslandName($name)) {

          $island = $this->plugin->islandManager->getIsland($sender);
          $island->setName($name);

          $message = $this->getMShop()->construct("NAME_CHANGE");
          $message = str_replace("{NAME}", $name, $message);
          $sender->sendMessage($message);
        } else {

          $message = $this->getMShop()->construct("ISLAND_NAME_EXISTS");
          $message = str_replace("{ISLAND_NAME}", $name, $message);
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

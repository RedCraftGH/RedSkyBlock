<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Level extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    $island = $this->plugin->islandManager->getIslandAtPlayer($sender);
    if ($island instanceof Island) {

      $islandLevel = $island->calculateLevel($island->getXP());
      $xpNeeded = $island->getXPNeeded($island->getXP()) + $island->getXP();

      $message = $this->getMShop()->construct("ISLAND_LEVEL_OTHER");
      $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
      $message = str_replace("{LEVEL}", $islandLevel, $message);
      $message = str_replace("{XP}", $island->getXP(), $message);
      $message = str_replace("{XP_NEEDED}", $xpNeeded, $message);
      $sender->sendTip($message);
    } elseif ($this->checkIsland($sender)) {

        $island = $this->plugin->islandManager->getIsland($sender);
        $islandLevel = $island->calculateLevel($island->getXP());
        $xpNeeded = $island->getXPNeeded($island->getXP()) + $island->getXP();

        $message = $this->getMShop()->construct("ISLAND_LEVEL_SELF");
        $message = str_replace("{LEVEL}", $islandLevel, $message);
        $message = str_replace("{XP}", $island->getXP(), $message);
        $message = str_replace("{XP_NEEDED}", $xpNeeded, $message);
        $sender->sendTip($message);
    } else {

      $message = $this->getMShop()->construct("NO_ISLAND");
      $sender->sendMessage($message);
    }
  }
}

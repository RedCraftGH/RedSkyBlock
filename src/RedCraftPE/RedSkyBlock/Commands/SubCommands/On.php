<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class On {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onOnCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.onisland")) {

      $plugin = $this->plugin;

      if (array_key_exists(strtolower($sender->getName()), $plugin->skyblock->get("SkyBlock", []))) {

        $playerArray = $plugin->getPlayersAtIsland($sender);

        if (count($playerArray) <= 0) {

          $sender->sendMessage(TextFormat::WHITE . "There are no players on your island right now.");
          return true;
        } else {

          $sender->sendMessage(TextFormat::LIGHT_PURPLE . "Players At Your Island: " . TextFormat::WHITE . implode(", ", $playerArray));
          return true;
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "You have not created a SkyBlock island yet.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

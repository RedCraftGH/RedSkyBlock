<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Banned {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onBannedCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.ban")) {

      $plugin = $this->plugin;
      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
      $senderName = strtolower($sender->getName());

      if (array_key_exists($senderName, $skyblockArray)) {

        $filePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";
        $playerDataEncoded = file_get_contents($filePath);
        $playerData = (array) json_decode($playerDataEncoded);

        if (count($playerData["Banned"]) <= 0) {

          $banned = "no banned players.";
        } else {

          $banned = implode(", ", $playerData["Banned"]);
        }

        $sender->sendMessage(TextFormat::LIGHT_PURPLE . "Banned Players: " . TextFormat::WHITE . $banned);
        return true;
      } else {

        $sender->sendMessage(TextFormat::RED . "You don't have a SkyBlock island yet.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

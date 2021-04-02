<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Rank {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onRankCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.rank")) {

      $plugin = $this->plugin;
      $senderName = strtolower($sender->getName());
      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);

      if (array_key_exists($senderName, $skyblockArray)) {

        $valueArray = [];

        foreach($skyblockArray as $player => $data) {

          $filePath = $plugin->getDataFolder() . "Players/" . $player . ".json";
          $playerDataEncoded = file_get_contents($filePath);
          $playerData = (array) json_decode($playerDataEncoded);
          $valueArray[$player] = $playerData["Value"];
        }

        arsort($valueArray);
        $offset = array_search($senderName, array_keys($valueArray)) + 1;
        $sender->sendMessage(TextFormat::GREEN . "Your island is ranked " . TextFormat::WHITE . "#{$offset}");
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

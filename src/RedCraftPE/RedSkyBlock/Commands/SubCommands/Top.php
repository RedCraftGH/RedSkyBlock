<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Top {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onTopCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.top")) {

      $plugin = $this->plugin;
      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
      $senderName = strtolower($sender->getName());

      if (array_key_exists($senderName, $skyblockArray)) {

        $valueArray = [];

        foreach($skyblockArray as $player => $data) {

          $filePath = $plugin->getDataFolder() . "Players/" . $player . ".json";
          $playerDataEncoded = file_get_contents($filePath);
          $playerData = (array) json_decode($playerDataEncoded);
          $valueArray[$player] = $playerData["Value"];
        }

        arsort($valueArray);

        $counter = 0;
        $total1 = "N/A";
        $total2 = "N/A";
        $total3 = "N/A";
        $total4 = "N/A";
        $total5 = "N/A";
        foreach ($valueArray as $player => $value) {

          $counter++;
          if ($counter === 1) {

            $total1 = $player . " -- " . $value;
          } elseif ($counter === 2) {

            $total2 = $player . " -- " . $value;
          } elseif ($counter === 3) {

            $total3 = $player . " -- " . $value;
          } elseif ($counter === 4) {

            $total4 = $player . " -- " . $value;
          } elseif ($counter === 5) {

            $total5 = $player . " -- " . $value;
          }
        }

        $sender->sendMessage(TextFormat::LIGHT_PURPLE . "||Top Players||" . "\n" . TextFormat::WHITE . "#1: " . $total1 . "\n" . "#2: " . $total2 . "\n" . "#3: " . $total3 . "\n" . "#4: " . $total4 . "\n" . "#5: " . $total5);
        return true;
      } else {

        $sender->sendMessage(TextFormat::RED . "You don't have a skyblock island yet.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

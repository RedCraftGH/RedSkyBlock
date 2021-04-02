<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

class Leave {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onLeaveCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.leave")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is leave <player>");
        return true;
      } else {

        $plugin = $this->plugin;
        $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
        $pName = strtolower(implode(" ", array_slice($args, 1)));
        $senderName = $sender->getName();
        $filePath = $plugin->getDataFolder() . "Players/" . $pName . "json";

        if (array_key_exists($pName, $skyblockArray)) {

          $playerDataEncoded = file_get_contents($filePath);
          $playerData = json_decode($playerDataEncoded, true);

          if (in_array($senderName, $playerData["Island Members"])) {

            $key = array_search($senderName, $playerData["Island Members"]);
            unset($playerData["Island Members"][$key]);
            $playerDataEncoded = json_encode($playerData);
            file_put_contents($filePath);
            $sender->sendMessage(TextFormat::GREEN . "You have left " . $pName . "'s island.");
            return true;
          } else {

            $sender->sendMessage(TextFormat::RED . "You are not a member of " . $pName . "'s island.");
            return true;
          }
        } else {

          $sender->sendMessage(TextFormat::RED . $pName . " does not have an island.");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

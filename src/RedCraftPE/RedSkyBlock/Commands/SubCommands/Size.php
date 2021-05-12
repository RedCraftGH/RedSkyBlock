<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Size {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onSizeCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("redskyblock.size")) {

      if (count($args) < 3) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is size <size> <player>");
        return true;
      } else {

        $playerName = strtolower(implode(" ", array_slice($args, 2)));
        $size = $args[1];
        if (intval($size) === 0) {

          $sender->sendMessage(TextFormat::RED . "You cannot set " . TextFormat::WHITE . $playerName . "'s" . TextFormat::RED . " island size to " . $size);
          return true;
        }
        $plugin = $this->plugin;
        $skyblockArray = $plugin->skyblock->get("SkyBlock", []);

        if (array_key_exists($playerName, $skyblockArray)) {

          $filePath = $plugin->getDataFolder() . "Players/" . $playerName . ".json";
          $playerDataEncoded = file_get_contents($filePath);
          $playerData = (array) json_decode($playerDataEncoded);

          $playerData["Island Size"] = intval($size);
          $playerDataEncoded = json_encode($playerData);
          file_put_contents($filePath, $playerDataEncoded);
          $sender->sendMessage(TextFormat::WHITE . $playerName . "'s" . TextFormat::GREEN . " island size has been changed to " . TextFormat::LIGHT_PURPLE . $size);
          return true;
        } else {

          $sender->sendMessage(TextFormat::WHITE . $playerName . TextFormat::RED . " does not have an island.");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

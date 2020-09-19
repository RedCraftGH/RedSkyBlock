<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Remove {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onRemoveCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.members")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is remove <player>");
        return true;
      } else {

        $senderName = strtolower($sender->getName());
        $name = strtolower(implode(" ", array_slice($args, 1)));
        $plugin = $this->plugin;
        $filePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";
        if (file_exists($filePath)) {

          $playerDataEncoded = file_get_contents($filePath);
          $playerData = (array) json_decode($playerDataEncoded);

          if (in_array($name, $playerData["Island Members"])) {

            $key = array_search($name, $playerData["Island Members"]);
            unset($playerData["Island Members"][$key]);
            $playerDataEncoded = json_encode($playerData);
            file_put_contents($filePath, $playerDataEncoded);
            $sender->sendMessage(TextFormat::WHITE . $name . TextFormat::GREEN . " is no longer a member of your island.");
            return true;
          } else {

            $sender->sendMessage(TextFormat::WHITE . $name . TextFormat::RED . " is not a member of your island.");
            return true;
          }
        } else {

          $sender->sendMessage(TextFormat::RED . "You have to create a skyblock island to use this command.");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

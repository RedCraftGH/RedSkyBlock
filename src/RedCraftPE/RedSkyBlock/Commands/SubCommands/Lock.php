<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Lock {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onLockCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.lock")) {

      $plugin = $this->plugin;
      $senderName = strtolower($sender->getName());
      $playerFilePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";

      if (file_exists($plugin->getDataFolder() . "Players/" . strtolower($sender->getName()) . ".json")) {

        $jsonPlayerData = file_get_contents($playerFilePath);
        $playerData = (array) json_decode($jsonPlayerData);

        if (count($args) < 2) {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is lock <on/off>");
          return true;
        } else {

          if ($args[1] === "on") {

            if ($playerData["Island Locked"] === false) {

              $playerData["Island Locked"] = true;
              $playerDataEncoded = json_encode($playerData);
              file_put_contents($playerFilePath, $playerDataEncoded);
              $sender->sendMessage(TextFormat::GREEN . "Your skyblock island has been locked.");
              return true;
            } else {

              $sender->sendMessage(TextFormat::RED . "Your skyblock island is already locked.");
              return true;
            }

          } elseif ($args[1] === "off") {

            if ($playerData["Island Locked"] === true) {

              $playerData["Island Locked"] = false;
              $playerDataEncoded = json_encode($playerData);
              file_put_contents($playerFilePath, $playerDataEncoded);
              $sender->sendMessage(TextFormat::GREEN . "Your skyblock island is no longer locked.");
              return true;
            } else {

              $sender->sendMessage(TextFormat::RED . "Your skyblock island is not locked.");
              return true;
            }
          } else {

            $sender->sendMessage(TextFormat::WHITE . "Usage: /is lock <on/off>");
            return true;
          }
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "You have to create a skyblock island to use this command.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::WHITE . "Usage: /is lock <on/off>");
      return true;
    }
  }
}

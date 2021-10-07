<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Lock {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onLockCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("redskyblock.lock")) {

      $plugin = $this->plugin;
      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
      $senderName = strtolower($sender->getName());
      $playerFilePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";

      if (array_key_exists($senderName, $skyblockArray)) {

        $jsonPlayerData = file_get_contents($playerFilePath);
        $playerData = (array) json_decode($jsonPlayerData, true);

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

              $scoreHud = $plugin->getServer()->getPluginManager()->getPlugin("ScoreHud");
              if ($scoreHud !== null && $scoreHud->isEnabled()) {

                $ev = new \Ifera\ScoreHud\event\PlayerTagUpdateEvent(
                  $sender,
                  new \Ifera\ScoreHud\scoreboard\ScoreTag("redskyblock.islestatus", "Locked")
                );
                $ev->call();
              }
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

              $scoreHud = $plugin->getServer()->getPluginManager()->getPlugin("ScoreHud");
              if ($scoreHud !== null && $scoreHud->isEnabled()) {

                $ev = new \Ifera\ScoreHud\event\PlayerTagUpdateEvent(
                  $sender,
                  new \Ifera\ScoreHud\scoreboard\ScoreTag("redskyblock.islestatus", "Unlocked")
                );
                $ev->call();
              }
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

        $sender->sendMessage(TextFormat::RED . "You have not created a SkyBlock island yet.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

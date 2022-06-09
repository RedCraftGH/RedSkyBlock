<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Fly {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onFlyCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("redskyblock.fly")) {

      $plugin = $this->plugin;
      $island = $plugin->getIslandAtPlayer($sender);
      if ($island === null) {

        $sender->sendMessage(TextFormat::RED . "You must be on your island to use this command.");
        return true;
      }
      $masterWorld = $plugin->skyblock->get("Master World");
      $senderName = strtolower($sender->getName());
      $filePath = $plugin->getDataFolder() . "Players/" . $island . ".json";
      $playerDataEncoded = file_get_contents($filePath);
      $playerData = (array) json_decode($playerDataEncoded);

      if ($masterWorld === $sender->getWorld()->getFolderName() || $masterWorld === $sender->getWorld()->getFolderName() . "-Nether") {

        if ($island === $senderName || in_array($senderName, $playerData["Island Members"])) {

          if ($sender->getAllowFlight()) {

            $sender->setAllowFlight(false);
            $sender->setFlying(false);
            $sender->sendMessage(TextFormat::GREEN . "You have disabled flight.");
            return true;
          } else {

            $sender->setAllowFlight(true);
            $sender->sendMessage(TextFormat::GREEN . "You have enabled flight.");
            return true;
          }
        } else {

          $sender->sendMessage(TextFormat::RED . "You must be on your island to use this command.");
          return true;
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "You must be on your island to use this command.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class Delete {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onDeleteCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("redskyblock.delete")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is delete <player>");
        return true;
      } else {

        $playerName = strtolower(implode(" ", array_slice($args, 1)));
        $plugin = $this->plugin;
        $player = $plugin->getServer()->getPlayerExact($playerName);
        $skyblockArray = $plugin->skyblock->get("SkyBlock", []);

        if ($player instanceof Player) {

          $player->sendMessage(TextFormat::RED . "Your island has been deleted by a server administrator.");
          $player->teleport($plugin->getServer()->getDefaultLevel()->getSafeSpawn());
        }

        if (array_key_exists($playerName, $skyblockArray)) {

          $filePath = $plugin->getDataFolder() . "Players/" . $playerName . ".json";

          unset($skyblockArray[$playerName]);
          unlink($filePath);

          $plugin->skyblock->set("SkyBlock", $skyblockArray);
          $plugin->skyblock->save();
          $sender->sendMessage(TextFormat::GREEN . "You have successfully deleted " . TextFormat::WHITE . $playerName . "'s" . TextFormat::GREEN . " island.");
          return true;
        } else {

          $sender->sendMessage(TextFormat::WHITE . $playerName . TextFormat::RED . " does not have an island to delete.");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

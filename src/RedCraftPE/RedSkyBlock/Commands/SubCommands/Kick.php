<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;

class Kick {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onKickCommand(CommandSender $sender, array $args) {

    if ($sender->hasPermission("redskyblock.kick")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is kick <player(s)>");
        return true;
      } else {

        $plugin = $this->plugin;
        $senderName = strtolower($sender->getName());
        $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
        $playerArray = $plugin->getPlayersAtIsland($sender);

        if (array_key_exists($senderName, $skyblockArray)) {

          $kickArray = [];

          for ($i = 0; $i <= count($args) - 1; $i++) {

            if ($i !== 0) {

              array_push($kickArray, strtolower($args[$i]));
            }
          }

          foreach($kickArray as $pName) {

            if (in_array($pName, $playerArray)) {

              if ($pName !== strtolower($sender->getName())) {

                $player = $plugin->getServer()->getPlayerByPrefix($pName);

                $player->teleport($plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
                $player->sendMessage(TextFormat::RED . $sender->getName() . " has kicked you off of their island.");
                $sender->sendMessage(TextFormat::GREEN . $player->getName() . " has been kicked off of your island.");
              } else {

                $sender->sendMessage(TextFormat::RED . "You can't kick yourself off of your island.");
              }
            } else {

              $sender->sendMessage(TextFormat::RED . $pName . " is not on your island.");
            }
          }
          return true;
        } else {

          $sender->sendMessage(TextFormat::RED . "You have not created a SkyBlock island yet.");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

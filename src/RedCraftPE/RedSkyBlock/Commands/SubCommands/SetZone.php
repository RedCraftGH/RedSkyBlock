<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class SetZone {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onSetZoneCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("redskyblock.setzone")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is setzone <1/2>");
        return true;
      } else {

        $plugin = $this->plugin;
        $islandZone = $plugin->cfg->get("Island Zone", []);

        if ($args[1] === "1") {

          $x = round($sender->getX());
          $y = round($sender->getY());
          $z = round($sender->getZ());

          $islandZone[0] = $x;
          $islandZone[1] = $y;
          $islandZone[2] = $z;

          $plugin->cfg->set("Island Zone", $islandZone);
          $plugin->cfg->save();
          $sender->sendMessage(TextFormat::GREEN . "The first position of your island zone has been set.");
          return true;

        } elseif ($args[1] === "2") {

          $x = round($sender->getX());
          $y = round($sender->getY());
          $z = round($sender->getZ());

          $islandZone[3] = $x;
          $islandZone[4] = $y;
          $islandZone[5] = $z;

          $plugin->cfg->set("Island Zone", $islandZone);
          $plugin->cfg->save();
          $sender->sendMessage(TextFormat::GREEN . "The second position of your island zone has been set.");
          return true;

        } else {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is setzone <1/2>");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

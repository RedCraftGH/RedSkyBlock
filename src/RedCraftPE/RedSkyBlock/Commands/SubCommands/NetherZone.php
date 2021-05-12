<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class NetherZone {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onNetherZoneCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("redskyblock.setzone")) {

      $plugin = $this->plugin;

      if ($plugin->cfg->get("Nether Islands")) {

        if (count($args) < 2) {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is netherzone <1/2>");
          return true;
        } else {

          $netherZone = $plugin->cfg->get("Nether Zone", []);

          if ($args[1] === "1") {

            $x = round($sender->getX());
            $y = round($sender->getY());
            $z = round($sender->getZ());

            $netherZone[0] = $x;
            $netherZone[1] = $y;
            $netherZone[2] = $z;

            $plugin->cfg->set("Nether Zone", $netherZone);
            $plugin->cfg->save();
            $sender->sendMessage(TextFormat::GREEN . "The first position of your nether island zone has been set.");
            return true;

          } elseif ($args[1] === "2") {

            $x = round($sender->getX());
            $y = round($sender->getY());
            $z = round($sender->getZ());

            $netherZone[3] = $x;
            $netherZone[4] = $y;
            $netherZone[5] = $z;

            $plugin->cfg->set("Nether Zone", $netherZone);
            $plugin->cfg->save();
            $sender->sendMessage(TextFormat::GREEN . "The second position of your nether island zone has been set.");
            return true;

          } else {

            $sender->sendMessage(TextFormat::WHITE . "Usage: /is netherzone <1/2>");
            return true;
          }
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "Nether Skyblock has been disabled.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

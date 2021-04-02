<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class UpdateZone {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onUpdateZoneCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.updatezone")) {

      $plugin = $this->plugin;
      $plugin->getServer()->loadLevel($plugin->cfg->get("Zone World"));
      $zoneWorldName = $plugin->cfg->get("Zone World");
      $zoneWorld = $plugin->getServer()->getLevelByName($zoneWorldName);

      if (count($args) < 2) {

        $islandZone = $plugin->cfg->get("Island Zone", []);
        $islandBlocks = [];
        $x1 = (int) $islandZone[0];
        $y1 = (int) $islandZone[1];
        $z1 = (int) $islandZone[2];
        $x2 = (int) $islandZone[3];
        $y2 = (int) $islandZone[4];
        $z2 = (int) $islandZone[5];

        for ($x = min($x1, $x2); $x <= max($x1, $x2); $x++) {

          for ($y = min($y1, $y2); $y <= max($y1, $y2); $y++) {

            for ($z = min($z1, $z2); $z <= max($z1, $z2); $z++) {

              $block = $zoneWorld->getBlockAt((int) $x, (int) $y, (int) $z, true, false);
              $blockID = $block->getID();
              $blockDamage = $block->getDamage();

              array_push($islandBlocks, $blockID . " " . $blockDamage);
            }
          }
        }
        $plugin->cfg->set("Island Spawn Y", 80 + (max($y1, $y2) - min($y1, $y2)) + 1); // calculates how high the player spawns when creating an island based on island size
        $plugin->skyblock->set("Island Blocks", $islandBlocks);
        $plugin->skyblock->set("Zone Created", true);
      } elseif (strtolower($args[1]) === "nether") {

        $netherZone = $plugin->cfg->get("Nether Zone", []);
        $netherBlocks = [];
        $x1 = (int) $netherZone[0];
        $y1 = (int) $netherZone[1];
        $z1 = (int) $netherZone[2];
        $x2 = (int) $netherZone[3];
        $y2 = (int) $netherZone[4];
        $z2 = (int) $netherZone[5];

        for ($x = min($x1, $x2); $x <= max($x1, $x2); $x++) {

          for ($y = min($y1, $y2); $y <= max($y1, $y2); $y++) {

            for ($z = min($z1, $z2); $z <= max($z1, $z2); $z++) {

              $block = $zoneWorld->getBlockAt((int) $x, (int) $y, (int) $z, true, false);
              $blockID = $block->getID();
              $blockDamage = $block->getDamage();

              array_push($netherBlocks, $blockID . " " . $blockDamage);
            }
          }
        }
        $plugin->skyblock->set("Nether Blocks", $netherBlocks);
        $plugin->skyblock->set("Nether Zone Created", true);
      } else {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is updatezone [nether]");
        return true;
      }
      $plugin->cfg->save();
      $plugin->skyblock->save();
      $sender->sendMessage(TextFormat::GREEN . "The Island Zone has been updated.");
      return true;
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

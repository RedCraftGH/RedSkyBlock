<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\Player;

class UpdateZone {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onUpdateZoneCommand(Player $sender): bool {

    if ($sender->hasPermission("skyblock.reloadzone")) {

      $plugin = $this->plugin;

      $plugin->getServer()->loadLevel($plugin->cfg->get("Zone World"));

      $islandWorld = $plugin->cfg->get("Zone World");
      $world = $plugin->getServer()->getLevelByName($islandWorld);
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

            $block = $world->getBlockAt((int) $x, (int) $y, (int) $z, true, false);
            $blockID = $block->getID();
            $blockDamage = $block->getDamage();

            array_push($islandBlocks, $blockID . " " . $blockDamage);
          }
        }
      }
      $plugin->skyblock->set("Island Blocks", $islandBlocks);
      $plugin->skyblock->set("Zone Created", true);
      $plugin->skyblock->save();
      $sender->sendMessage(TextFormat::GREEN . "The Island Zone has been updated.");
      return true;
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

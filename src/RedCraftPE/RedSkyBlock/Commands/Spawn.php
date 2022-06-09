<?php

namespace RedCraftPE\RedSkyBlock\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use pocketmine\math\Vector3;

class Spawn {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onSpawnCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

    $plugin = $this->plugin;
    $check = $plugin->cfg->get("Spawn Command");

    if ($plugin->cfg->get("Spawn Command") === "on") {

      $spawn = $plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn();

      if ($sender->getGamemode() === 0) {

        $sender->setAllowFlight(false);
      }
      $sender->teleport($spawn);
      $position = new Vector3(12, -7, 14);
      return true;
    } else {

      return true;
    }
  }
}

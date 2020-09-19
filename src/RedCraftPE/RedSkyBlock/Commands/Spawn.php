<?php

namespace RedCraftPE\RedSkyBlock\Commands;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;

class Spawn {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onSpawnCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

    $plugin = $this->plugin;
    $world = $plugin->getServer()->getLevelByName($plugin->cfg->get("Spawn World"));

    $sender->teleport(new Position(208, 14, 261, $world));
    return true;
  }
}

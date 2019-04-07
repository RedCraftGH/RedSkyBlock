<?php

namespace RedCraftPE\RedSkyBlock\Generators;

use pocketmine\Server;
use pocketmine\level\generator\Flat;

use RedCraftPE\RedSkyBlock\Commands\SubCommands\CreateWorld;

class WorldGenerator {

  private static $instance;

  public function __construct($plugin) {

    $this->plugin = $plugin;
    self::$instance = $this;
  }
  public function generateWorld($levelName) {

    $plugin = $this->plugin;
    $plugin->getServer()->generateLevel($levelName, null, 'pocketmine\level\generator\Flat', ["preset" => "3;minecraft:air;127;"]);
  }
}

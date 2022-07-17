<?php

namespace RedCraftPE\RedSkyBlock\Tasks;

use pocketmine\scheduler\Task;

use RedCraftPE\RedSkyBlock\SkyBlock;

class AutoSaveIslands extends Task {

  private $plugin;

  public function __construct(SkyBlock $plugin) {

    $this->plugin = $plugin;
  }

  public function onRun(): void {

    $this->plugin->islandManager->saveAllIslands();
  }
}

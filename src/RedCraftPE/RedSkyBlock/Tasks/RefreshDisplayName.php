<?php

namespace RedCraftPE\RedSkyBlock\Tasks;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;

class RefreshDisplayName extends Task {

  private $player;
  private $displayName;

  public function __construct(Player $player, string $displayName) {

    $this->player = $player;
    $this->displayName = $displayName;
  }

  public function onRun(): void {

    $this->player->setDisplayName($this->displayName);
  }
}

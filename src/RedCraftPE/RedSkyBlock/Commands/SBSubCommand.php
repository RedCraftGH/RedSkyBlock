<?php

namespace RedCraftPE\RedSkyBlock\Commands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\world\World;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Utils\MessageConstructor;
use RedCraftPE\RedSkyBlock\Utils\ZoneManager;

use CortexPE\Commando\BaseSubCommand;

abstract class SBSubCommand extends BaseSubCommand {

  protected $plugin;
  private $sender;

  public function __construct(SkyBlock $plugin, string $name, string $description = "", array $aliases = []) {

    $this->plugin = $plugin;
    parent::__construct($name, $description, $aliases);
  }

  //include get SB functions here + any other useful functions to be used across multiple commands

  public function getMShop(): MessageConstructor {

    return MessageConstructor::getInstance();
  }

  public function checkZone(): bool {

    if (ZoneManager::getZone() !== []) {

      return true;
    } else {

      return false;
    }
  }

  public function checkMasterWorld(): bool {

    if ($this->plugin->skyblock->get("Master World") !== false) {

      return true;
    } else {

      return false;
    }
  }

  public function checkIsland(Player $player): bool {

    $skyblockArray = scandir($this->plugin->getDataFolder() . "../RedSkyBlock/Players");
    $playerName = $player->getName();

    if (in_array($playerName . ".json", $skyblockArray)) {

      return true;
    } else {

      return false;
    }
  }
}

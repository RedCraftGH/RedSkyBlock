<?php

namespace RedCraftPE\RedSkyBlock\Tasks;

use pocketmine\scheduler\Task;
use pocketmine\world\World;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\item\StringToItemParser;
use pocketmine\block\VanillaBlocks;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Utils\ZoneManager;

class IslandGenerator extends Task {

  private $plugin;
  private $sender;
  private $lastX;
  private $lastZ;
  private $world;

  public function __construct(SkyBlock $plugin, Player $sender, int $lastX, int $lastZ, World $world) {

    $this->plugin = $plugin;
    $this->sender = $sender;
    $this->lastX = $lastX;
    $this->lastZ = $lastZ;
    $this->world = $world;
  }

  public function onRun(): void {

    $plugin = $this->plugin;
    $sender = $this->sender;
    $lastX = $this->lastX;
    $lastZ = $this->lastZ;
    $world = $this->world;
    $islandSpawnY = $plugin->cfg->get("Island Spawn Y");
    $startingItems = $plugin->cfg->get("Starting Items", []);

    $zone = ZoneManager::getZone();
    $zoneStartPosition = ZoneManager::getZoneStartPosition();
    $zoneSize = ZoneManager::getZoneSize();
    $counter = 0;

    for ($x = $lastX; $x <= $lastX + $zoneSize[0]; $x++) {

      for ($y = $islandSpawnY; $y <= $islandSpawnY + $zoneSize[1]; $y++) {

        for ($z = $lastZ; $z <= $lastZ + $zoneSize[2]; $z++) {

          $blockName = $zone[$counter];
          $block = StringToItemParser::getInstance()->parse($blockName)->getBlock();
          $world->setBlock(new Vector3($x, $y, $z), $block, false);
          $counter++;
        }
      }
    }

    $world->setBlock(new Vector3($sender->getPosition()->x, $sender->getPosition()->y - 1, $sender->getPosition()->z + 1), VanillaBlocks::CHEST());
    $startingChest = $world->getTileAt($sender->getPosition()->x, $sender->getPosition()->y - 1, $sender->getPosition()->z + 1);
    foreach($startingItems as $itemName => $count) {

      if (count($startingItems) !== 0) {

        $item = StringToItemParser::getInstance()->parse($itemName);
        $item->setCount(intval($count));
        $startingChest->getInventory()->addItem($item);
      }
    }

    $sender->setImmobile(false);
  }
}

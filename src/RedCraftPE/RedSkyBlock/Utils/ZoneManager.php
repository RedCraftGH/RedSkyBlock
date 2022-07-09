<?php

namespace RedCraftPE\RedSkyBlock\Utils;

use pocketmine\world\Position;
use pocketmine\player\Player;
use pocketmine\world\World;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\block\Block;

use RedCraftPE\RedSkyBlock\SkyBlock;

class ZoneManager {

  private static $plugin;

  private static $zone;

  private static $zoneShovel;
  private static $spawnFeather;

  private static $zoneKeeper = null;
  private static $zoneWorld;

  private static $pos1 = null;
  private static $pos2 = null;
  private static $spawnPosition = null;
  private static $zoneSpawn;

  private static $zoneSize;
  private static $zoneStartPosition;

  public function __construct(SkyBlock $plugin) {

    self::$plugin = $plugin;
    self::$zone = $plugin->skyblock->get("Zone", []);

    self::$zoneStartPosition = $plugin->skyblock->get("Zone Position", []);
    self::$zoneSize = $plugin->skyblock->get("Zone Size", []);
    self::$zoneSpawn = $plugin->skyblock->get("Zone Spawn", []);

    $zoneWorld = $plugin->skyblock->get("Zone World");
    if ($zoneWorld === false) {

      self::$zoneWorld = null;
    } else {

      if ($plugin->getServer()->getWorldManager()->loadWorld($zoneWorld)) {

        self::$zoneWorld = $plugin->getServer()->getWorldManager()->getWorldByName($zoneWorld);
      } else {

        self::$zoneWorld = false;
        $plugin->skyblock->set("Zone World", self::$zoneWorld);
      }
    }

    self::$zoneShovel = VanillaItems::WOODEN_SHOVEL();
    self::$zoneShovel->getNamedTag()->setByte("redskyblock", 1);
    self::$zoneShovel->setCustomName(TextFormat::OBFUSCATED . "s" . TextFormat::RESET . TextFormat::RED . " Zone Shovel " . TextFormat::RESET . TextFormat::OBFUSCATED . TextFormat::WHITE . "s");

    self::$spawnFeather = VanillaItems::FEATHER();
    self::$spawnFeather->getNamedTag()->setByte("redskyblock", 1);
    self::$spawnFeather->setCustomName(TextFormat::OBFUSCATED . "s" . TextFormat::RESET . TextFormat::WHITE . " Spawn Feather " . TextFormat::RESET . TextFormat::OBFUSCATED . TextFormat::WHITE . "s");
  }

  public static function createZone(): void {

    $zone = self::$zone;

    $pos1 = self::$pos1;
    $pos2 = self::$pos2;
    $zoneWorld = self::$zoneWorld;
    $spawnPosition = self::$spawnPosition;
    $plugin = self::$plugin;
    $cSpawnVals = $plugin->skyblock->get("CSpawnVals", []);

    $zoneX = [$pos1->x, $pos2->x];
    $zoneY = [$pos1->y, $pos2->y];
    $zoneZ = [$pos1->z, $pos2->z];

    self::$zoneStartPosition = [min($zoneX), min($zoneY), min($zoneZ)];
    self::$zoneSize = [max($zoneX) - min($zoneX), max($zoneY) - min($zoneY), max($zoneZ) - min($zoneZ)];

    //calculate spawn finding values relative to the position of the selected spawn within the island zone
    $islandHeight = self::$zoneSize[1];

    $cSpawnVals[0] = $spawnPosition->x - self::$zoneStartPosition[0];
    $cSpawnVals[1] = $spawnPosition->y - self::$zoneStartPosition[1] + 2; // + 2 to account for player height
    $cSpawnVals[2] = $spawnPosition->z - self::$zoneStartPosition[2];
    $plugin->skyblock->set("CSpawnVals", $cSpawnVals);

    self::updateZone();
  }

  public static function updateZone(): void {

    self::clearZone();
    $zone = self::$zone;
    $zoneWorld = self::$zoneWorld;
    $zoneStartPosition = self::$zoneStartPosition;
    $zoneSize = self::$zoneSize;

    for ($x = $zoneStartPosition[0]; $x <= $zoneStartPosition[0] + $zoneSize[0]; $x++) {

      for ($y = $zoneStartPosition[1]; $y <= $zoneStartPosition[1] + $zoneSize[1]; $y++) {

        for ($z = $zoneStartPosition[2]; $z <= $zoneStartPosition[2] + $zoneSize[2]; $z++) {

          $block = $zoneWorld->getBlockAt((int) $x, (int) $y, (int) $z, true, false);
          $blockID = $block->getID();
          $blockMeta = $block->getMeta();

          array_push($zone, $blockID . ":" . $blockMeta);
        }
      }
    }
    self::$zone = $zone;
    self::saveZone();
  }

  public static function getZone(): array {

    return self::$zone;
  }

  public static function saveZone(): void {

    $plugin = self::$plugin;
    $zone = self::$zone;
    if (self::$zoneSpawn === []) {

      $spawnPosition = self::$spawnPosition;
      self::$zoneSpawn = [round($spawnPosition->x), round($spawnPosition->y), round($spawnPosition->z)];
    }
    $zoneWorld = self::$zoneWorld;
    $zoneStartPosition = self::$zoneStartPosition;
    $zoneSize = self::$zoneSize;
    $plugin->skyblock->set("Zone", $zone);
    $plugin->skyblock->set("Zone Spawn", self::$zoneSpawn);
    $plugin->skyblock->set("Zone World", $zoneWorld->getFolderName());
    $plugin->skyblock->set("Zone Size", $zoneSize);
    $plugin->skyblock->set("Zone Position", $zoneStartPosition);
    $plugin->skyblock->save();
  }

  public static function clearZone(): void {

    self::$zone = [];
  }

  public static function clearZoneTools(Player $player): void {

    $playerInv = $player->getInventory();
    $invContents = $playerInv->getContents();

    if ($playerInv->contains(self::$zoneShovel)) {

      $index = array_search(self::$zoneShovel, $invContents);
      $playerInv->setItem($index, VanillaItems::AIR());
    }
    if ($playerInv->contains(self::$spawnFeather)) {

      $index = array_search(self::$spawnFeather, $invContents);
      $playerInv->setItem($index, VanillaItems::AIR());
    }
    if ($playerInv->getItemInHand()->equals(self::$zoneShovel) || $playerInv->getItemInHand()->equals(self::$spawnFeather)) {

      $playerInv->setItemInHand(VanillaItems::AIR());
    }
    return;
  }

  public static function getZoneKeeper(): ?Player {

    return self::$zoneKeeper;
  }

  public static function setZoneKeeper($zoneKeeper = null): void {

    self::$zoneKeeper = $zoneKeeper;
  }

  public static function getZoneWorld(): ?World {

    return self::$zoneWorld;
  }

  public static function setZoneWorld($world = null): void {

    self::$zoneWorld = $world;
  }

  public static function getFirstPosition(): ?Position {

    return self::$pos1;
  }

  public static function getSecondPosition(): ?Position {

    return self::$pos2;
  }

  public static function setFirstPosition($position = null): void {

    self::$pos1 = $position;
  }

  public static function setSecondPosition($position = null): void {

    self::$pos2 = $position;
  }

  public static function getZoneShovel(): Item {

    return self::$zoneShovel;
  }

  public static function getSpawnFeather(): Item {

    return self::$spawnFeather;
  }

  public static function getSpawnPosition(): ?Position {

    return self::$spawnPosition;
  }

  public static function setSpawnPosition($position = null): void {

    self::$spawnPosition = $position;
  }

  public static function getZoneStartPosition(): array {

    return self::$zoneStartPosition;
  }

  public static function getZoneSize(): array {

    return self::$zoneSize;
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Generators;

use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\level\generator\object\Tree;

use RedCraftPE\RedSkyBlock\Commands\SubCommands\Create;

class IslandGenerator {

  public static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function generateIsland($level, $interval, $islands) {

    for ($x = $islands * $interval; $x < ($islands * $interval) + 3; $x++) {

      for ($y = 15; $y < 18; $y++) {

        for ($z = $islands * $interval; $z < ($islands * $interval) + 6; $z++) {

          if ($y < 17) {

            $level->setBlock(new Vector3($x, $y, $z), Block::get(1));
          } else {

            $level->setBlock(new Vector3($x, $y, $z), Block::get(2));
          }
          if ($x === ($islands * $interval) + 1 && $z === $islands * $interval && $y === 17) {

            Tree::growTree($level, $x, $y + 1, $z, new Random(), 0);
          }
        }
      }
    }
    for ($x = ($islands * $interval) - 2; $x < $islands * $interval; $x++) {

      for ($y = 15; $y < 18; $y++) {

        for ($z = ($islands * $interval) + 3; $z < ($islands * $interval) + 6; $z++) {

          if ($y < 17) {

            $level->setBlock(new Vector3($x, $y, $z), Block::get(1));
          } else {

            $level->setBlock(new Vector3($x, $y, $z), Block::get(2));
          }
        }
      }
    }
  }
}

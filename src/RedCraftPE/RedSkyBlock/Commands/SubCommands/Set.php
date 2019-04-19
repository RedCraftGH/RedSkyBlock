<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\block\BlockFactory;
use pocketmine\block\Block;

use RedCraftPE\RedSkyBlock\SkyBlock;

class Set {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function onSetCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.set")) {

      $x1 = SkyBlock::getInstance()->skyblock->get("x1");
      $x2 = SkyBlock::getInstance()->skyblock->get("x2");
      $y1 = SkyBlock::getInstance()->skyblock->get("y1");
      $y2 = SkyBlock::getInstance()->skyblock->get("y2");
      $z1 = SkyBlock::getInstance()->skyblock->get("z1");
      $z2 = SkyBlock::getInstance()->skyblock->get("z2");
      $level = $sender->getLevel();
      $blocksArray = [];

      if (SkyBlock::getInstance()->skyblock->get("Pos1") && SkyBlock::getInstance()->skyblock->get("Pos2")) {

        for ($x = min($x1, $x2); $x <= max($x1, $x2); $x++) {

          for ($y = min($y1, $y2); $y <= max($y1, $y2); $y++) {

            for ($z = min($z1, $z2); $z <= max($z1, $z2); $z++) {

              $block = $level->getBlockAt($x, $y, $z, true, false);
              $blockID = $block->getID();
              $blockDamage = $block->getDamage();

              if ($blockID === BlockFactory::get(Block::LEAVES)->getID() || $blockID === BlockFactory::get(Block::LEAVES2)->getID()) {

                $oakNoDecay = [0, 4, 12];
                $spruceNoDecay = [1, 5, 13];
                $birchNoDecay = [2, 6, 14];
                $jungleNoDecay = [3, 7, 15];
                $acaciaNoDecay = [0, 4, 12];
                $darkNoDecay = [1, 5, 13];

                if (in_array($blockDamage, $oakNoDecay) && $blockID === BlockFactory::get(Block::LEAVES)->getID()) $blockDamage = 8;
                if (in_array($blockDamage, $spruceNoDecay) && $blockID === BlockFactory::get(Block::LEAVES)->getID()) $blockDamage = 9;
                if (in_array($blockDamage, $birchNoDecay) && $blockID === BlockFactory::get(Block::LEAVES)->getID()) $blockDamage = 10;
                if (in_array($blockDamage, $jungleNoDecay) && $blockID === BlockFactory::get(Block::LEAVES)->getID()) $blockDamage = 11;
                if (in_array($blockDamage, $acaciaNoDecay) && $blockID === BlockFactory::get(Block::LEAVES2)->getID()) $blockDamage = 8;
                if (in_array($blockDamage, $darkNoDecay) && $blockID === BlockFactory::get(Block::LEAVES2)->getID()) $blockDamage = 9;
              }

              array_push($blocksArray, $blockID . " " . $blockDamage);
            }
          }
        }
        SkyBlock::getInstance()->skyblock->set("Blocks", $blocksArray);
        SkyBlock::getInstance()->skyblock->set("Custom", true);
        SkyBlock::getInstance()->skyblock->save();
        $sender->sendMessage(TextFormat::GREEN . "Your new SkyBlock custom island has been set!");
        return true;
      } else {

        $sender->sendMessage(TextFormat::RED . "You must set the custom island position 1 and position 2 before using this command!");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\Commands\Island;
use RedCraftPE\RedSkyBlock\SkyBlock;

class Top {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onTopCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.top")) {

      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $first = "N/A";
      $firstValue = 0;
      $second = "N/A";
      $secondValue = 0;
      $third = "N/A";
      $thirdValue = 0;
      $fourth = "N/A";
      $fourthValue = 0;
      $fifth = "N/A";
      $fifthValue = 0;

      foreach(array_keys($skyblockArray) as $user) {

        $value = $skyblockArray[$user]["Value"];
        if ($value > $firstValue || ($value >= $firstValue && $first === "N/A")) {

          $first = $user;
          $firstValue = $value;
        } else if ($value > $secondValue || ($value >= $secondValue && $second === "N/A")) {

          $second = $user;
          $secondValue = $value;
        } else if ($value > $thirdValue || ($value >= $thirdValue && $third === "N/A")) {

          $third = $user;
          $thirdValue = $value;
        } else if ($value > $fourthValue || ($value >= $fourthValue && $fourth === "N/A")) {

          $fourth = $user;
          $fourthValue = $value;
        } else if ($value > $fifthValue || ($value >= $fifthValue && $fifth === "N/A")) {

          $fifth = $user;
          $fifthValue = $value;
        }
      }

      $sender->sendMessage(TextFormat::GREEN . "The Top 5 Islands on this Server: \n" . TextFormat::WHITE . "#1: " . TextFormat::GRAY . "{$first} " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$firstValue} \n" . TextFormat::WHITE . "#2: " . TextFormat::GRAY . "{$second}  " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$secondValue} \n" . TextFormat::WHITE . "#3: " . TextFormat::GRAY . "{$third} " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$thirdValue} \n" . TextFormat::WHITE . "#4: " . TextFormat::GRAY . "{$fourth}  " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$fourthValue} \n" . TextFormat::WHITE . "#5: " . TextFormat::GRAY . "{$fifth} " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$fifthValue}");
      return true;
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

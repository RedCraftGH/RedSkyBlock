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
      $values = [];
      $copyOfArray = $skyblockArray;

      foreach(array_keys($skyblockArray) as $user) {

        $value = $skyblockArray[$user]["Value"];
        $values[] = $value;
        rsort($values);
      }

      $counter = count($skyblockArray);
      if ($counter > 5) {

        $counter = 5;
      }

      for ($i = 1; $i <= count($skyblockArray); $i++) {

        $value = $values[$i - 1];

        $NameIndex = array_search($value, array_column($copyOfArray, "Value"));
        $keys = array_keys($copyOfArray);
        $NameValue = $copyOfArray[$keys[$NameIndex]];
        $Name = array_search($NameValue, $copyOfArray);

        if ($i === 1) {$first = $NameValue["Members"][0]; $firstValue = $value;}
        if ($i === 2) {$second = $NameValue["Members"][0]; $secondValue = $value;}
        if ($i === 3) {$third = $NameValue["Members"][0]; $thirdValue = $value;}
        if ($i === 4) {$fourth = $NameValue["Members"][0]; $fourthValue = $value;}
        if ($i === 5) {$fifth = $NameValue["Members"][0]; $fifthValue = $value;}

        unset($copyOfArray[$Name]);
      }

      $sender->sendMessage(TextFormat::GREEN . "The Top 5 Islands on this Server: \n" . TextFormat::WHITE . "#1: " . TextFormat::GRAY . "{$first} " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$firstValue} \n" . TextFormat::WHITE . "#2: " . TextFormat::GRAY . "{$second}  " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$secondValue} \n" . TextFormat::WHITE . "#3: " . TextFormat::GRAY . "{$third} " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$thirdValue} \n" . TextFormat::WHITE . "#4: " . TextFormat::GRAY . "{$fourth}  " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$fourthValue} \n" . TextFormat::WHITE . "#5: " . TextFormat::GRAY . "{$fifth} " . TextFormat::WHITE . "Worth: " . TextFormat::GRAY . "{$fifthValue}");
      return true;
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

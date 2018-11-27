<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Info {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onInfoCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.info")) {

      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock");

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is info <player>");
        return true;
      } else {

        $name = strtolower(implode(" ", array_slice($args, 1)));
        if (array_key_exists($name, $skyblockArray)) {

          $islandName = $skyblockArray[$name]["Name"];
          $owner = implode(" ", array_slice($args, 1));
          $membersArray = $skyblockArray[$name]["Members"];
          $members = implode(", ", $membersArray);
          $memberCount = count($skyblockArray[$name]["Members"]);
          if ($skyblockArray[$name]["Locked"] === true) {

            $isLocked = "Yes";
          } else {

            $isLocked = "No";
          }

          $sender->sendMessage(TextFormat::RED . $owner . "'s Island Info: \n" . TextFormat::GRAY . "Owner: {$owner} \n" . "Island Name: {$islandName} \n" . "Total Members: {$memberCount} \n" . "Members: {$members} \n" . "Locked: {$isLocked}");
          return true;
        } else {

          $sender->sendMessage(TextFormat::WHITE . implode(" ", array_slice($args, 1)) . TextFormat::RED . " does not have an island.");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

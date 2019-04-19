<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Delete {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onDeleteCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.delete")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is delete <player>");
        return true;
      } else {

        $playerName = strtolower(implode(" ", array_slice($args, 1)));
        $player = SkyBlock::getInstance()->getServer()->getPlayerExact(implode(" ", array_slice($args, 1)));
        if ($player) {

          $player->teleport(SkyBlock::getInstance()->getServer()->getDefaultLevel()->getSafeSpawn());
          $player->sendMessage(TextFormat::RED . "Your island has been deleted by a server administrator");
        }
        $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);

        if (array_key_exists($playerName, $skyblockArray)) {

          $sender->sendMessage(TextFormat::GREEN . "You have successfully deleted " . TextFormat::WHITE . $skyblockArray[$playerName]["Members"][0] . "'s " . TextFormat::GREEN . "island.");
          unset($skyblockArray[$playerName]);

          SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
          SkyBlock::getInstance()->skyblock->save();
          return true;
        } else {

          $sender->sendMessage(TextFormat::RED . "This player does not have an island to delete.");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

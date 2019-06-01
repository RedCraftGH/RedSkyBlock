<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Decrease {

  private static $instance;

  public function __contruct() {

    self::$instance = $this;
  }

  public function onDecreaseCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.size")) {

      if (count($args) < 3) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is decrease <amount> <player>");
        return true;
      } else {

        if (is_numeric($args[1]) && intval($args[1]) > 0) {

          $name = strtolower(implode(" ", array_slice($args, 2)));
          $player = SkyBlock::getInstance()->getServer()->getPlayerExact(implode(" ", array_slice($args, 2)));
          $amount = round(intval($args[1]) / 2);
          if ($player) {

            $player->sendMessage(TextFormat::GREEN . "Your island's limits have been decreased by {$args[1]}");
          }
          $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);

          if (array_key_exists($name, $skyblockArray)) {

            $startX = $skyblockArray[$name]["Area"]["start"]["X"];
            $startZ = $skyblockArray[$name]["Area"]["start"]["Z"];
            $endX = $skyblockArray[$name]["Area"]["end"]["X"];
            $endZ = $skyblockArray[$name]["Area"]["end"]["Z"];

            $startX += $amount;
            $startZ += $amount;
            $endX -= $amount;
            $endZ -= $amount;

            $skyblockArray[$name]["Area"]["start"]["X"] = $startX;
            $skyblockArray[$name]["Area"]["start"]["Z"] = $startZ;
            $skyblockArray[$name]["Area"]["end"]["X"] = $endX;
            $skyblockArray[$name]["Area"]["end"]["Z"] = $endZ;

            if ($startX > $endX || $startZ > $endZ) {

              $sender->sendMessage(TextFormat::RED . "The amount you've entered is bigger than {$name}'s island. Operation Abandoned.");
              return true;
            }

            SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
            SkyBlock::getInstance()->skyblock->save();
            $sender->sendMessage(TextFormat::WHITE . $name . "'s" . TextFormat::GREEN . " island limits have been decreased by {$args[1]}");
            return true;
          } else {

            $sender->sendMessage(TextFormat::WHITE . $name . TextFormat::RED . " does not have an island!");
            return true;
          }
        } else {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is decrease <amount> <player>");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

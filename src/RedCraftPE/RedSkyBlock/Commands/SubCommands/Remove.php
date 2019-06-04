<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Remove {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onRemoveCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.members")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is remove <player>");
        return true;
      } else {

        $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
        $senderName = strtolower($sender->getName());
        $playerName = str_replace("\"", "", implode(" ", array_slice($args, 1)));
        $playerName = str_replace("'", "", $playerName);
        $playerName = str_replace("`", "", $playerName);
        $player = SkyBlock::getInstance()->getServer()->getPlayerExact($playerName);
        if (!$player) {

          $sender->sendMessage(TextFormat::WHITE . implode(" ", array_slice($args, 1)) . TextFormat::RED . " does not exist or is not online.");
          return true;
        } else {

          if (array_key_exists($senderName, $skyblockArray)) {

            if (in_array($player->getName(), $skyblockArray[$senderName]["Members"])) {

              if ($player->getName() !== $sender->getName()) {

                unset($skyblockArray[$senderName]["Members"][array_search($player->getName(), $skyblockArray[$senderName]["Members"])]);
                SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
                SkyBlock::getInstance()->skyblock->save();
                $sender->sendMessage(TextFormat::WHITE . $player->getName() . TextFormat::GREEN . " has been removed from your island!");
                return true;
              } else {

                $sender->sendMessage(TextFormat::RED . "You cannot remove yourself from your island!");
                return true;
              }
            } else {

              $sender->sendMessage(TextFormat::WHITE . $player->getName() . TextFormat::RED . " is not a member of your island.");
              return true;
            }
          } else {

            $sender->sendMessage(TextFormat::RED . "You do not have an island yet.");
            return true;
          }
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

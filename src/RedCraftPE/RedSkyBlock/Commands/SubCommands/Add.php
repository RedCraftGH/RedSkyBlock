<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Add {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onAddCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.members")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is add <player>");
        return true;
      } else {

        $senderName = strtolower($sender->getName());
        $limit = SkyBlock::getInstance()->cfg->get("MemberLimit");
        $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
        $playerName = str_replace("\"", "", implode(" ", array_slice($args, 1)));
        $playerName = str_replace("'", "", $playerName);
        $playerName = str_replace("`", "", $playerName);
        $player = SkyBlock::getInstance()->getServer()->getPlayerExact($playerName);
        if (!$player) {

          $sender->sendMessage(TextFormat::WHITE . implode(" ", array_slice($args, 1)) . TextFormat::RED . " does not exist or is not online.");
          return true;
        } else {

          if (array_key_exists($senderName, $skyblockArray)) {

            if (count($skyblockArray[$senderName]["Members"]) === $limit && !$sender->hasPermission("skyblock.umembers")) {

              $sender->sendMessage(TextFormat::RED . "Your island has reached the maximum number of members.");
              return true;
            } else {

              if (in_array($player->getName(), $skyblockArray[$senderName]["Members"])) {

                $sender->sendMessage(TextFormat::WHITE . $player->getName() . TextFormat::RED . " is already a member of your island.");
                return true;
              } else {

                if (!in_array($player->getName(), $skyblockArray[$senderName]["Banned"])) {

                  $skyblockArray[$senderName]["Members"][] = $player->getName();
                  SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
                  SkyBlock::getInstance()->skyblock->save();
                  $sender->sendMessage(TextFormat::WHITE . $player->getName() . TextFormat::GREEN . " has been added to your island.");
                  $player->sendMessage(TextFormat::WHITE . $sender->getName() . TextFormat::GREEN . " has added you to their island.");
                  return true;
                } else {

                  $sender->sendMessage(TextFormat::WHITE . $player->getName() . TextFormat::RED . " is banned from your island.");
                  return true;
                }
              }
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

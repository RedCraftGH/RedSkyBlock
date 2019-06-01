<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\block\Block;
use pocketmine\item\Item;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Claim {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onClaimCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.challenges")) {

      $challengesArray = SkyBlock::getInstance()->cfg->get("Challenges", []);
      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $senderName = strtolower($sender->getName());

      if (array_key_exists($senderName, $skyblockArray)) {

        if (count($args) < 2) {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is claim <challenge>");
          return true;
        } else {

          $challengeName = implode(" ", array_slice($args, 1));
          $challenge = NULL;

          foreach(array_keys($challengesArray) as $c) {

            $cArray = explode(":", $c);
            $cName = $cArray[5];
            if (strtolower($cName) === strtolower($challengeName)) {

              $challenge = $c;
              break;
            }
          }

          if ($challenge === NULL) {

            $sender->sendMessage(TextFormat::RED . "The challenge " . TextFormat::WHITE . $challengeName . TextFormat::RED . " does not exist!");
            return true;
          } else {

            $challengeArray = explode(":", $challenge);
            $rewardsArray = $challengesArray[$challenge]["rewards"];
            $repeating = $challengesArray[$challenge]["repeating"];
            $type = $challengeArray[1];

            if ($type === "collect") {

              $id = $challengeArray[2];
              $meta = $challengeArray[3];
              $amount = $challengeArray[4];
              $item = Item::get($id, $meta, $amount);
              $inventory = $sender->getInventory();

              if ($inventory->contains($item)) {

                if (in_array($challengeArray[5], $skyblockArray[$senderName]["Challenges"])) {

                  $sender->sendMessage(TextFormat::RED . "You've already completed this challenge.");
                  return true;
                } else {

                  if (!$repeating) {

                    array_push($skyblockArray[$senderName]["Challenges"], $challengeArray[5]);
                  }

                  $sender->sendMessage(TextFormat::GREEN . "Congrats! You've completed the challenge " . TextFormat::WHITE . $challengeArray[5]);
                  foreach($rewardsArray as $reward) {

                    if (strpos($reward, "value")) {

                      $vArray = explode(" ", $reward);
                      $amount = (int) $vArray[0];

                      $skyblockArray[$senderName]["Value"] += $amount;
                    } else {

                      $rArray = explode(":", $reward);
                      $id = $rArray[0];
                      $meta = $rArray[1];
                      $amount = $rArray[2];
                      $rItem = Item::get($id, $meta, $amount);

                      $inventory->addItem($rItem);
                    }
                  }
                  SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
                  SkyBlock::getInstance()->skyblock->save();
                  return true;
                }
              } else {

                $sender->sendMessage(TextFormat::RED . "You do not meet the requirements for this challenge!");
                return true;
              }
            } else if ($type === "add") {

              $amount = $challengeArray[2];

              if (count($skyblockArray[$senderName]["Members"]) > $amount + 1) {

                if (in_array($challengeArray[5], $skyblockArray[$senderName]["Challenges"])) {

                  $sender->sendMessage(TextFormat::RED . "You've already completed this challenge.");
                  return true;
                } else {

                  if (!$repeating) {

                    array_push($skyblockArray[$senderName]["Challenges"], $challengeArray[5]);
                  }

                  $sender->sendMessage(TextFormat::GREEN . "Congrats! You've completed the challenge " . TextFormat::WHITE . $challengeArray[5]);
                  foreach($rewardsArray as $reward) {

                    if (strpos($reward, "value")) {

                      $vArray = explode(" ", $reward);
                      $amount = (int) $vArray[0];

                      $skyblockArray[$senderName]["Value"] += $amount;
                    } else {

                      $rArray = explode(":", $reward);
                      $id = $rArray[0];
                      $meta = $rArray[1];
                      $amount = $rArray[2];
                      $item = Item::get($id, $meta, $amount);

                      $inventory->addItem($item);
                    }
                  }
                  SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
                  SkyBlock::getInstance()->skyblock->save();
                  return true;
                }
              } else {

                $sender->sendMessage(TextFormat::RED . "You do not meet the requirements for this challenge!");
                return true;
              }
            }
          }
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "You do not have an island yet.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

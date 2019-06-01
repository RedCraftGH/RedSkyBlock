<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\block\Block;
use pocketmine\item\Item;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Challenges {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onChallengeCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.challenges")) {

      $challengesArray = SkyBlock::getInstance()->cfg->get("Challenges", []);

      if (count($args) === 1) {

        if (SkyBlock::getInstance()->cfg->get("Enable Challenges")) {

          $challenges = ["easy" => [], "intermediate" => [], "advanced" => []];

          foreach(array_keys($challengesArray) as $challenge) {

            $challengeArray = explode(":", $challenge);
            $name = $challengeArray[5];
            array_push($challenges[$challengeArray[0]], $name);
          }
          $sender->sendMessage(TextFormat::GREEN . "Easy: \n" . implode(", ", $challenges["easy"]) . "\n" . TextFormat::YELLOW . "Intermediate: \n" . implode(", ", $challenges["intermediate"]) . "\n" . TextFormat::RED . "Advanced: \n" . implode(", ", $challenges["advanced"]));
          return true;
        } else {

          $sender->sendMessage(TextFormat::RED . "SkyBlock challenges are disabled on this server.");
          return true;
        }
      } else {

        $challengeName = implode(" ", array_slice($args, 1));

        foreach(array_keys($challengesArray) as $challenge) {

          $challengeArray = explode(":", $challenge);
          $name = $challengeArray[5];

          if (strtolower($name) === strtolower($challengeName)) {

            $difficulty = $challengeArray[0];
            $type = $challengeArray[1];
            if ($type === "collect") {

              $id = $challengeArray[2];
              $meta = $challengeArray[3];
              $amount = $challengeArray[4];
              $item = Item::get($id, $meta);

              $sender->sendMessage(TextFormat::AQUA . "Challenge: {$name} \nDifficulty: {$difficulty} \nDescription: Collect {$amount} " . $item->getName() . "(s)");
              return true;
            } else if ($type === "place") {

              $id = $challengeArray[2];
              $meta = $challengeArray[3];
              $amount = $challengeArray[4];
              $block = Block::get($id, $meta);

              $sender->sendMessage(TextFormat::AQUA . "Challenge: {$name} \nDifficulty: {$difficulty} \nDescription: Place {$amount} " . $block->getName() . "(s) on your island.");
              return true;
            } else if ($type === "add") {

              $amount = $challengeArray[2];

              $sender->sendMessage(TextFormat::AQUA . "Challenge: {$name} \nDifficulty: {$difficulty} \nDescription: Add {$amount} member(s) to your island.");
              return true;
            }
          }
        }
        $sender->sendMessage(TextFormat::RED . "The challenge " . TextFormat::WHITE . $challengeName . TextFormat::RED . " does not exist.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

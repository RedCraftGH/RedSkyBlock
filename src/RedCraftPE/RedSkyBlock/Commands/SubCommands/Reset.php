<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\item\Item;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;
use RedCraftPE\RedSkyBlock\Tasks\Generate;
use RedCraftPE\RedSkyBlock\Generators\WorldGenerator;

class Reset {

  private static $instance;

  public function __construct($plugin) {

    $this->worldGenerator = new WorldGenerator($plugin);
    self::$instance = $this;
  }

  public function onResetCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.reset")) {

      $interval = SkyBlock::getInstance()->cfg->get("Interval");
      $itemsArray = SkyBlock::getInstance()->cfg->get("Starting Items", []);
      $levelName = SkyBlock::getInstance()->cfg->get("SkyBlockWorld");
      $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
      $islands = SkyBlock::getInstance()->skyblock->get("Islands");
      $initialSize = SkyBlock::getInstance()->cfg->get("Island Size");
      $senderName = strtolower($sender->getName());
      $worldsArray = SkyBlock::getInstance()->cfg->get("SkyBlockWorlds", []);
      $worldCount = (int) SkyBlock::getInstance()->skyblock->get("worlds");
      $baseName = SkyBlock::getInstance()->cfg->get("SkyBlockWorld Base Name");
      $cooldown = (int) SkyBlock::getInstance()->cfg->get("Reset Cooldown");

      if ($baseName === false) {

        $sender->sendMessage(TextFormat::RED . "You must set a SkyBlock world in order for this plugin to function properly.");
        return true;
      }
      $level = SkyBlock::getInstance()->getServer()->getLevelByName($baseName);
      if (!$level) {

        $sender->sendMessage(TextFormat::RED . "The world currently set as the SkyBlock world does not exist.");
        return true;
      }

      if (array_key_exists($senderName, $skyblockArray)) {

        if (Time() >= (int) $skyblockArray[$senderName]["Reset Cooldown"]) {

          if ($islands >= SkyBlock::getInstance()->cfg->get("World Island Limit")) {

            $worldCount++;
            $this->worldGenerator->generateWorld($baseName . $worldCount);
            $world = SkyBlock::getInstance()->getServer()->getLevelByName($baseName . $worldCount);
            array_push($worldsArray, $baseName . $worldCount);
            $islands = 0;
            SkyBlock::getInstance()->cfg->set("SkyBlockWorlds", $worldsArray);
            SkyBlock::getInstance()->cfg->save();
            SkyBlock::getInstance()->skyblock->set("Islands", $islands);
            SkyBlock::getInstance()->skyblock->set("worlds", $worldCount);
          } else {

            $world = SkyBlock::getInstance()->getServer()->getLevelByName(end($worldsArray));
            if (!$world) {

              $sender->sendMessage(TextFormat::RED . "The world currently set as the SkyBlock world does not exist.");
              return true;
            }
          }

          unset($skyblockArray[$senderName]);
          SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
          SkyBlock::getInstance()->skyblock->save();
          $sender->getInventory()->clearAll();
          $sender->sendMessage(TextFormat::GREEN . "Your island has been completely reset.");

          if (SkyBlock::getInstance()->skyblock->get("Custom")) {

            $sender->teleport(new Position($islands * $interval + SkyBlock::getInstance()->skyblock->get("CustomX"), 15 + SkyBlock::getInstance()->skyblock->get("CustomY"), $islands * $interval + SkyBlock::getInstance()->skyblock->get("CustomZ"), $world));
          } else {

            $sender->teleport(new Position($islands * $interval + 2, 15 + 3, $islands * $interval + 4, $world));
          }
          $sender->setImmobile(true);
          SkyBlock::getInstance()->getScheduler()->scheduleDelayedTask(new Generate($islands, $world, $interval, $sender), 30);

          foreach($itemsArray as $items) {

            if (count($itemsArray) > 0) {

              $itemArray = explode(" ", $items);
              if (count($itemArray) === 3) {

                $id = intval($itemArray[0]);
                $damage = intval($itemArray[1]);
                $count = intval($itemArray[2]);
                $sender->getInventory()->addItem(Item::get($id, $damage, $count));
              }
            }
          }

          SkyBlock::getInstance()->skyblock->setNested("Islands", $islands + 1);
          $skyblockArray[$senderName] = Array(
            "Name" => $sender->getName() . "'s Island",
            "Members" => [$sender->getName()],
            "Banned" => [],
            "Locked" => false,
            "Value" => 0,
            "World" => $world->getFolderName(),
            "Reset Cooldown" => Time() + $cooldown,
            "Challenges" => [],
            "Spawn" => Array(
              "X" => $sender->getX(),
              "Y" => $sender->getY(),
              "Z" => $sender->getZ()
            ),
            "Area" => Array(
              "start" => Array(
                "X" => ($islands * $interval + SkyBlock::getInstance()->skyblock->get("CustomX")) - ($initialSize / 2),
                "Y" => 0,
                "Z" => ($islands * $interval + SkyBlock::getInstance()->skyblock->get("CustomZ")) - ($initialSize / 2)
              ),
              "end" => Array(
                "X" => ($islands * $interval + SkyBlock::getInstance()->skyblock->get("CustomX")) + ($initialSize / 2),
                "Y" => 256,
                "Z" => ($islands * $interval + SkyBlock::getInstance()->skyblock->get("CustomZ")) + ($initialSize / 2)
              )
            ),
            "Settings" => Array(
              "Build" => "on",
              "Break" => "on",
              "Pickup" => "on",
              "Anvil" => "on",
              "Chest" => "on",
              "CraftingTable" => "off",
              "Fly" => "off",
              "Hopper" => "on",
              "Brewing" => "off",
              "Beacon" => "on",
              "Buckets" => "on",
              "PVP" => "off",
              "FlintAndSteel" => "on",
              "Furnace" => "on",
              "EnderChest" => "off"
            )
          );
          SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
          SkyBlock::getInstance()->skyblock->save();
          return true;
        } else {

          $sender->sendMessage(TextFormat::RED . "You must wait " . TextFormat::WHITE . gmdate("H:i:s", $skyblockArray[$senderName]["Reset Cooldown"] - Time()) . TextFormat::RED . " before you are able to reset again.");
          return true;
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

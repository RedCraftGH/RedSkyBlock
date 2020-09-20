<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\block\Block;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Tasks\Generate;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Create {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onCreateCommand(CommandSender $sender): bool {

     if ($sender->hasPermission("skyblock.create")) {

      $plugin = $this->plugin;
      $itemsArray = $plugin->cfg->get("Starting Items", []);
      $interval = $plugin->cfg->get("Island Interval");
      $initialSize = $plugin->cfg->get("Island Size");
      $islandSpawnY = $plugin->cfg->get("Island Spawn Y");
      $levelName = $plugin->skyblock->get("Master World");
      $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
      $senderName = strtolower($sender->getName());

      if ($levelName === false) {

        $sender->sendMessage(TextFormat::RED . "You must set a SkyBlock Master world in order for this plugin to function properly.");
        return true;
      } else {

        $level = $plugin->getServer()->getLevelByName($levelName);
        if (!$level) {

          $sender->sendMessage(TextFormat::RED . "The world currently set as the SkyBlock world does not exist.");
          return true;
        }

        if (array_key_exists($senderName, $skyblockArray)) {

          $sender->sendMessage(TextFormat::RED . "You already have a Skyblock island.");
          return true;
        } else {

          if ($plugin->skyblock->get("Zone Created")) {

            $world = $plugin->getServer()->getLevelByName($levelName);
            $turns = $plugin->skyblock->get("Turns");
            $steps = $plugin->skyblock->get("Steps");
            $stepChecker = $plugin->skyblock->get("Step Checker");
            $lastX = $plugin->skyblock->get("Last X");
            $lastZ = $plugin->skyblock->get("Last Z");
            $dir = 0;

            if ($steps === -1) {

              $lastX += $interval;
              $steps = 1;
            } else {

              if ($steps === $stepChecker) {

                $turns++;
                $steps = 0;
                if ($turns % 2 === 0) {

                  $stepChecker++;
                }

                $dir =  intval($turns - ((floor($turns/4)) * 4));
              } else {

                $dir =  intval($turns - ((floor($turns/4)) * 4));
              }
              if ($dir === 0) {

                $lastX += $interval;
                $steps++;
              } elseif ($dir === 1) {

                $lastZ += $interval;
                $steps++;
              } elseif ($dir === 2) {

                $lastX -= $interval;
                $steps++;
              } elseif ($dir === 3) {

                $lastZ -= $interval;
                $steps++;
              }
            }

            $sender->teleport(new Position($lastX + 4, $islandSpawnY, $lastZ + 2, $world));
            $sender->setImmobile(true);

            $plugin->getScheduler()->scheduleDelayedTask(new Generate($plugin, $sender, $lastX, $lastZ, $world), 50);

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

            $playerData = array("Island Members" => [], "Island Spawn" => [], "Island Locked" => false);

            if (file_put_contents($plugin->getDataFolder() . "Players/" . $senderName . ".json", json_encode($playerData)) !== false) {

              $sender->sendMessage(TextFormat::GREEN . "You have successfully created your skyblock island.");
            } else {

              $plugin->getLogger()->info(TextFormat::RED . "Error: {$sender->getName}'s player files were not successfully generated.");
            }

            $skyblockArray[$senderName] = [$lastX + 4, $lastZ + 2];
            $plugin->skyblock->set("SkyBlock", $skyblockArray);
            $plugin->skyblock->set("Steps", $steps);
            $plugin->skyblock->set("Turns", $turns);
            $plugin->skyblock->set("Step Checker", $stepChecker);
            $plugin->skyblock->set("Last X", $lastX);
            $plugin->skyblock->set("Last Z", $lastZ);
            $plugin->skyblock->save();
            return true;
          } else {

            $sender->sendMessage(TextFormat::RED . "A custom island zone must be created before islands can be generated.");
            return true;
          }
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

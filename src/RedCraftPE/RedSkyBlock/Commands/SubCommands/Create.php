<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\item\StringToItemParser;
use pocketmine\world\Position;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;
use RedCraftPE\RedSkyBlock\Tasks\IslandGenerator;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Create extends SBSubCommand {

  public static $instance;

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
    self::$instance = $this;
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if ($this->checkMasterWorld()) {

      if ($this->checkZone()) {

        $plugin = $this->plugin;
        $masterWorldName = $plugin->skyblock->get("Master World");

        if (!$plugin->getServer()->getWorldManager()->isWorldLoaded($masterWorldName)) {

          if (!$plugin->getServer()->getWorldManager()->loadWorld($masterWorldName)) {

            $message = $this->getMShop()->construct("LOAD_ERROR");
            $sender->sendMessage($message);
          }
        } else {

          if (!$this->checkIsland($sender)) {

            $interval = $plugin->cfg->get("Island Interval");
            $initialSize = $plugin->cfg->get("Island Size");
            $islandSpawnY = $plugin->cfg->get("Island Spawn Y");
            $resetCooldown = $plugin->cfg->get("Reset Cooldown");
            $senderName = $sender->getName();
            $masterWorld = $plugin->getServer()->getWorldManager()->getWorldByName($masterWorldName);

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

            $cSpawnVals = $plugin->skyblock->get("CSpawnVals", []);
            $initialSpawnPoint = [$lastX + $cSpawnVals[0], $islandSpawnY + $cSpawnVals[1], $lastZ + $cSpawnVals[2]];

            $sender->teleport(new Position($initialSpawnPoint[0], $initialSpawnPoint[1], $initialSpawnPoint[2], $masterWorld));
            $sender->setImmobile(true);
            $plugin->getScheduler()->scheduleDelayedTask(new IslandGenerator($plugin, $sender, $lastX, $lastZ, $masterWorld), 60);

            $islandData = [
              "creator" => $sender->getName(),
              "name" => $sender->getName() . "'s island",
              "size" => $initialSize,
              "value" => 0,
              "initialspawnpoint" => $initialSpawnPoint,
              "spawnpoint" => $initialSpawnPoint,
              "members" => [],
              "banned" => [],
              "resetcooldown" => Time() + $resetCooldown,
              "lockstatus" => false,
              "settings" => [],
              "stats" => []
            ];

            $island = $plugin->islandManager->constructIsland($islandData);

            $plugin->skyblock->set("Steps", $steps);
            $plugin->skyblock->set("Turns", $turns);
            $plugin->skyblock->set("Step Checker", $stepChecker);
            $plugin->skyblock->set("Last X", $lastX);
            $plugin->skyblock->set("Last Z", $lastZ);
            $plugin->skyblock->save();

            if (file_put_contents($plugin->getDataFolder() . "../RedSkyBlock/Players/" . $senderName . ".json", json_encode($islandData)) !== false) {

              $message = $this->getMShop()->construct("ISLAND_CREATED");
              $sender->sendMessage($message);
            } else {

              $message = $this->getMShop()->construct("FILE_CREATION_ERROR");
              $sender->sendMessage($message);
            }
          } else {

            $message = $this->getMShop()->construct("ALREADY_CREATED_ISLAND");
            $sender->sendMessage($message);
          }
        }
      } else {

        $message = $this->getMShop()->construct("NO_ZONE");
        $sender->sendMessage($message);
      }
    } else {

      $message = $this->getMShop()->construct("NO_MASTER_WORLD");
      $sender->sendMessage($message);
    }
  }

  public static function getInstance(): self {

    return self::$instance;
  }
}

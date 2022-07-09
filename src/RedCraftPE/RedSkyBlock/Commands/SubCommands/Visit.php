<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;
use pocketmine\world\Position;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\args\TextArgument;

class Visit extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new TextArgument("target", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["target"])) {

      $name = $args["target"];
      $island = $this->plugin->islandManager->getIslandByName($name);
      $player = $this->plugin->getServer()->getPlayerByPrefix($name);
      if ($island instanceof Island) {

        $islandName = $island->getName();
        $islandCreator = $island->getCreator();
        $members = $island->getMembers();
        $banned = $island->getBanned();
        $lockStatus = $island->getLockStatus();

        if (!in_array(strtolower($sender->getName()), $banned)) {

          if (!$lockStatus || in_array(strtolower($sender->getName), $members) || $sender->hasPermission("redskyblock.bypass") || $islandCreator === $sender->getName()) {

            $masterWorld = $this->plugin->islandManager->getMasterWorld();
            $islandSpawn = $island->getSpawnPoint();
            $sender->teleport(new Position($islandSpawn[0], $islandSpawn[1], $islandSpawn[2], $masterWorld));

            $message = $this->getMShop()->construct("WELCOME_TO_ISLAND");
            $message = str_replace("{ISLAND_NAME}", $islandName, $message);
            $sender->sendMessage($message);
          } else {

            $message = $this->getMShop()->construct("ISLAND_LOCKED");
            $message = str_replace("{ISLAND_NAME}", $islandName, $message);
            $sender->sendMessage($message);
          }
        } else {

          $message = $this->getMShop()->construct("BANNED");
          $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
          $sender->sendMessage($message);
        }
      } elseif ($player instanceof Player) {

        if ($this->checkIsland($player)) {

          $island = $this->plugin->islandManager->getIsland($player);
          $islandCreator = $island->getCreator();
          $islandName = $island->getName();
          $members = $island->getMembers();
          $banned = $island->getBanned();
          $lockStatus = $island->getLockStatus();

          if (!in_array(strtolower($sender->getName()), $banned)) {

            if (!$lockStatus || in_array(strtolower($sender->getName()), $members) || $sender->hasPermission("redskyblock.bypass") || $sender->getName() === $islandCreator) {

              $masterWorld = $this->plugin->islandManager->getMasterWorld();
              $islandSpawn = $island->getSpawnPoint();
              $sender->teleport(new Position($islandSpawn[0], $islandSpawn[1], $islandSpawn[2], $masterWorld));

              $message = $this->getMShop()->construct("WELCOME_TO_ISLAND");
              $message = str_replace("{ISLAND_NAME}", $islandName, $message);
              $sender->sendMessage($message);
            } else {

              $message = $this->getMShop()->construct("ISLAND_LOCKED");
              $message = str_replace("{ISLAND_NAME}", $islandName, $message);
              $sender->sendMessage($message);
            }
          } else {

            $message = $this->getMShop()->construct("BANNED");
            $message = str_replace("{ISLAND_NAME}", $island->getName(), $message);
            $sender->sendMessage($message);
          }
        } else {

          $message = $this->getMShop()->construct("PLAYER_HAS_NO_ISLAND");
          $message = str_replace("{NAME}", $player->getName(), $message);
          $sender->sendMessage($message);
        }
      } else {

        $message = $this->getMShop()->construct("TARGET_NOT_FOUND");
        $message = str_replace("{NAME}", $name, $message);
        $sender->sendMessage($message);
      }
    } else {

      $this->sendUsage();
      return;
    }
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Island;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

class Info extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    $island = $this->plugin->islandManager->getIslandAtPlayer($sender);
    if ($island instanceof Island) {

      if ($island->getCreator() === $sender->getName() || in_array(strtolower($sender->getName()), $island->getMembers()) || $sender->hasPermission("redskyblock.admin")) {

        $sender->sendMessage($this->getIslandInfoFull($island));
      } else {

        $islandName = $island->getName();
        $islandCreator = $island->getCreator();
        $islandValue = $island->getValue();
        $islandSpawnPoint = implode(", ", $island->getSpawnPoint());
        $islandStats = $island->getStats();
        $islandStatsString = "";
        foreach($islandStats as $stat => $value) {

          $statName = str_replace("_", " ", $stat);
          $islandStatsString .= $statName . ": " . $value . " | ";
        }
        if ($island->getLockStatus()) {

          $islandLockStatus = "Locked";
        } else {

          $islandLockStatus = "Unlocked";
        }

        $message = $this->getMShop()->construct("ISLAND_INFO_LIMITED");
        $message = str_replace("{ISLAND_NAME}", $islandName, $message);
        $message = str_replace("{ISLAND_CREATOR}", $islandCreator, $message);
        $message = str_replace("{ISLAND_VALUE}", $islandValue, $message);
        $message = str_replace("{LOCK_STATUS}", $islandLockStatus, $message);
        $message = str_replace("{SPAWN_POINT}", $islandSpawnPoint, $message);
        $message = str_replace("{ISLAND_STATS}", $islandStatsString, $message);
        $sender->sendMessage($message);
      }
    } else {

      if ($this->checkIsland($sender)) {

        $island = $this->plugin->islandManager->getIsland($sender);
        $sender->sendMessage($this->getIslandInfoFull($island));
      } else {

        $message = $this->getMShop()->construct("NO_ISLAND");
        $sender->sendMessage($message);
      }
    }
  }

  public function getIslandInfoFUll(Island $island): string {

    $islandName = $island->getName();
    $islandMembers = implode(", ", array_keys($island->getMembers()));
    if ($islandMembers === "") $islandMembers = "N/A";
    $islandBanned = implode(", ", $island->getBanned());
    if ($islandBanned === "") $islandBanned = "N/A";
    $islandSpawnPoint = implode(", ", $island->getSpawnPoint());
    $islandSettings = $island->getSettings();
    $islandSettingsString = "";
    foreach($islandSettings as $setting => $status) {

      if ($status) {

        $isSettingActive = "on";
      } else {

        $isSettingActive = "off";
      }
      $settingName = str_replace("_", " ", $setting);
      $islandSettingsString .= $settingName . ": " . $isSettingActive . " | ";
    }
    $islandStats = $island->getStats();
    $islandStatsString = "";
    foreach($islandStats as $stat => $value) {

      $statName = str_replace("_", " ", $stat);
      $islandStatsString .= $statName . ": " . $value . " | ";
    }
    if ($island->getLockStatus()) {

      $islandLockStatus = "Locked";
    } else {

      $islandLockStatus = "Unlocked";
    }
    if (Time() >= $island->getResetCooldown()) {

      $islandTimeToReset = gmdate("H:i:s", Time() + 86400);
    } else {

      $islandTimeToReset = gmdate("H:i:s", $island->getResetCooldown() - Time());
    }
    $islandSize = $island->getSize();
    $islandValue = $island->getValue();
    $islandCreator = $island->getCreator();

    $message = $this->getMShop()->construct("ISLAND_INFO_FULL");
    $message = str_replace("{ISLAND_NAME}", $islandName, $message);
    $message = str_replace("{ISLAND_CREATOR}", $islandCreator, $message);
    $message = str_replace("{ISLAND_SIZE}", $islandSize, $message);
    $message = str_replace("{ISLAND_VALUE}", $islandValue, $message);
    $message = str_replace("{RESET_COOLDOWN}", $islandTimeToReset, $message);
    $message = str_replace("{LOCK_STATUS}", $islandLockStatus, $message);
    $message = str_replace("{MEMBERS}", $islandMembers, $message);
    $message = str_replace("{BANNED}", $islandBanned, $message);
    $message = str_replace("{SPAWN_POINT}", $islandSpawnPoint, $message);
    $message = str_replace("{ISLAND_SETTINGS}", $islandSettingsString, $message);
    $message = str_replace("{ISLAND_STATS}", $islandStatsString, $message);
    return $message;
  }
}

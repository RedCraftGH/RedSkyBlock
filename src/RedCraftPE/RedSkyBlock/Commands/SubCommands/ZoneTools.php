<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;
use RedCraftPE\RedSkyBlock\Utils\ZoneManager;

class ZoneTools extends SBSubCommand {

  private $zoneShovel;
  private $spawnFeather;

  public function prepare(): void {

    $this->setPermission("redskyblock.admin;redskyblock.zone");
    $this->zoneShovel = ZoneManager::getZoneShovel();
    $this->spawnFeather = ZoneManager::getSpawnFeather();
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    $plugin = $this->plugin;
    $zoneKeeper = ZoneManager::getZoneKeeper();
    $senderInv = $sender->getInventory();
    $senderContents = $senderInv->getContents();
    $zoneShovel = clone $this->zoneShovel;
    $spawnFeather = clone $this->spawnFeather;

    if ($zoneKeeper != $sender) {

      if ($zoneKeeper == null) {

        ZoneManager::clearZoneTools($sender);
        $senderInv->addItem($zoneShovel);
        $senderInv->addItem($spawnFeather);
        ZoneManager::setZoneKeeper($sender);
        ZoneManager::setSpawnPosition();
        ZoneManager::setFirstPosition();
        ZoneManager::setSecondPosition();
        return;
      } else {

        ZoneManager::clearZoneTools($zoneKeeper);
        ZoneManager::setZoneKeeper($sender);
        ZoneManager::setSpawnPosition();
        ZoneManager::setFirstPosition();
        ZoneManager::setSecondPosition();
        $senderInv->addItem($zoneShovel);
        $senderInv->addItem($spawnFeather);
        return;
      }
    } elseif (!$senderInv->contains($zoneShovel) || !$senderInv->contains($spawnFeather)) {

      ZoneManager::clearZoneTools($sender);
      $senderInv->addItem($zoneShovel);
      $senderInv->addItem($spawnFeather);
      return;
    }

    return;
  }
}

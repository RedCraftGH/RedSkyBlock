<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class CustomSpawn{

	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onCustomSpawnCommand(CommandSender $sender) : bool{

		if($sender->hasPermission("redskyblock.updatezone") and $sender instanceof Player){

			$plugin = $this->plugin;
			$islandZone = $plugin->cfg->get("Island Zone", []);

			if($islandZone[0] === 0 && $islandZone[1] === 0 && $islandZone[2] === 0){

				$sender->sendMessage(TextFormat::RED . "You must set position 1 of your custom island zone first.");
				return true;
			}else{

				if($islandZone[3] === 0 && $islandZone[4] === 0 && $islandZone[5] === 0){

					$sender->sendMessage(TextFormat::RED . "You must set position 2 of your custom island zone first.");
					return true;
				}else{

					$posOneX = $islandZone[0];
					$posOneY = $islandZone[1];
					$posOneZ = $islandZone[2];
					$posTwoX = $islandZone[3];
					$posTwoY = $islandZone[4];
					$posTwoZ = $islandZone[5];

					$islandHeight = max($posOneY, $posTwoY) - min($posOneY, $posTwoY);

					$zoneX = min($posOneX, $posTwoX);
					$zoneZ = min($posOneZ, $posTwoZ);

					$playerX = round($sender->getPosition()->getX());
					$playerZ = round($sender->getPosition()->getZ());

					$cSpawnVals = $plugin->skyblock->get("CSpawnVals", []);
					$cSpawnVals[0] = $playerX - $zoneX;
					$cSpawnVals[1] = 81 + $islandHeight;
					$cSpawnVals[2] = $playerZ - $zoneZ;

					$sender->sendMessage(TextFormat::GREEN . "Your custom island main spawn has been set!");

					$plugin->skyblock->set("CSpawnVals", $cSpawnVals);
					$plugin->skyblock->save();
					return true;
				}
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

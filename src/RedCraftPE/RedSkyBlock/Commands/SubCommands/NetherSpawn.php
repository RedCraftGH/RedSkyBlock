<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class NetherSpawn{

	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onNetherSpawnCommand(CommandSender $sender) : bool{

		if($sender->hasPermission("redskyblock.nether")){

			$plugin = $this->plugin;

			if($plugin->cfg->get("Nether Islands")){

				$owner = $plugin->getIslandAtPlayer($sender);
				$senderName = strtolower($sender->getName());
				$skyblockArray = $plugin->skyblock->get("SkyBlock", []);
				$filePath = $plugin->getDataFolder() . "Players/" . strtolower($sender->getName()) . ".json";

				if(array_key_exists($senderName, $skyblockArray)){

					if(strtolower($sender->getName()) === $owner && $sender instanceof Player && $sender->getWorld()->getFolderName() === $plugin->skyblock->get("Master World") . "-Nether"){

						$playerDataEncoded = file_get_contents($filePath);
						$playerData = (array) json_decode($playerDataEncoded, true);

						$playerData["Nether Spawn"][0] = (int) round($sender->getPosition()->getX());
						$playerData["Nether Spawn"][1] = (int) round($sender->getPosition()->getY());
						$playerData["Nether Spawn"][2] = (int) round($sender->getPosition()->getZ());

						$playerDataEncoded = json_encode($playerData);
						file_put_contents($filePath, $playerDataEncoded);
						$sender->sendMessage(TextFormat::GREEN . "Your nether island spawn point has been set to " . TextFormat::WHITE . (int) round($sender->getPosition()->getX()) . TextFormat::GREEN . ", " . TextFormat::WHITE . (int) round($sender->getPosition()->getY()) . TextFormat::GREEN . ", " . TextFormat::WHITE . (int) round($sender->getPosition()->getZ()) . TextFormat::GREEN . ".");
						return true;
					}else{

						$sender->sendMessage(TextFormat::RED . "You must be on your nether island to set your island's spawn point.");
						return true;
					}
				}else{

					$sender->sendMessage(TextFormat::RED . "You have not created a SkyBlock island yet.");
					return true;
				}
			}else{

				$sender->sendMessage(TextFormat::RED . "Nether Skyblock has been disabled.");
				return true;
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Restart{

	public $plugin;

	public $create;

	public function __construct($plugin){

		$this->plugin = $plugin;
		$this->create = new Create($plugin);
	}

	public function onRestartCommand(CommandSender $sender) : bool{

		if($sender->hasPermission("redskyblock.restart") and $sender instanceof \pocketmine\player\Player){

			$plugin = $this->plugin;
			$skyblockArray = $plugin->skyblock->get("SkyBlock", []);
			$senderName = strtolower($sender->getName());

			if(array_key_exists($senderName, $skyblockArray)){

				$filePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";
				$playerDataEncoded = file_get_contents($filePath);
				$playerData = (array) json_decode($playerDataEncoded);

				if(time() >= $playerData["Cooldown"]){

					// Removes all existing player data : optional clear inventory:
					unlink($filePath);
					unset($skyblockArray[$senderName]);
					$plugin->skyblock->set("SkyBlock", $skyblockArray);
					$plugin->skyblock->save();
					if($plugin->cfg->get("Reset Wipe")){

						$sender->getInventory()->clearAll();
					}

					//runs the create command to reinstate the player with a new island and clean data:
					return $this->create->onCreateCommand($sender);
				}else{

					$sender->sendMessage(TextFormat::RED . "You must wait " . TextFormat::WHITE . gmdate("H:i:s", $playerData["Cooldown"] - Time()) . TextFormat::RED . " before you can restart your SkyBlock island again.");
					return true;
				}
			}else{

				$sender->sendMessage(TextFormat::RED . "You don't have a SkyBlock island yet.");
				return true;
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command!");
			return true;
		}
	}
}

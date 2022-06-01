<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Unban{
	
	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onUnbanCommand(CommandSender $sender, array $args) : bool{

		if($sender->hasPermission("redskyblock.ban")){

			if(count($args) < 2){

				$sender->sendMessage(TextFormat::WHITE . "Usage: /is unban <player>");
				return true;
			}else{

				$plugin = $this->plugin;
				$skyblockArray = $plugin->skyblock->get("SkyBlock", []);
				$senderName = strtolower($sender->getName());

				if(array_key_exists($senderName, $skyblockArray)){

					$name = strtolower(implode(" ", array_slice($args, 1)));
					$playerFromName = $plugin->getServer()->getPlayerByPrefix($name);
					if($playerFromName === null){

						$playerFromName = $name;
					}
					$filePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";
					$playerDataEncoded = file_get_contents($filePath);
					$playerData = (array) json_decode($playerDataEncoded, true);

					if(in_array($name, $playerData["Banned"])){

						$key = array_search($name, $playerData["Banned"]);
						unset($playerData["Banned"][$key]);
						$playerDataEncoded = json_encode($playerData);
						file_put_contents($filePath, $playerDataEncoded);
						$sender->sendMessage(TextFormat::WHITE . $name . TextFormat::GREEN . " is no longer banned from your island.");
						return true;
					}else{

						$sender->sendMessage(TextFormat::WHITE . $name . TextFormat::RED . " is not banned from your island.");
						return true;
					}
				}else{

					$sender->sendMessage(TextFormat::RED . "You have not created a SkyBlock island yet.");
					return true;
				}
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

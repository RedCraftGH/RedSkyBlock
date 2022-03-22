<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Value{

	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onValueCommand(CommandSender $sender) : bool{

		if($sender->hasPermission("redskyblock.value")){

			$plugin = $this->plugin;
			$senderName = strtolower($sender->getName());
			$skyblockArray = $plugin->skyblock->get("SkyBlock", []);

			if(array_key_exists($senderName, $skyblockArray)){

				$filePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";
				$playerDataEncoded = file_get_contents($filePath);
				$playerData = (array) json_decode($playerDataEncoded);

				$sender->sendMessage(TextFormat::LIGHT_PURPLE . "Island Value: " . TextFormat::WHITE . $playerData["Value"]);
				return true;
			}else{

				$sender->sendMessage(TextFormat::RED . "You must create a SkyBlock island first.");
				return true;
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Members{

	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onMembersCommand(CommandSender $sender) : bool{

		if($sender->hasPermission("redskyblock.members")){

			$plugin = $this->plugin;
			$skyblockArray = $plugin->skyblock->get("SkyBlock", []);
			$senderName = strtolower($sender->getName());

			if(array_key_exists($senderName, $skyblockArray)){

				$filePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";
				$playerDataEncoded = file_get_contents($filePath);
				$playerData = (array) json_decode($playerDataEncoded);

				if(count($playerData["Island Members"]) <= 0){

					$members = "no members yet.";
				}else{

					$members = implode(", ", $playerData["Island Members"]);
				}

				$sender->sendMessage(TextFormat::LIGHT_PURPLE . "Members: " . TextFormat::WHITE . $members);
				return true;

			}else{

				$sender->sendMessage(TextFormat::RED . "You don't have a SkyBlock island yet.");
				return true;
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

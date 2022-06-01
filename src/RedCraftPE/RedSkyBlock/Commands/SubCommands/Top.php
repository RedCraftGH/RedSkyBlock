<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Top{
	
	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onTopCommand(CommandSender $sender) : bool{

		if($sender->hasPermission("redskyblock.top")){

			$plugin = $this->plugin;
			$skyblockArray = $plugin->skyblock->get("SkyBlock", []);
			$senderName = strtolower($sender->getName());

			if(array_key_exists($senderName, $skyblockArray)){

				$topArray = $plugin->getTopIslands();

				$sender->sendMessage(TextFormat::LIGHT_PURPLE . "||Top Islands||" . "\n" . TextFormat::WHITE . "#1: " . $topArray[0] . "\n" . "#2: " . $topArray[1] . "\n" . "#3: " . $topArray[2] . "\n" . "#4: " . $topArray[3] . "\n" . "#5: " . $topArray[4]);
				return true;
			}else{

				$sender->sendMessage(TextFormat::RED . "You don't have a skyblock island yet.");
				return true;
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

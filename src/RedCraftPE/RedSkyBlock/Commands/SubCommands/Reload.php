<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Reload{

	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onReloadCommand(CommandSender $sender) : bool{

		if($sender->hasPermission("redskyblock.reload")){

			$plugin = $this->plugin;
			$plugin->cfg->reload();
			$plugin->skyblock->reload();
			$sender->sendMessage(TextFormat::GREEN . "All SkyBlock data has been reloaded.");
			return true;
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

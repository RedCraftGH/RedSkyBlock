<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class SetWorld{

	public static $instance;

	public $plugin;

	public function __construct($plugin){

		self::$instance = $this;
		$this->plugin = $plugin;
	}

	public function onSetWorldCommand(CommandSender $sender, array $args) : bool{

		if($sender->hasPermission("redskyblock.setworld")){

			if(count($args) < 2){

				$sender->sendMessage(TextFormat::WHITE . "Usage: /island setworld <world name>");
				return true;
			}else{

				$plugin = $this->plugin;
				$world = implode(" ", array_splice($args, 1));

				if($plugin->skyblock->get("Master World") === $world){

					$sender->sendMessage(TextFormat::RED . "This world is already set as the SkyBlock base world.");
					return true;
				}else{

					$plugin->skyblock->set("Islands", 0);
					$plugin->skyblock->set("Master World", $world);
					$plugin->skyblock->save();
					$sender->sendMessage(TextFormat::GREEN . $world . " has been set as the SkyBlock Master world on this server.");
					return true;
				}
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
			return true;
		}
	}
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use pocketmine\player\GameMode;

class Teleport{

	public static $instance;
	
	public $plugin;

	public function __construct($plugin){

		self::$instance = $this;
		$this->plugin = $plugin;
	}

	public function onTeleportCommand(CommandSender $sender, array $args) : bool{

		if($sender->hasPermission("redskyblock.create") and $sender instanceof \pocketmine\player\Player){

			$plugin = $this->plugin;
			$masterWorld = $plugin->skyblock->get("Master World");

			if($masterWorld === false){

				$sender->sendMessage(TextFormat::RED . "You must set a SkyBlock world in order for this plugin to function properly.");
				return true;
			}
			$level = $plugin->getServer()->getWorldManager()->getWorldByName($masterWorld);
			if(!$level){

				$sender->sendMessage(TextFormat::RED . "The world currently set as the SkyBlock world does not exist.");
				return true;
			}

			$skyblockArray = $plugin->skyblock->get("SkyBlock", []);
			$islandSpawnY = $plugin->cfg->get("Island Spawn Y");
			$senderName = strtolower($sender->getName());

			if(count($args) < 2){

				if(array_key_exists($senderName, $skyblockArray)){

					$playerDataEncoded = file_get_contents($plugin->getDataFolder() . "Players/" . $senderName . ".json");
					$playerData = (array) json_decode($playerDataEncoded, true);

					$x = $playerData["Island Spawn"][0];
					$y = $playerData["Island Spawn"][1];
					$z = $playerData["Island Spawn"][2];

					if($sender->getGamemode() == GameMode::SURVIVAL()){

						$sender->setAllowFlight(false);
					}
					$sender->teleport(new Position($x, $y, $z, $level));
					$sender->sendMessage(TextFormat::GREEN . "You have been teleported to your island!");
					return true;
				}else{

					$sender->sendMessage(TextFormat::RED . "You have not created a SkyBlock island yet.");
					return true;
				}
			}else{

				$name = strtolower(implode(" ", array_slice($args, 1)));

				if(array_key_exists($name, $skyblockArray)){

					$playerDataEncoded = file_get_contents($plugin->getDataFolder() . "Players/" . $name . ".json");
					$playerData = (array) json_decode($playerDataEncoded, true);

					if($playerData["Island Locked"] === false || $senderName === $name || in_array($senderName, $playerData["Island Members"])){

						if(in_array($senderName, $playerData["Banned"])){

							$sender->sendMessage(TextFormat::WHITE . $name . TextFormat::RED . " has banned you from their island.");
							return true;
						}else{

							$x = $playerData["Island Spawn"][0];
							$y = $playerData["Island Spawn"][1];
							$z = $playerData["Island Spawn"][2];

							if($sender->getGamemode() == GameMode::SURVIVAL()){

								$sender->setAllowFlight(false);
							}
							$sender->teleport(new Position($x, $y, $z, $level));
							$sender->setFlying(false);
							$sender->setAllowFlight(false);
							$sender->sendMessage(TextFormat::GREEN . "You have been teleported to " . TextFormat::WHITE . $name . TextFormat::GREEN . "'s island.");
							return true;
						}
					}else{

						$sender->sendMessage(TextFormat::WHITE . $name . TextFormat::RED . " has locked their island.");
						return true;
					}
				}else{

					$sender->sendMessage(TextFormat::WHITE . implode(" ", array_slice($args, 1)) . TextFormat::RED . " does not have an island.");
					return true;
				}
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

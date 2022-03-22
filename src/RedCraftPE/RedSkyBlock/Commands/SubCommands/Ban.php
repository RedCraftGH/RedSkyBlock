<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class Ban{

	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onBanCommand(CommandSender $sender, array $args) : bool{

		if($sender->hasPermission("redskyblock.ban")){

			if(count($args) < 2){

				$sender->sendMessage(TextFormat::WHITE . "Usage: /is ban <player>");
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

						$sender->sendMessage(TextFormat::WHITE . $name . TextFormat::RED . " is already banned from your island.");
						return true;
					}else{

						if($name !== $senderName && $playerFromName !== $sender){

							if(in_array($name, $playerData["Island Members"])){

								$key = array_search($name, $playerData["Island Members"]);
								unset($playerData["Island Members"][$key]);
							}

							$playerData["Banned"][] = $name;
							$playerDataEncoded = json_encode($playerData);
							file_put_contents($filePath, $playerDataEncoded);
							$sender->sendMessage(TextFormat::WHITE . $name . TextFormat::GREEN . " is now banned from your island.");

							if($playerFromName instanceof Player){

								$playerFromName->sendMessage(TextFormat::WHITE . $senderName . TextFormat::RED . " has banned you from their island.");

								if($plugin->getIslandAtPlayer($playerFromName) === $senderName){

									$playerFromName->teleport($plugin->getServer()->getWorldManager()->getDefaultWorld()->getSafeSpawn());
								}
							}
							return true;
						}elseif($name === $senderName || $playerFromName === $sender){

							$sender->sendMessage(TextFormat::RED . "You cannot ban yourself from your island.");
							return true;
						}
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
		return true;
	}
}

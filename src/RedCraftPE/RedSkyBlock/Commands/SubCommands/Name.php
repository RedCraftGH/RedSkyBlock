<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Name{

	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onNameCommand(CommandSender $sender, array $args) : bool{

		if($sender->hasPermission("redskyblock.name")){

			$plugin = $this->plugin;
			$skyblockArray = $plugin->skyblock->get("SkyBlock", []);
			$senderName = strtolower($sender->getName());

			if(array_key_exists($senderName, $skyblockArray)){

				$filePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";
				$playerDataEncoded = file_get_contents($filePath);
				$playerData = (array) json_decode($playerDataEncoded);

				if(count($args) < 2){

					$sender->sendMessage(TextFormat::WHITE . "Your island is named " . TextFormat::GREEN . $playerData["Name"]);
					return true;
				}else{

					$islandName = implode(" ", array_slice($args, 1));
					if(strlen($islandName) > 21){

						$sender->sendMessage(TextFormat::RED . "This island name is too long.");
						return true;
					}
					$playerData["Name"] = $islandName;
					$playerDataEncoded = json_encode($playerData);
					file_put_contents($filePath, $playerDataEncoded);
					$sender->sendMessage(TextFormat::GREEN . "Your island's name has been changed to " . TextFormat::LIGHT_PURPLE . $islandName);

					$scoreHud = $plugin->getServer()->getPluginManager()->getPlugin("ScoreHud");
					if($scoreHud !== null && $scoreHud->isEnabled()){

						$ev = new PlayerTagUpdateEvent(
							$sender,
							new ScoreTag("redskyblock.islename", strval($islandName))
						);
						$ev->call();
					}
					return true;
				}
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

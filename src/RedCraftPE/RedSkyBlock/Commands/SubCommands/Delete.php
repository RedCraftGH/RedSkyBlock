<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class Delete{

	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onDeleteCommand(CommandSender $sender, array $args) : bool{

		if($sender->hasPermission("redskyblock.delete")){

			if(count($args) < 2){

				$sender->sendMessage(TextFormat::WHITE . "Usage: /is delete <player>");
				return true;
			}else{

				$playerName = strtolower(implode(" ", array_slice($args, 1)));
				$plugin = $this->plugin;
				$player = $plugin->getServer()->getPlayerByPrefix($playerName);
				$skyblockArray = $plugin->skyblock->get("SkyBlock", []);

				if($player instanceof Player){

					$scoreHud = $plugin->getServer()->getPluginManager()->getPlugin("ScoreHud");
					if($scoreHud !== null && $scoreHud->isEnabled()){

						$ev1 = new PlayerTagUpdateEvent(
							$player,
							new ScoreTag("redskyblock.islename", "N/A")
						);
						$ev2 = new PlayerTagUpdateEvent(
							$player,
							new ScoreTag("redskyblock.islesize", "N/A")
						);
						$ev3 = new PlayerTagUpdateEvent(
							$player,
							new ScoreTag("redskyblock.islevalue", "N/A")
						);
						$ev4 = new PlayerTagUpdateEvent(
							$player,
							new ScoreTag("redskyblock.islerank", "N/A")
						);
						$ev5 = new PlayerTagUpdateEvent(
							$player,
							new ScoreTag("redskyblock.islestatus", "N/A")
						);
						$ev6 = new PlayerTagUpdateEvent(
							$player,
							new ScoreTag("redskyblock.membercount", "N/A")
						);
						$ev1->call();
						$ev2->call();
						$ev3->call();
						$ev4->call();
						$ev5->call();
						$ev6->call();
					}
					$player->sendMessage(TextFormat::RED . "Your island has been deleted by a server administrator.");

					if(($player->getWorld()->getFolderName() === $plugin->skyblock->get("Master World")) && ($plugin->getIslandAtPlayer($player) === $playerName)){

						$player->teleport($plugin->getServer()->getDefaultLevel()->getSafeSpawn());
					}
				}

				if(array_key_exists($playerName, $skyblockArray)){

					$filePath = $plugin->getDataFolder() . "Players/" . $playerName . ".json";

					unset($skyblockArray[$playerName]);
					unlink($filePath);

					$plugin->skyblock->set("SkyBlock", $skyblockArray);
					$plugin->skyblock->save();
					$sender->sendMessage(TextFormat::GREEN . "You have successfully deleted " . TextFormat::WHITE . $playerName . "'s" . TextFormat::GREEN . " island.");
					return true;
				}else{

					$sender->sendMessage(TextFormat::WHITE . $playerName . TextFormat::RED . " does not have an island to delete.");
					return true;
				}
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

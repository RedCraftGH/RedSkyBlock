<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use RedCraftPE\RedSkyBlock\Tasks\Generate;

class Create{

	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onCreateCommand(CommandSender $sender) : bool{

		if($sender->hasPermission("redskyblock.create") and $sender instanceof Player){

			$plugin = $this->plugin;
			$itemsArray = $plugin->cfg->get("Starting Items", []);
			$interval = $plugin->cfg->get("Island Interval");
			$initialSize = $plugin->cfg->get("Island Size");
			$islandSpawnY = $plugin->cfg->get("Island Spawn Y");
			$levelName = $plugin->skyblock->get("Master World");
			$skyblockArray = $plugin->skyblock->get("SkyBlock", []);
			$senderName = strtolower($sender->getName());

			if($levelName === false){

				$sender->sendMessage(TextFormat::RED . "You must set a SkyBlock Master world in order for this plugin to function properly.");
				return true;
			}else{

				$level = $plugin->getServer()->getWorldManager()->getWorldByName($levelName);
				if(!$plugin->getServer()->getWorldManager()->isWorldLoaded($levelName)){

					if($plugin->getServer()->getWorldManager()->loadWorld($levelName)){

						$plugin->getServer()->getWorldManager()->loadWorld($levelName);
					}else{

						$sender->sendMessage(TextFormat::RED . "The world currently set as the SkyBlock world does not exist or can't be loaded.");
						return true;
					}
				}

				if(array_key_exists($senderName, $skyblockArray)){

					$sender->sendMessage(TextFormat::RED . "You already have a Skyblock island.");
					return true;
				}else{

					if($plugin->skyblock->get("Zone Created")){

						$world = $plugin->getServer()->getWorldManager()->getWorldByName($levelName);
						$turns = $plugin->skyblock->get("Turns");
						$steps = $plugin->skyblock->get("Steps");
						$stepChecker = $plugin->skyblock->get("Step Checker");
						$lastX = $plugin->skyblock->get("Last X"); //starts at 0
						$lastZ = $plugin->skyblock->get("Last Z"); //starts at 0 , coords: 0, y, 0
						$dir = 0; //in future need to build algorithm to test for blocks at interval to create the skyblock island generation pattern
						$cooldown = $plugin->cfg->get("Reset Cooldown");

						if($steps === -1){

							$lastX += $interval;
							$steps = 1;
						}else{

							if($steps === $stepChecker){

								$turns++;
								$steps = 0;
								if($turns % 2 === 0){

									$stepChecker++;
								}

								$dir = intval($turns - ((floor($turns / 4)) * 4));
							}else{

								$dir = intval($turns - ((floor($turns / 4)) * 4));
							}
							if($dir === 0){

								$lastX += $interval;
								$steps++;
							}elseif($dir === 1){

								$lastZ += $interval;
								$steps++;
							}elseif($dir === 2){

								$lastX -= $interval;
								$steps++;
							}elseif($dir === 3){

								$lastZ -= $interval;
								$steps++;
							}
						}

						$cSpawnVals = $plugin->skyblock->get("CSpawnVals", []);

						$spawnX = $lastX + $cSpawnVals[0];
						$spawnY = $cSpawnVals[1];
						$spawnZ = $lastZ + $cSpawnVals[2];
						if($sender instanceof Player){
							$sender->teleport(new Position($spawnX, $spawnY, $spawnZ, $world));
							$sender->setImmobile(true);

							$plugin->getScheduler()->scheduleDelayedTask(new Generate($plugin, $sender, $lastX, $lastZ, $world), 50);

							foreach($itemsArray as $items){

								if(count($itemsArray) > 0){

									$itemArray = explode(" ", $items);
									if(count($itemArray) === 3){

										$id = intval($itemArray[0]);
										$damage = intval($itemArray[1]);
										$count = intval($itemArray[2]);
										$sender->getInventory()->addItem(ItemFactory::getInstance()->get($id, $damage, $count));
									}
								}
							}
						}

						$playerData = [
							"Island Members" => [],
							"Name" => $sender->getName() . "'s island",
							"Value" => 0,
							"Island Spawn" => [$spawnX, $spawnY, $spawnZ],
							"Nether Spawn" => [],
							"Island Size" => $initialSize,
							"Cooldown" => Time() + $cooldown,
							"Island Locked" => false,
							"Banned" => []
						];

						if(file_put_contents($plugin->getDataFolder() . "Players/" . $senderName . ".json", json_encode($playerData)) !== false){

							$sender->sendMessage(TextFormat::GREEN . "You have successfully created your skyblock island.");
						}else{

							$plugin->getLogger()->info(TextFormat::RED . "Error: {$sender->getName()}'s player files were not successfully generated.");
						}

						$skyblockArray[$senderName] = [$spawnX, $spawnZ]; //Necessary for event listener boundaries and api functions
						$plugin->skyblock->set("SkyBlock", $skyblockArray);
						$plugin->skyblock->set("Steps", $steps); //take out after new island generation algorithm in place?
						$plugin->skyblock->set("Turns", $turns); //take out after new island generation algorithm in place?
						$plugin->skyblock->set("Step Checker", $stepChecker);
						$plugin->skyblock->set("Last X", $lastX);
						$plugin->skyblock->set("Last Z", $lastZ);
						$plugin->skyblock->set("Islands", intval($plugin->skyblock->get("Islands")) + 1);
						$plugin->skyblock->save();

						$scoreHud = $plugin->getServer()->getPluginManager()->getPlugin("ScoreHud");
						if($scoreHud !== null && $scoreHud->isEnabled()){

							$ev1 = new PlayerTagUpdateEvent(
								$sender,
								new ScoreTag("redskyblock.islename", $playerData["Name"])
							);
							$ev2 = new PlayerTagUpdateEvent(
								$sender,
								new ScoreTag("redskyblock.islesize", $playerData["Island Size"])
							);
							$ev3 = new PlayerTagUpdateEvent(
								$sender,
								new ScoreTag("redskyblock.islevalue", $playerData["Value"])
							);
							$ev4 = new PlayerTagUpdateEvent(
								$sender,
								new ScoreTag("redskyblock.rank", "#" . $plugin->getIslandRank($sender))
							);
							$ev5 = new PlayerTagUpdateEvent(
								$sender,
								new ScoreTag("redskyblock.islestatus", "Unlocked")
							);
							$ev6 = new PlayerTagUpdateEvent(
								$sender,
								new ScoreTag("redskyblock.membercount", "0")
							);
							$ev1->call();
							$ev2->call();
							$ev3->call();
							$ev4->call();
							$ev5->call();
							$ev6->call();
						}
						return true;
					}else{

						$sender->sendMessage(TextFormat::RED . "A custom island zone must be created before islands can be generated.");
						return true;
					}
				}
			}
		}else{

			$sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
			return true;
		}
	}
}

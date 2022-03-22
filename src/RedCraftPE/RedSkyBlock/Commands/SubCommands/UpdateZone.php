<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class UpdateZone{
	
	public $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
	}

	public function onUpdateZoneCommand(CommandSender $sender, array $args) : bool{

		if($sender->hasPermission("redskyblock.updatezone")){

			$plugin = $this->plugin;
			$islandZone = $plugin->cfg->get("Island Zone", []);

			if($islandZone[0] === 0 && $islandZone[1] === 0 && $islandZone[2] === 0){

				$sender->sendMessage(TextFormat::RED . "You must set position 1 of your custom island zone first.");
				return true;
			}else{

				if($islandZone[3] === 0 && $islandZone[4] === 0 && $islandZone[5] === 0){

					$sender->sendMessage(TextFormat::RED . "You must set position 2 of your custom island zone first.");
					return true;
				}else{

					$cSpawnVals = $plugin->skyblock->get("CSpawnVals", []);
					if(empty($cSpawnVals)){

						$sender->sendMessage(TextFormat::RED . "You must set a custom island main spawn point first!");
						return true;
					}else{

						$plugin->getServer()->getWorldManager()->loadWorld($plugin->cfg->get("Zone World"));
						$zoneWorldName = $plugin->cfg->get("Zone World");
						$zoneWorld = $plugin->getServer()->getWorldManager()->getWorldByName($zoneWorldName);

						if(count($args) < 2){

							$islandBlocks = [];
							$x1 = (int) $islandZone[0];
							$y1 = (int) $islandZone[1];
							$z1 = (int) $islandZone[2];
							$x2 = (int) $islandZone[3];
							$y2 = (int) $islandZone[4];
							$z2 = (int) $islandZone[5];

							for($x = min($x1, $x2); $x <= max($x1, $x2); $x++){

								for($y = min($y1, $y2); $y <= max($y1, $y2); $y++){

									for($z = min($z1, $z2); $z <= max($z1, $z2); $z++){
										$zoneWorld->loadChunk($x, $z);
										$block = $zoneWorld->getBlockAt((int) $x, (int) $y, (int) $z, true, false);
										$blockID = $block->getID();
										$blockDamage = $block->getMeta();

										array_push($islandBlocks, $blockID . " " . $blockDamage);
									}
								}
							}
							$plugin->skyblock->set("Island Blocks", $islandBlocks);
							$plugin->skyblock->set("Zone Created", true);
						}elseif(strtolower($args[1]) === "nether"){

							$netherZone = $plugin->cfg->get("Nether Zone", []);
							$netherBlocks = [];
							$x1 = (int) $netherZone[0];
							$y1 = (int) $netherZone[1];
							$z1 = (int) $netherZone[2];
							$x2 = (int) $netherZone[3];
							$y2 = (int) $netherZone[4];
							$z2 = (int) $netherZone[5];

							for($x = min($x1, $x2); $x <= max($x1, $x2); $x++){

								for($y = min($y1, $y2); $y <= max($y1, $y2); $y++){

									for($z = min($z1, $z2); $z <= max($z1, $z2); $z++){
										$zoneWorld->loadChunk($x, $z);
										$block = $zoneWorld->getBlockAt((int) $x, (int) $y, (int) $z, true, false);
										$blockID = $block->getID();
										$blockDamage = $block->getMeta();

										array_push($netherBlocks, $blockID . " " . $blockDamage);
									}
								}
							}
							$plugin->skyblock->set("Nether Blocks", $netherBlocks);
							$plugin->skyblock->set("Nether Zone Created", true);
						}else{

							$sender->sendMessage(TextFormat::WHITE . "Usage: /is updatezone [nether]");
							return true;
						}
						$plugin->cfg->save();
						$plugin->skyblock->save();
						$sender->sendMessage(TextFormat::GREEN . "The Island Zone has been updated.");
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

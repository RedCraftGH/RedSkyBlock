<?php
declare(strict_types=1);

namespace RedCraftPE\RedSkyBlock;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use RedCraftPE\RedSkyBlock\Blocks\Lava;
use RedCraftPE\RedSkyBlock\Commands\Island;
use RedCraftPE\RedSkyBlock\Commands\Spawn;

class SkyBlock extends PluginBase{

	protected EventListener $eventListener;
	public Config $skyblock;
	public Island $island;
	public Spawn $spawn;
	public Config $cfg;
	public ScoreboardListener $scoreboardListener;

	public function onEnable() : void{

		$this->eventListener = new EventListener($this);
		$this->island = new Island($this);
		$this->spawn = new Spawn($this);
		if(!BlockFactory::getInstance()->isRegistered(BlockLegacyIds::FLOWING_LAVA)){
			BlockFactory::getInstance()->register(new Lava(0, $this, new BlockIdentifier(BlockLegacyIds::FLOWING_LAVA, 0)));
		}

		if(!file_exists($this->getDataFolder() . "skyblock.json")){

			$this->saveResource("skyblock.json");
		}
		if(!file_exists($this->getDataFolder() . "config.yml")){

			$this->saveResource("config.yml");
		}
		if(!file_exists($this->getDataFolder() . "Players")){

			mkdir($this->getDataFolder() . "Players");
		}

		$this->skyblock = new Config($this->getDataFolder() . "skyblock.json", Config::JSON);
		$this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->cfg->reload();
		$this->skyblock->reload();

		//check if ScoreHUD is available
		$scoreHud = $this->getServer()->getPluginManager()->getPlugin("ScoreHud");
		if($scoreHud instanceof Plugin){

			$this->scoreboardListener = new ScoreboardListener($this);
		}

		if($this->skyblock->get("Master World") === false){

			$this->getLogger()->info(TextFormat::RED . "In order for this plugin to function properly, you must set a Skyblock Master world in your server.");
			$masterWorld = false;
		}else{

			if($this->getServer()->getWorldManager()->loadWorld($this->skyblock->get("Master World"))){

				$this->getServer()->getWorldManager()->loadWorld($this->skyblock->get("Master World"));
				if($this->cfg->get("Nether Islands")){

					$this->getServer()->getWorldManager()->loadWorld($this->skyblock->get("Master World") . "-Nether");
				}
			}else{

				$this->getLogger()->info(TextFormat::RED . "Error: Unable to load the Skyblock Master world.");
			}

			$masterWorld = $this->getServer()->getWorldManager()->getWorldByName($this->skyblock->get("Master World"));
			if(!$masterWorld){

				$this->getLogger()->info(TextFormat::RED . "The level currently set as the SkyBlock Master world does not exist.");
				$masterWorld = null;
			}else{

				$this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on the Master world {$masterWorld->getFolderName()}");
			}
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{

		if($sender instanceof Player){

			switch(strtolower($command->getName())){

				case "island":

					return $this->island->onIslandCommand($sender, $command, $label, $args);
				case "spawn":

					return $this->spawn->onSpawnCommand($sender, $command, $label, $args);
			}
		}else{

			$this->getLogger()->info(TextFormat::RED . "You can only use this command in the game.");
			return true;
		}
		return false;
	}

	//api functions

	public function getIslandName(Player $player) : string{

		$playerName = strtolower($player->getName());
		$filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
		if(!file_exists($filePath)){

			return "N/A";
		}
		$playerDataEncoded = file_get_contents($filePath);
		$playerData = (array) json_decode($playerDataEncoded);

		return $playerData["Name"];
	}

	public function getMasterWorld() : string{

		if($this->skyblock->get("Master World") !== false){

			return $this->skyblock->get("Master World");
		}else{

			return "N/A";
		}
	}

	public function getNetherWorld() : string{
		if($this->skyblock->get("Master World") !== false){

			return $this->skyblock->get("Master World") . "-Nether";
		}else{

			return "N/A";
		}
	}

	public function getIslandSize(Player $player){

		$playerName = strtolower($player->getName());
		$filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
		if(!file_exists($filePath)){

			return "N/A";
		}
		$playerDataEncoded = file_get_contents($filePath);
		$playerData = (array) json_decode($playerDataEncoded);

		return $playerData["Island Size"];
	}

	public function isIslandLocked(Player $player){

		$playerName = strtolower($player->getName());
		$filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
		if(!file_exists($filePath)){

			return "N/A";
		}
		$playerDataEncoded = file_get_contents($filePath);
		$playerData = (array) json_decode($playerDataEncoded);

		return $playerData["Island Locked"];
	}

	public function getIslandMembers(Player $player){

		$playerName = strtolower($player->getName());
		$filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
		if(!file_exists($filePath)){

			return "N/A";
		}
		$playerDataEncoded = file_get_contents($filePath);
		$playerData = (array) json_decode($playerDataEncoded);

		return $playerData["Island Members"];
	}

	public function getIslandBanned(player $player){

		$playerName = strtolower($player->getName());
		$filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
		if(!file_exists($filePath)){

			return "N/A";
		}
		$playerDataEncoded = file_get_contents($filePath);
		$playerData = (array) json_decode($playerDataEncoded);

		return $playerData["Banned"];
	}

	public function getIslandSpawn(Player $player){

		$playerName = strtolower($player->getName());
		$filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
		if(!file_exists($filePath)){

			return "N/A";
		}
		$playerDataEncoded = file_get_contents($filePath);
		$playerData = (array) json_decode($playerDataEncoded);

		return $playerData["Island Spawn"];
	}

	public function getNetherSpawn(Player $player){

		$playerName = strtolower($player->getName());
		$filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
		if(!file_exists($filePath)){

			return "N/A";
		}
		$playerDataEncoded = file_get_contents($filePath);
		$playerData = (array) json_decode($playerDataEncoded);

		if($playerData["Nether Spawn"] !== []){

			return $playerData["Nether Spawn"];
		}
	}

	public function getIslandValue(Player $player){

		$playerName = strtolower($player->getName());
		$filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
		if(!file_exists($filePath)){

			return "N/A";
		}
		$playerDataEncoded = file_get_contents($filePath);
		$playerData = (array) json_decode($playerDataEncoded);

		return $playerData["Value"];
	}

	public function getIslandRank(Player $player){

		$playerName = strtolower($player->getName());
		$skyblockArray = $this->skyblock->get("SkyBlock", []);

		$valueArray = [];

		foreach($skyblockArray as $players => $data){

			$filePath = $this->getDataFolder() . "Players/" . $players . ".json";
			$playerDataEncoded = file_get_contents($filePath);
			$playerData = (array) json_decode($playerDataEncoded);
			$valueArray[$players] = $playerData["Value"];
		}

		arsort($valueArray);
		if(!(isset($valueArray[$playerName]))){

			return "N/A";
		}
		$offset = array_search($playerName, array_keys($valueArray)) + 1;
		return $offset;
	}

	public function getTopIslands() : array{

		$valueArray = [];

		$skyblockArray = $this->skyblock->get("SkyBlock", []);

		foreach($skyblockArray as $player => $data){

			$filePath = $this->getDataFolder() . "Players/" . $player . ".json";
			$playerDataEncoded = file_get_contents($filePath);
			$playerData = (array) json_decode($playerDataEncoded);
			$valueArray[$player] = $playerData["Value"];
		}

		arsort($valueArray);

		$counter = 0;
		$top1 = "N/A";
		$top2 = "N/A";
		$top3 = "N/A";
		$top4 = "N/A";
		$top5 = "N/A";
		foreach($valueArray as $player => $value){

			$filePath = $this->getDataFolder() . "Players/" . $player . ".json";
			$playerDataEncoded = file_get_contents($filePath);
			$playerData = (array) json_decode($playerDataEncoded);

			$counter++;
			if($counter === 1){

				$top1 = $playerData["Name"] . " -- " . $value . " value";
			}elseif($counter === 2){

				$top2 = $playerData["Name"] . " -- " . $value . " value";
			}elseif($counter === 3){

				$top3 = $playerData["Name"] . " -- " . $value . " value";
			}elseif($counter === 4){

				$top4 = $playerData["Name"] . " -- " . $value . " value";
			}elseif($counter === 5){

				$top5 = $playerData["Name"] . " -- " . $value . " value";
			}
		}
		return [$top1, $top2, $top3, $top4, $top5];
	}

	public function getIslandAtPlayer(Player $player){

		$skyblockArray = $this->skyblock->get("SkyBlock", []);
		$owner = null;

		foreach($skyblockArray as $owner => $spawnArray){

			$filePath = $this->getDataFolder() . "Players/" . $owner . ".json";
			$playerDataEncoded = file_get_contents($filePath);
			$playerData = (array) json_decode($playerDataEncoded);
			$islandSize = $playerData["Island Size"];

			$x = $player->getPosition()->getX();
			$z = $player->getPosition()->getZ();

			$ownerX = $spawnArray[0];
			$ownerZ = $spawnArray[1];

			if(($x > $ownerX - ($islandSize / 2) && $z > $ownerZ - ($islandSize / 2)) && ($x < $ownerX + ($islandSize / 2) && $z < $ownerZ + ($islandSize / 2))){

				return $owner;
			}else{

				$owner = null;
			}
		}
		return $owner;
	}

	public function getIslandAtBlock(Block $block){

		$skyblockArray = $this->skyblock->get("SkyBlock", []);
		$owner = null;

		foreach($skyblockArray as $owner => $spawnArray){

			$filePath = $this->getDataFolder() . "Players/" . $owner . ".json";
			$playerDataEncoded = file_get_contents($filePath);
			$playerData = (array) json_decode($playerDataEncoded);
			$islandSize = $playerData["Island Size"];

			$x = $block->getPosition()->getX();
			$z = $block->getPosition()->getZ();

			$ownerX = $spawnArray[0];
			$ownerZ = $spawnArray[1];

			if(($x > $ownerX - ($islandSize / 2) && $z > $ownerZ - ($islandSize / 2)) && ($x < $ownerX + ($islandSize / 2) && $z < $ownerZ + ($islandSize / 2))){
				return $owner;
			}else{
				$owner = null;
			}
		}
		return $owner;
	}

	public function getPlayersAtIsland(Player $player) : array{

		$skyblockArray = $this->skyblock->get("SkyBlock", []);
		$onlinePlayers = $this->getServer()->getOnlinePlayers();
		$onIsland = [];
		$playerName = strtolower($player->getName());
		$filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
		$playerDataEncoded = file_get_contents($filePath);
		$playerData = (array) json_decode($playerDataEncoded);
		$islandSize = $playerData["Island Size"];

		foreach($onlinePlayers as $p){

			$px = $p->getPosition()->getX();
			$pz = $p->getPosition()->getZ();
			$pWorld = $p->getPosition()->getWorld();

			if($pWorld->getFolderName() === $this->skyblock->get("Master World") || $pWorld->getFolderName() === $this->skyblock->get("Master World") . "-Nether"){

				if(($px > $skyblockArray[$playerName][0] - ($islandSize / 2) && $pz > $skyblockArray[$playerName][1] - ($islandSize / 2)) && ($px < $skyblockArray[$playerName][0] + ($islandSize / 2) && $pz < $skyblockArray[$playerName][1] + ($islandSize / 2))){

					$onIsland[] = strtolower($p->getName());
				}
			}
		}
		return $onIsland;
	}
}

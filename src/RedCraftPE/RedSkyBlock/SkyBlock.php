<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\block\BlockFactory;
use pocketmine\Player;
use pocketmine\block\Block;

use RedCraftPE\RedSkyBlock\Commands\Island;
use RedCraftPE\RedSkyBlock\Commands\Spawn;
use RedCraftPE\RedSkyBlock\Tasks\Generate;
use RedCraftPE\RedSkyBlock\Blocks\Lava;


use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\InvMenuEventHandler;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\MenuIds;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;


class SkyBlock extends PluginBase {

  private $eventListener;

  private $island;
  private $spawn;

  public function onEnable(): void {

    $this->eventListener = new EventListener($this);
    $this->island = new Island($this);
    $this->spawn = new Spawn($this);
    BlockFactory::registerBlock(new Lava(0, $this), true);

    if (!file_exists($this->getDataFolder() . "skyblock.json")) {

      $this->saveResource("skyblock.json");
    }
    if (!file_exists($this->getDataFolder() . "config.yml")) {

      $this->saveResource("config.yml");
    }
    if (!file_exists($this->getDataFolder() . "Players")) {

      mkdir($this->getDataFolder() . "Players");

      
    }

    if(!InvMenuHandler::isRegistered()){
      InvMenuHandler::register($this);
   }
    $guicfg = new Config($this->getDataFolder() . "gui.yml", Config::YAML);
		$title1 = $guicfg->get("title");
	    $title2 = str_replace(["&", "+n",], ["§", "\n"], $title1);

    $this->skyblock = new Config($this->getDataFolder() . "skyblock.json", Config::JSON);
    $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    $this->cfg->reload();
    $this->skyblock->reload();

    if ($this->skyblock->get("Master World") === false) {

      $this->getLogger()->info(TextFormat::RED . "In order for this plugin to function properly, you must set a Skyblock Master world in your server.");
      $masterWorld = false;
    } else {

      if ($this->getServer()->loadLevel($this->skyblock->get("Master World"))) {

        $this->getServer()->loadLevel($this->skyblock->get("Master World"));
        if ($this->cfg->get("Nether Islands")) {

          $this->getServer()->loadLevel($this->skyblock->get("Master World") . "-Nether");
        }
      } else {

        $this->getLogger()->info(TextFormat::RED . "Error: Unable to load the Skyblock Master world.");
      }

      $masterWorld = $this->getServer()->getLevelByName($this->skyblock->get("Master World"));
      if (!$masterWorld) {

        $this->getLogger()->info(TextFormat::RED . "The level currently set as the SkyBlock Master world does not exist.");
        $masterWorld = null;
      } else {

        $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on the Master world {$masterWorld->getFolderName()}");
      }
    }
  }

  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

    if ($sender instanceof Player) {

      switch(strtolower($command->getName())) {

        case "island":

          return $this->island->onIslandCommand($sender, $command, $label, $args);
        break;
        case "spawn":

          return $this->spawn->onSpawnCommand($sender, $command, $label, $args);
        break;
      }
    } else {

      $this->getLogger()->info(TextFormat::RED . "You can only use this command in the game.");
      return true;
    }
    return false;
  }

  //api

  public function getMasterWorld(): string {

    if ($this->skyblock->get("Master World") !== false) {

      return $this->skyblock->get("Master World");
    } else {

      return "N/A";
    }
  }

  public function getNetherWorld(): string {
    if ($this->skyblock->get("Master World") !== false) {

      return $this->skyblock->get("Master World") . "-Nether";
    } else {

      return "N/A";
    }
  }

  public function getIslandSize(Player $player): int {

    $playerName = strtolower($player->getName());
    $filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
    $playerDataEncoded = file_get_contents($filePath);
    $playerData = (array) json_decode($playerDataEncoded);

    return $playerData["Island Size"];
  }

  public function isIslandLocked(Player $player): bool {

    $playerName = strtolower($player->getName());
    $filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
    $playerDataEncoded = file_get_contents($filePath);
    $playerData = (array) json_decode($playerDataEncoded);

    return $playerData["Island Locked"];
  }

  public function getIslandMembers(Player $player): array {

    $playerName = strtolower($player->getName());
    $filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
    $playerDataEncoded = file_get_contents($filePath);
    $playerData = (array) json_decode($playerDataEncoded);

    return $playerData["Island Members"];
  }

  public function getIslandBanned(player $player): array {

    $playerName = strtolower($player->getName());
    $filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
    $playerDataEncoded = file_get_contents($filePath);
    $playerData = (array) json_decode($playerDataEncoded);

    return $playerData["Banned"];
  }

  public function getIslandSpawn(Player $player): array {

    $playerName = strtolower($player->getName());
    $filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
    $playerDataEncoded = file_get_contents($filePath);
    $playerData = (array) json_decode($playerDataEncoded);

    return $playerData["Island Spawn"];
  }

  public function getNetherSpawn(Player $player): array {

    $playerName = strtolower($player->getName());
    $filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
    $playerDataEncoded = file_get_contents($filePath);
    $playerData = (array) json_decode($playerDataEncoded);

    if ($playerData["Nether Spawn"] !== []) {

      return $playerData["Nether Spawn"];
    }
  }

  public function getIslandValue(Player $player): int {

    $playerName = strtolower($player->getName());
    $filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
    $playerDataEncoded = file_get_contents($filePath);
    $playerData = (array) json_decode($playerDataEncoded);

    return $playerData["Value"];
  }

  public function getIslandRank(Player $player): int {

    $playerName = strtolower($player->getName());
    $skyblockArray = $this->skyblock->get("SkyBlock", []);

      $valueArray = [];

      foreach($skyblockArray as $players => $data) {

        $filePath = $this->getDataFolder() . "Players/" . $players . ".json";
        $playerDataEncoded = file_get_contents($filePath);
        $playerData = (array) json_decode($playerDataEncoded);
        $valueArray[$players] = $playerData["Value"];
      }

      arsort($valueArray);
      $offset = array_search($playerName, array_keys($valueArray)) + 1;
      return $offset;
  }

  public function getTopIslands(): array {

    $valueArray = [];

    $skyblockArray = $this->skyblock->get("SkyBlock", []);

    foreach($skyblockArray as $player => $data) {

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
    foreach ($valueArray as $player => $value) {

      $filePath = $this->getDataFolder() . "Players/" . $player . ".json";
      $playerDataEncoded = file_get_contents($filePath);
      $playerData = (array) json_decode($playerDataEncoded);

      $counter++;
      if ($counter === 1) {

        $top1 = $playerData["Name"] . " -- " . $value . " value";
      } elseif ($counter === 2) {

        $top2 = $playerData["Name"] . " -- " . $value . " value";
      } elseif ($counter === 3) {

        $top3 = $playerData["Name"] . " -- " . $value . " value";
      } elseif ($counter === 4) {

        $top4 = $playerData["Name"] . " -- " . $value . " value";
      } elseif ($counter === 5) {

        $top5 = $playerData["Name"] . " -- " . $value . " value";
      }
    }
    return [$top1, $top2, $top3, $top4, $top5];
  }

  public function getIslandAtPlayer(Player $player) {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $owner = null;

    foreach ($skyblockArray as $owner => $spawnArray) {

      $filePath = $this->getDataFolder() . "Players/" . $owner . ".json";
      $playerDataEncoded = file_get_contents($filePath);
      $playerData = (array) json_decode($playerDataEncoded);
      $islandSize = $playerData["Island Size"];

      $x = $player->getX();
      $z = $player->getZ();

      $ownerX = $spawnArray[0];
      $ownerZ = $spawnArray[1];

      if (($x > $ownerX - ($islandSize / 2) && $z > $ownerZ - ($islandSize / 2)) && ($x < $ownerX + ($islandSize / 2) && $z < $ownerZ + ($islandSize / 2))) {

        return $owner;
      } else {

        $owner = null;
      }
    }
    return $owner;
  }

  public function getIslandAtBlock(Block $block) {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $owner = null;

    foreach ($skyblockArray as $owner => $spawnArray) {

      $filePath = $this->getDataFolder() . "Players/" . $owner . ".json";
      $playerDataEncoded = file_get_contents($filePath);
      $playerData = (array) json_decode($playerDataEncoded);
      $islandSize = $playerData["Island Size"];

      $x = $block->getX();
      $z = $block->getZ();

      $ownerX = $spawnArray[0];
      $ownerZ = $spawnArray[1];

      if (($x > $ownerX - ($islandSize / 2) && $z > $ownerZ - ($islandSize / 2)) && ($x < $ownerX + ($islandSize / 2) && $z < $ownerZ + ($islandSize / 2))) {

        return $owner;
      } else {

        $owner = null;
      }
    }
    return $owner;
  }

  public function getPlayersAtIsland(Player $player): array {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $onlinePlayers = $this->getServer()->getOnlinePlayers();
    $onIsland = [];
    $playerName = strtolower($player->getName());
    $filePath = $this->getDataFolder() . "Players/" . $playerName . ".json";
    $playerDataEncoded = file_get_contents($filePath);
    $playerData = (array) json_decode($playerDataEncoded);
    $islandSize = $playerData["Island Size"];

    foreach ($onlinePlayers as $p) {

      $px = $p->getX();
      $pz = $p->getZ();
      $pWorld = $p->getLevel();

      if ($pWorld->getFolderName() === $this->skyblock->get("Master World") || $pWorld->getFolderName() === $this->skyblock->get("Master World") . "-Nether") {

        if (($px > $skyblockArray[$playerName][0] - ($islandSize / 2) && $pz > $skyblockArray[$playerName][1] - ($islandSize / 2)) && ($px < $skyblockArray[$playerName][0] + ($islandSize / 2) && $pz < $skyblockArray[$playerName][1] + ($islandSize / 2))) {

          array_push($onIsland, strtolower($p->getName()));
        }
      }
    }
    return $onIsland;
  }
}

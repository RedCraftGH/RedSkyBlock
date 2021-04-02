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

  public function getIslandAtPlayer(Player $player) {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $islandSize = $this->cfg->get("Island Size");

    foreach ($skyblockArray as $owner => $spawnArray) {

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
    $islandSize = $this->cfg->get("Island Size");

    foreach ($skyblockArray as $owner => $spawnArray) {

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
    $islandSize = $this->cfg->get("Island Size");
    $onlinePlayers = $this->getServer()->getOnlinePlayers();
    $onIsland = [];
    $playerName = strtolower($player->getName());

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

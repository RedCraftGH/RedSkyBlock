<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\block\BlockFactory;
use pocketmine\Player;

use RedCraftPE\RedSkyBlock\Commands\Island;
use RedCraftPE\RedSkyBlock\Tasks\Generate;
use RedCraftPE\RedSkyBlock\Blocks\Lava;

class SkyBlock extends PluginBase {

  private $eventListener;

  private static $instance;

  private $island;

  public function onEnable(): void {

    foreach($this->cfg->get("SkyBlockWorlds", []) as $world) {

      if (!$this->getServer()->isLevelLoaded($world)) {

        if ($this->getServer()->loadLevel($world)) {

          $this->getServer()->loadLevel($world);
        }
      }
    }

    if ($this->cfg->get("SkyBlockWorld Base Name") === false) {

      $this->getLogger()->info(TextFormat::RED . "In order for this plugin to function properly, you must set a SkyBlock world in your server.");
      $this->level = null;
    } else {

      $this->level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld Base Name"));
      if (!$this->level) {

        $this->getLogger()->info(TextFormat::RED . "The level currently set as the SkyBlock base world does not exist.");
        $this->level = null;
      } else {

        $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on the base world {$this->level->getFolderName()}");
      }
    }

    $this->eventListener = new EventListener($this, $this->level);
    $this->island = new Island($this);
    self::$instance = $this;
    BlockFactory::registerBlock(new Lava(), true);
  }
  public function onLoad(): void {

    if (!is_dir($this->getDataFolder())) {

      @mkdir($this->getDataFolder());
    }
    if (!file_exists($this->getDataFolder() . "skyblock.yml")) {

      $this->saveResource("skyblock.yml");
      $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
    } else {

      $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
    }
    if (!file_exists($this->getDataFolder() . "config.yml")) {

      $this->saveResource("config.yml");
      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    } else {

      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    if (!$this->cfg->exists("PVP")) {

      $this->cfg->set("PVP", "off");
      $this->cfg->save();
    }

    $this->cfg->reload();
    $this->skyblock->reload();
  }
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

    switch(strtolower($command->getName())) {

      case "island":

        return $this->island->onIslandCommand($sender, $command, $label, $args);
      break;
    }
    return false;
  }

  //API FUNCTIONS:
  public static function getInstance(): self {

    return self::$instance;
  }
  public function calcRank(string $name): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $users = [];

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    foreach(array_keys($skyblockArray) as $user) {

      $userValue = $skyblockArray[$user]["Value"];
      $users[$user] = $userValue;
    }

    arsort($users);
    $rank = array_search($name, array_keys($users)) + 1;

    return strval($rank);
  }
  public function getIslandName(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return $skyblockArray[$name]["Name"];
  }
  public function getMembers(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return implode(", ", $skyblockArray[$name]["Members"]);
  }
  public function getValue(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return strval($skyblockArray[$name]["Value"]);
  }
  public function getBanned(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    return implode(", ", $skyblockArray[$name]["Banned"]);
  }
  public function getLockedStatus(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    if ($skyblockArray[$name]["Locked"]) {

      return "Yes";
    } else {

      return "No";
    }
  }
  public function getSize(Player $player): string {

    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());

    if (!array_key_exists($name, $skyblockArray)) {

      return "N/A";
    }

    $startX = intval($skyblockArray[$name]["Area"]["start"]["X"]);
    $startZ = intval($skyblockArray[$name]["Area"]["start"]["Z"]);
    $endX = intval($skyblockArray[$name]["Area"]["end"]["X"]);
    $endZ = intval($skyblockArray[$name]["Area"]["end"]["Z"]);

    $length = $endX - $startX;
    $width = $endZ - $startZ;

    return "{$length} x {$width}";
  }
  public function getIslandAt(Player $player) {

    $worldsArray = $this->cfg->get("SkyBlockWorlds", []);

    if (in_array($player->getLevel()->getFolderName(), $worldsArray)) {

      $skyblockArray = $this->skyblock->get("SkyBlock", []);
      $islandOwner = false;
      foreach(array_keys($skyblockArray) as $skyblock) {

        if (((int) $player->getX() >= $skyblockArray[$skyblock]["Area"]["start"]["X"] - 5 && (int) $player->getZ() >= $skyblockArray[$skyblock]["Area"]["start"]["Z"] - 5 && (int) $player->getX() <= $skyblockArray[$skyblock]["Area"]["end"]["X"] + 5 && (int) $player->getZ() <= $skyblockArray[$skyblock]["Area"]["end"]["Z"] + 5) && ($player->getLevel()->getFolderName() === $skyblockArray[$skyblock]["World"])) {

          $islandOwner = $skyblock;
          break;
        }
      }

      return $islandOwner;
    } else {

      return false;
    }
  }
  public function getPlayersOnIsland(Player $player): array {

    $name = strtolower($player->getName());
    $onlinePlayers = $this->getServer()->getOnlinePlayers();
    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $onIsland = [];

    foreach($onlinePlayers as $p) {

      $pX = (int) $p->getX();
      $pZ = (int) $p->getZ();
      $pWorld = $p->getLevel();

      if ($pWorld->getFolderName() === $skyblockArray[$name]["World"]) {

        if ($pX >= $skyblockArray[$name]["Area"]["start"]["X"] && $pX <= $skyblockArray[$name]["Area"]["end"]["X"] && $pZ >= $skyblockArray[$name]["Area"]["start"]["Z"] && $pZ <= $skyblockArray[$name]["Area"]["end"]["Z"]) {

          array_push($onIsland, $p->getName());
        }
      }
    }

    return $onIsland;
  }
}

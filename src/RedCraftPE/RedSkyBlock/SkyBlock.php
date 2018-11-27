<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\Commands\Island;
use RedCraftPE\RedSkyBlock\Tasks\Generate;

class SkyBlock extends PluginBase {

  private $eventListener;

  private static $instance;

  private $island;

  public function onEnable(): void {

    if ($this->cfg->get("SkyBlockWorld") === "") {

      $this->getLogger()->info(TextFormat::RED . "In order for this plugin to function properly, you must set a SkyBlock world in your server.");
      $this->level = null;
    } else {

      $this->level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld"));
      if (!($this->getServer()->isLevelLoaded($this->cfg->get("SkyBlockWorld")))) {

        if ($this->getServer()->loadLevel($this->cfg->get("SkyBlockWorld"))) {

          $this->getServer()->loadLevel($this->cfg->get("SkyBlockWorld"));
          $this->level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld"));
          $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on level {$this->level->getFolderName()}");
        } else {

          $this->getLogger()->info(TextFormat::RED . "The level currently set as the SkyBlock world does not exist.");
          $this->level = null;
        }
      } else {

        if ($this->getServer()->isLevelLoaded($this->level->getFolderName())) {

           $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on level {$this->level->getFolderName()}");
        } else {

          $this->getServer()->loadLevel($this->level->getFolderName());
          $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on level {$this->level->getFolderName()}");
        }
      }
    }
    $this->eventListener = new EventListener($this, $this->level);
    $this->island = new Island($this);
    self::$instance = $this;
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
  public static function getInstance(): self {

    return self::$instance;
  }
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Add;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Create;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\CreateWorld;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Lock;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Remove;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetSpawn;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetWorld;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Teleport;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\UpdateZone;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetZone;

class Island {

  private $add;
  private $create;
  private $createWorld;
  private $lock;
  private $remove;
  private $setSpawn;
  private $setWorld;
  private $teleport;
  private $updateZone;
  private $setzone;

  public function __construct($plugin) {

    $this->plugin = $plugin;

    $this->add = new Add($plugin);
    $this->create = new Create($plugin);
    $this->createWorld = new CreateWorld($plugin);
    $this->lock = new Lock($plugin);
    $this->remove = new Remove($plugin);
    $this->setSpawn = new SetSpawn($plugin);
    $this->setWorld = new SetWorld($plugin);
    $this->teleport = new Teleport($plugin);
    $this->updateZone = new UpdateZone($plugin);
    $this->setzone = new SetZone($plugin);
  }
  public function onIslandCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

    if (!$args) {

      $sender->sendMessage(TextFormat::WHITE . "Usage: /island <args>");
      return true;
    } else {

      switch (strtolower($args[0])) {

        case "add":

          return $this->add->onAddCommand($sender, $args);
        break;
        case "create":

          return $this->create->onCreateCommand($sender);
        break;
        case "cw":
        case "createworld":

          return $this->createWorld->onCreateWorldCommand($sender, $args);
        break;
        case "lock":

          return $this->lock->onLockCommand($sender, $args);
        break;
        case "remove":

          return $this->remove->onRemoveCommand($sender, $args);
        break;
        case "setspawn":

          return $this->setSpawn->onSetSpawnCommand($sender);
        break;
        case "sw":
        case "setworld":

          return $this->setWorld->onSetWorldCommand($sender, $args);
        break;
        case "spawn":
        case "goto":
        case "go":
        case "tp":
        case "teleport":
        case "visit":
          return $this->teleport->onTeleportCommand($sender, $args);
        break;
        case "updatezone":

          return $this->updateZone->onUpdateZoneCommand($sender);
        break;
        case "setzone":

          return $this->setzone->onSetZoneCommand($sender, $args);
        break;
      }
      $sender->sendMessage(TextFormat::WHITE . "Usage: /island <args>");
      return true;
    }
  }
}

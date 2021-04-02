<?php

namespace RedCraftPE\RedSkyBlock\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Add;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Ban;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Banned;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Create;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\CreateWorld;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Delete;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Fly;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Kick;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Leave;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Lock;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Members;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Name;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Nether;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\NetherSpawn;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\NetherZone;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\On;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Rank;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Reload;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Remove;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Restart;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetSpawn;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetWorld;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Teleport;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Top;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Unban;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\UpdateZone;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetZone;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Value;

class Island {

  private $add;
  private $ban;
  private $banned;
  private $create;
  private $createWorld;
  private $delete;
  private $fly;
  private $kick;
  private $leave;
  private $lock;
  private $members;
  private $name;
  private $nether;
  private $netherSpawn;
  private $netherZone;
  private $on;
  private $rank;
  private $reload;
  private $remove;
  private $restart;
  private $setSpawn;
  private $setWorld;
  private $teleport;
  private $top;
  private $unban;
  private $updateZone;
  private $setzone;
  private $value;

  public function __construct($plugin) {

    $this->plugin = $plugin;

    $this->add = new Add($plugin);
    $this->ban = new Ban($plugin);
    $this->banned = new Banned($plugin);
    $this->create = new Create($plugin);
    $this->createWorld = new CreateWorld($plugin);
    $this->delete = new Delete($plugin);
    $this->fly = new Fly($plugin);
    $this->kick = new Kick($plugin);
    $this->leave = new Leave($plugin);
    $this->lock = new Lock($plugin);
    $this->members = new Members($plugin);
    $this->name = new Name($plugin);
    $this->nether = new Nether($plugin);
    $this->netherSpawn = new NetherSpawn($plugin);
    $this->netherZone = new NetherZone($plugin);
    $this->on = new On($plugin);
    $this->rank = new Rank($plugin);
    $this->reload = new Reload($plugin);
    $this->remove = new Remove($plugin);
    $this->restart = new Restart($plugin);
    $this->setSpawn = new SetSpawn($plugin);
    $this->setWorld = new SetWorld($plugin);
    $this->teleport = new Teleport($plugin);
    $this->top = new Top($plugin);
    $this->unban = new Unban($plugin);
    $this->updateZone = new UpdateZone($plugin);
    $this->setzone = new SetZone($plugin);
    $this->value = new Value($plugin);
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
        case "ban":

          return $this->ban->onBanCommand($sender, $args);
        break;
        case "banned":

          return $this->banned->onBannedCommand($sender);
        break;
        case "create":

          return $this->create->onCreateCommand($sender);
        break;
        case "cw":
        case "createworld":

          return $this->createWorld->onCreateWorldCommand($sender, $args);
        break;
        case "delete":

          return $this->delete->onDeleteCommand($sender, $args);
        break;
        case "fly":

          return $this->fly->onFlyCommand($sender);
        break;
        case "kick":

          return $this->kick->onKickCommand($sender, $args);
        break;
        case "leave":

          return $this->leave->onLeaveCommand($sender, $args);
        break;
        case "lock":

          return $this->lock->onLockCommand($sender, $args);
        break;
        case "members":

          return $this->members->onMembersCommand($sender);
        break;
        case "name":
        case "rename":

          return $this->name->onNameCommand($sender, $args);
        break;
        case "nether":

          return $this->nether->onNetherCommand($sender);
        break;
        case "netherspawn":

          return $this->netherSpawn->onNetherSpawnCommand($sender);
        break;
        case "netherzone":

          return $this->netherZone->onNetherZoneCommand($sender, $args);
        break;
        case "on":

          return $this->on->onOnCommand($sender);
        break;
        case "rank":

          return $this->rank->onRankCommand($sender);
        break;
        case "reload":

          return $this->reload->onReloadCommand($sender);
        break;
        case "remove":

          return $this->remove->onRemoveCommand($sender, $args);
        break;
        case "restart":
        case "reset":

          return $this->restart->onRestartCommand($sender);
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
        case "top":
        case "leaderboard":
        case "lb":

          return $this->top->onTopCommand($sender);
        break;
        case "unban":

          return $this->unban->onUnbanCommand($sender, $args);
        break;
        case "updatezone":

          return $this->updateZone->onUpdateZoneCommand($sender, $args);
        break;
        case "setzone":

          return $this->setzone->onSetZoneCommand($sender, $args);
        break;
        case "value":

          return $this->value->onValueCommand($sender);
        break;
      }
      $sender->sendMessage(TextFormat::WHITE . "Usage: /island <args>");
      return true;
    }
  }
}

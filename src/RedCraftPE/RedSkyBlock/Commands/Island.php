<?php

namespace RedCraftPE\RedSkyBlock\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Add;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Ban;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Banned;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Create;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\CreateWorld;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\CustomSpawn;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Delete;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Fly;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Help;
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
use RedCraftPE\RedSkyBlock\Commands\SubCommands\SetZone;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Size;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Teleport;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Top;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Unban;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\UpdateZone;
use RedCraftPE\RedSkyBlock\Commands\SubCommands\Value;
use RedCraftPE\RedSkyBlock\SkyBlock;

class Island{

	private Add $add;
	private Ban $ban;
	private Banned $banned;
	private Create $create;
	private CreateWorld $createWorld;
	private CustomSpawn $customSpawn;
	private Delete $delete;
	private Fly $fly;
	private Help $help;
	private Kick $kick;
	private Leave $leave;
	private Lock $lock;
	private Members $members;
	private Name $name;
	private Nether $nether;
	private NetherSpawn $netherSpawn;
	private NetherZone $netherZone;
	private On $on;
	private Rank $rank;
	private Reload $reload;
	private Remove $remove;
	private Restart $restart;
	private SetSpawn $setSpawn;
	private SetWorld $setWorld;
	private SetZone $setzone;
	private Size $size;
	private Teleport $teleport;
	private Top $top;
	private Unban $unban;
	private UpdateZone $updateZone;
	private Value $value;
	public SkyBlock $plugin;

	public function __construct(SkyBlock $plugin){

		$this->plugin = $plugin;

		$this->add = new Add($plugin);
		$this->ban = new Ban($plugin);
		$this->banned = new Banned($plugin);
		$this->create = new Create($plugin);
		$this->createWorld = new CreateWorld($plugin);
		$this->customSpawn = new CustomSpawn($plugin);
		$this->delete = new Delete($plugin);
		$this->fly = new Fly($plugin);
		$this->help = new Help($plugin);
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
		$this->setzone = new SetZone($plugin);
		$this->size = new Size($plugin);
		$this->teleport = new Teleport($plugin);
		$this->top = new Top($plugin);
		$this->unban = new Unban($plugin);
		$this->updateZone = new UpdateZone($plugin);
		$this->value = new Value($plugin);
	}

	public function onIslandCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{

		if(!$args){

			return $this->help->onHelpCommand($sender, $args);
		}else{

			switch(strtolower($args[0])){

				case "add":

					return $this->add->onAddCommand($sender, $args);

				case "ban":

					return $this->ban->onBanCommand($sender, $args);

				case "banned":

					return $this->banned->onBannedCommand($sender);

				case "create":

					return $this->create->onCreateCommand($sender);

				case "cw":
				case "createworld":

					return $this->createWorld->onCreateWorldCommand($sender, $args);

				case "customspawn":

					return $this->customSpawn->onCustomSpawnCommand($sender);

				case "delete":

					return $this->delete->onDeleteCommand($sender, $args);

				case "fly":

					return $this->fly->onFlyCommand($sender);

				case "help":

					return $this->help->onHelpCommand($sender, $args);

				case "kick":

					return $this->kick->onKickCommand($sender, $args);

				case "leave":

					return $this->leave->onLeaveCommand($sender, $args);

				case "lock":

					return $this->lock->onLockCommand($sender, $args);

				case "members":

					return $this->members->onMembersCommand($sender);

				case "name":
				case "rename":

					return $this->name->onNameCommand($sender, $args);

				case "nether":

					return $this->nether->onNetherCommand($sender);

				case "netherspawn":

					return $this->netherSpawn->onNetherSpawnCommand($sender);

				case "netherzone":

					return $this->netherZone->onNetherZoneCommand($sender, $args);

				case "on":

					return $this->on->onOnCommand($sender);

				case "rank":

					return $this->rank->onRankCommand($sender);

				case "reload":

					return $this->reload->onReloadCommand($sender);

				case "remove":

					return $this->remove->onRemoveCommand($sender, $args);

				case "restart":
				case "reset":

					return $this->restart->onRestartCommand($sender);

				case "setspawn":

					return $this->setSpawn->onSetSpawnCommand($sender);

				case "sw":
				case "setworld":

					return $this->setWorld->onSetWorldCommand($sender, $args);

				case "setzone":

					return $this->setzone->onSetZoneCommand($sender, $args);

				case "size":

					return $this->size->onSizeCommand($sender, $args);

				case "spawn":
				case "goto":
				case "go":
				case "tp":
				case "teleport":
				case "visit":

					return $this->teleport->onTeleportCommand($sender, $args);

				case "top":
				case "leaderboard":
				case "lb":

					return $this->top->onTopCommand($sender);

				case "unban":

					return $this->unban->onUnbanCommand($sender, $args);

				case "updatezone":

					return $this->updateZone->onUpdateZoneCommand($sender, $args);

				case "value":

					return $this->value->onValueCommand($sender);

			}
			return $this->help->onHelpCommand($sender, $args);
		}
	}
}

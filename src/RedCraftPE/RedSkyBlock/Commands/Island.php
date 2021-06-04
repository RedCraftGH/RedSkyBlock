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

use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\InvMenuEventHandler;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\MenuIds;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;

class Island {

  private $add;
  private $ban;
  private $banned;
  private $create;
  private $createWorld;
  private $delete;
  private $fly;
  private $help;
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
  private $setzone;
  private $size;
  private $teleport;
  private $top;
  private $unban;
  private $updateZone;
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
  public function onIslandCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

    if (!$args) {

      $this->menu = InvMenu::create(InvMenu::TYPE_CHEST);
      $this->menu->readonly();
	    $this->menu->setListener([$this, "shopmenu"]);
         $this->menu->setName($title1);
	    $inventory = $this->menu->getInventory();




       //0
			 $inconfig = $guicfg->get("item.name");
			 $id = $guicfg->get("item.id");
			 $meta = $guicfg->get("item.meta");
			 $count = $guicfg->get("item.count");
			 $price = $guicfg->get("price")
			 $in = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig);
			 //1
			 $inconfig1 = $guicfg->get("item.name1");
			 $id1 = $guicfg->get("item.id1");
			 $meta1 = $guicfg->get("item.meta1");
			 $count1 = $guicfg->get("item.count1");
			 $in1 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig1);

			 //2 
			 $inconfig2 = $guicfg->get("item.name2");
			 $id2 = $guicfg->get("item.id2");
			 $meta2 = $guicfg->get("item.meta2");
			 $count2 = $guicfg->get("item.count2");
			 $in2 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig2);
			 //3 
			 $inconfig3 = $guicfg->get("item.name3");
			 $id3 = $guicfg->get("item.id3");
			 $meta3 = $guicfg->get("item.meta3");
			 $count3 = $guicfg->get("item.count3");
			 $in3 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig3);
			 //4 

			 $inconfig4 = $guicfg->get("item.name4");
			 $id4 = $guicfg->get("item.id4");
			 $meta4 = $guicfg->get("item.meta4");
			 $count4 = $guicfg->get("item.count4");
			 $in4 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig4);
			 //5 
			 $inconfig5 = $guicfg->get("item.name5");
			 $id5 = $guicfg->get("item.id5");
			 $meta5 = $guicfg->get("item.meta5");
			 $count5 = $guicfg->get("item.count5");
			 $in5 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig5);
			 //6 
			 $inconfig6 = $guicfg->get("item.name6");
			 $id6 = $guicfg->get("item.id6");
			 $meta6 = $guicfg->get("item.meta6");
			 $count6 = $guicfg->get("item.count6");
			 $in6 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig6);
			 //7 
			 $inconfig7 = $guicfg->get("item.name7");
			 $id7 = $guicfg->get("item.id7");
			 $meta7 = $guicfg->get("item.meta7");
			 $count7 = $guicfg->get("item.count7");
			 $in7 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig7);
			 //8 
			 $inconfig8 = $guicfg->get("item.name8");
			 $id8 = $guicfg->get("item.id8");
			 $meta8 = $guicfg->get("item.meta8");
			 $count8 = $guicfg->get("item.count8");
			 $in8 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig8);
			 $inconfig9 = $guicfg->get("item.name9");
			 $id9 = $guicfg->get("item.id9");
			 $meta9 = $guicfg->get("item.meta9");
			 $count9 = $guicfg->get("item.count9");
			 $in9 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig9);
			 $inconfig10 = $guicfg->get("item.name10");
			 $id10 = $guicfg->get("item.id10");
			 $meta10 = $guicfg->get("item.meta10");
			 $count10 = $guicfg->get("item.count10");
			 $in10 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig10);
			 $inconfig11 = $guicfg->get("item.name11");
			 $id11 = $guicfg->get("item.id11");
			 $meta11 = $guicfg->get("item.meta11");
			 $count11 = $guicfg->get("item.count11");
			 $in11 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig11);
			 $inconfig12 = $guicfg->get("item.name12");
			 $id12 = $guicfg->get("item.id12");
			 $meta12 = $guicfg->get("item.meta12");
			 $count12 = $guicfg->get("item.count12");
			 $in12 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig12);
			 $inconfig13 = $guicfg->get("item.name13");
			 $id13 = $guicfg->get("item.id13");
			 $meta13 = $guicfg->get("item.meta13");
			 $count13 = $guicfg->get("item.count13");
			 $in13 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig13);
			 $inconfig14 = $guicfg->get("item.name14");
			 $id14 = $guicfg->get("item.id14");
			 $meta14 = $guicfg->get("item.meta14");
			 $count14 = $guicfg->get("item.count14");
			 $in14 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig14);	    
			 $inconfig15 = $guicfg->get("item.name15"); 	    
			 $id15 = $guicfg->get("item.id15"); 	    
			 $meta15 = $guicfg->get("item.meta15"); 	    
			 $count15 = $guicfg->get("item.count15"); 	    
			 $in15 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig15);	    
			 $inconfig16 = $guicfg->get("item.name16"); 	    
			 $id16 = $guicfg->get("item.id16"); 	    
			 $meta16 = $guicfg->get("item.meta16"); 	    
			 $count16 = $guicfg->get("item.count16"); 	    
			 $in16 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig16);	    
			 $inconfig17 = $guicfg->get("item.name17"); 	    
			 $id17 = $guicfg->get("item.id17"); 	    
			 $meta17 = $guicfg->get("item.meta17"); 	    
			 $count17 = $guicfg->get("item.count17"); 	    
			 $in17 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig17);	    
			 $inconfig18 = $guicfg->get("item.name18");
			 $id18 = $guicfg->get("item.id18");
			 $meta18 = $guicfg->get("item.meta18");
			 $count18 = $guicfg->get("item.count18");
			 $in18 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig18);
			 $inconfig19 = $guicfg->get("item.name19");
			 $id19 = $guicfg->get("item.id19");
			 $meta19 = $guicfg->get("item.meta19");
			 $count19 = $guicfg->get("item.count19");
			 $in19 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig19);
			 $inconfig20 = $guicfg->get("item.name20");
			 $id20 = $guicfg->get("item.id20");
			 $meta20 = $guicfg->get("item.meta20");
			 $count20 = $guicfg->get("item.count20");
			 $in20 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig20);
			 $inconfig21 = $guicfg->get("item.name21");
			 $id21 = $guicfg->get("item.id21");
			 $meta21 = $guicfg->get("item.meta21");
			 $count21 = $guicfg->get("item.count21");
			 $in21 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig21);
			 $inconfig22 = $guicfg->get("item.name22");
			 $id22 = $guicfg->get("item.id22");
			 $meta22 = $guicfg->get("item.meta22");
			 $count22 = $guicfg->get("item.count22");
			 $in22 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig22);
			 $inconfig23 = $guicfg->get("item.name23");
			 $id23 = $guicfg->get("item.id23");
			 $meta23 = $guicfg->get("item.meta23");
			 $count23 = $guicfg->get("item.count23");
			 $in23 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig23);
			 $inconfig24 = $guicfg->get("item.name24");
			 $id24 = $guicfg->get("item.id24");
			 $meta24 = $guicfg->get("item.meta24");
			 $count24 = $guicfg->get("item.count24");
			 $in24 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig24);
			 $inconfig25 = $guicfg->get("item.name25");
			 $id25 = $guicfg->get("item.id25");
			 $meta25 = $guicfg->get("item.meta25");
			 $count25 = $guicfg->get("item.count25");
			 $in25 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig25);
			 $inconfig26 = $guicfg->get("item.name26");
			 $id26 = $guicfg->get("item.id26");
			 $meta26 = $guicfg->get("item.meta26");
			 $count26 = $guicfg->get("item.count26");
			 $in26 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig26);
			 $inconfig27 = $guicfg->get("item.name27");
			 $id27 = $guicfg->get("item.id27");
			 $meta27 = $guicfg->get("item.meta27");
			 $count27 = $guicfg->get("item.count27");
			 $in27 = str_replace(["&", "+n", "%player_name%", "%player_ping%", "%player_address%", "%player_client_id%", "%player_direction%", "%player_displayname%", "%player_hunger%", "%player_x%", "%player_y%", "%player_z%", "%player_hunger_point%", "%player_gamemode%", "%player_health_point%", "%player_location%", "%player_hunger_point_max%", "%player_health_point_max%", "%player_scale%", "%server_online%", "%server_max_players%", "%item_id%", "%item_meta%", "%item_count%", "%item_name%", "%server_load%", "%server_tps%", "%level_name%", "%level_folder_name%", "%level_player_count%", "%player_balance%"], ["§", "\n", $sender->getName(), $sender->getPing(), $sender->getAddress(), $sender->getClientId(), $sender->getDirection(), $sender->getDisplayName(), $sender->getExhaustion(), $sender->getFloorX(), $sender->getFloorY(), $sender->getFloorZ(), $sender->getFood(), $sender->getGamemode(), $sender->getHealth(), $sender->getLocation(), $sender->getMaxFood(), $sender->getMaxHealth(), $sender->getScale(), count($sender->getServer()->getOnlinePlayers()), $sender->getServer()->getMaxPlayers(), $sender->getInventory()->getItemInHand()->getId(), $sender->getInventory()->getItemInHand()->getDamage(), $sender->getInventory()->getItemInHand()->getCount(), $sender->getInventory()->getItemInHand()->getName(), $sender->getServer()->getTickUsage(), $sender->getServer()->getTicksPerSecond(), $sender->getLevel()->getName(), $sender->getLevel()->getFolderName(), count($sender->getLevel()->getPlayers()), $this->economyAPI->myMoney($sender)], $inconfig27);
			

			


            //Chest Section 1-8
		 $inventory->setItem(0, Item::get($id, $meta, $count)->setCustomName($in));
		 $inventory->setItem(1, Item::get($id1, $meta1, $count1)->setCustomName($in1));
		 $inventory->setItem(2, Item::get($id2, $meta2, $count2)->setCustomName($in2));
		 $inventory->setItem(3, Item::get($id3, $meta3, $count3)->setCustomName($in3));
		 $inventory->setItem(4, Item::get($id4, $meta4, $count4)->setCustomName($in4));
		 $inventory->setItem(5, Item::get($id5, $meta5, $count5)->setCustomName($in5));
		 $inventory->setItem(6, Item::get($id6, $meta6, $count6)->setCustomName($in6));
		 $inventory->setItem(7, Item::get($id7, $meta7, $count7)->setCustomName($in7));
		 $inventory->setItem(8, Item::get($id8, $meta8, $count8)->setCustomName($in8));
		  //Chest Section 9-17
		  $inventory->setItem(9, Item::get($id9, $meta9, $count9)->setCustomName($in9));
		 $inventory->setItem(10, Item::get($id10, $meta10, $count10)->setCustomName($in10));
		 $inventory->setItem(11, Item::get($id11, $meta11, $count11)->setCustomName($in11));
		 $inventory->setItem(12, Item::get($id12, $meta12, $count12)->setCustomName($in12));
		 $inventory->setItem(13, Item::get($id13, $meta13, $count13)->setCustomName($in13));
		 $inventory->setItem(14, Item::get($id14, $meta14, $count14)->setCustomName($in14));
		 $inventory->setItem(15, Item::get($id15, $meta15, $count15)->setCustomName($in15));
		 $inventory->setItem(16, Item::get($id16, $meta16, $count16)->setCustomName($in16));
		 $inventory->setItem(17, Item::get($id17, $meta17, $count17)->setCustomName($in17));
		  //Chest Section 18-26
		  $inventory->setItem(18, Item::get($id18, $meta18, $count18)->setCustomName($in18));
		 $inventory->setItem(19, Item::get($id19, $meta19, $count19)->setCustomName($in19));
		 $inventory->setItem(20, Item::get($id20, $meta20, $count20)->setCustomName($in20));
		 $inventory->setItem(21, Item::get($id21, $meta21, $count21)->setCustomName($in21));
		 $inventory->setItem(22, Item::get($id22, $meta22, $count22)->setCustomName($in22));
		 $inventory->setItem(23, Item::get($id23, $meta23, $count23)->setCustomName($in23));
		 $inventory->setItem(24, Item::get($id24, $meta24, $count24)->setCustomName($in24));
		 $inventory->setItem(25, Item::get($id25, $meta25, $count25)->setCustomName($in25));
		 $inventory->setItem(26, Item::get($id26, $meta26, $count26)->setCustomName($in26));


		
		 if($item->getId() === $guicfg->get("id") && $item->getDamage() === $guicfg->get("meta")){
			$command = $guicfg->get("command")
			$this->getServer()->dispatchCommand($player, $command);}
			if($item->getId() === $guicfg->get("id1") && $item->getDamage() === $guicfg->get("meta1")){
				$command = $guicfg->get("command1")
				$this->getServer()->dispatchCommand($player, $command);}
				if($item->getId() === $guicfg->get("id2") && $item->getDamage() === $guicfg->get("meta2")){
					$command = $guicfg->get("command2")
					$this->getServer()->dispatchCommand($player, $command);}
					if($item->getId() === $guicfg->get("id3") && $item->getDamage() === $guicfg->get("meta3")){
						$command = $guicfg->get("command3")
						$this->getServer()->dispatchCommand($player, $command);}
						if($item->getId() === $guicfg->get("id4") && $item->getDamage() === $guicfg->get("meta4")){
							$command = $guicfg->get("command4")
							$this->getServer()->dispatchCommand($player, $command);}
							if($item->getId() === $guicfg->get("id5") && $item->getDamage() === $guicfg->get("meta5")){
								$command = $guicfg->get("command5")
								$this->getServer()->dispatchCommand($player, $command);}
								if($item->getId() === $guicfg->get("id6") && $item->getDamage() === $guicfg->get("meta6")){
									$command = $guicfg->get("command6")
									$this->getServer()->dispatchCommand($player, $command);}
									if($item->getId() === $guicfg->get("id7") && $item->getDamage() === $guicfg->get("meta7")){
										$command = $guicfg->get("command7")
										$this->getServer()->dispatchCommand($player, $command);}
										if($item->getId() === $guicfg->get("id8") && $item->getDamage() === $guicfg->get("meta8")){
											$command = $guicfg->get("command8")
											$this->getServer()->dispatchCommand($player, $command);}
											if($item->getId() === $guicfg->get("id9") && $item->getDamage() === $guicfg->get("meta9")){
												$command = $guicfg->get("command9")
												$this->getServer()->dispatchCommand($player, $command);}
												if($item->getId() === $guicfg->get("id10") && $item->getDamage() === $guicfg->get("meta10")){
													$command = $guicfg->get("command10")
													$this->getServer()->dispatchCommand($player, $command);}
													if($item->getId() === $guicfg->get("id11") && $item->getDamage() === $guicfg->get("meta11")){
														$command = $guicfg->get("command11")
														$this->getServer()->dispatchCommand($player, $command);}
														if($item->getId() === $guicfg->get("id12") && $item->getDamage() === $guicfg->get("meta12")){
															$command = $guicfg->get("command12")
															$this->getServer()->dispatchCommand($player, $command);}
															if($item->getId() === $guicfg->get("id13") && $item->getDamage() === $guicfg->get("meta13")){
																$command = $guicfg->get("command13")
																$this->getServer()->dispatchCommand($player, $command);}
																if($item->getId() === $guicfg->get("id14") && $item->getDamage() === $guicfg->get("meta14")){
																	$command = $guicfg->get("command14")
																	$this->getServer()->dispatchCommand($player, $command);}
																	if($item->getId() === $guicfg->get("id15") && $item->getDamage() === $guicfg->get("meta15")){
																		$command = $guicfg->get("command15")
																		$this->getServer()->dispatchCommand($player, $command);}
																		if($item->getId() === $guicfg->get("id6") && $item->getDamage() === $guicfg->get("meta16")){
																			$command = $guicfg->get("command16")
																			$this->getServer()->dispatchCommand($player, $command);}
																			if($item->getId() === $guicfg->get("id17") && $item->getDamage() === $guicfg->get("meta17")){
																				$command = $guicfg->get("command17")
																				$this->getServer()->dispatchCommand($player, $command);}
																				if($item->getId() === $guicfg->get("id18") && $item->getDamage() === $guicfg->get("meta18")){
																					$command = $guicfg->get("command18")
																					$this->getServer()->dispatchCommand($player, $command);}
																					if($item->getId() === $guicfg->get("id19") && $item->getDamage() === $guicfg->get("meta19")){
																						$command = $guicfg->get("command9")
																						$this->getServer()->dispatchCommand($player, $command);}
																						if($item->getId() === $guicfg->get("id20") && $item->getDamage() === $guicfg->get("meta20")){
																							$command = $guicfg->get("command20")
																							$this->getServer()->dispatchCommand($player, $command);}	
																							
																							











																							if($item->getId() === $guicfg->get("id21") && $item->getDamage() === $guicfg->get("meta21")){
																								$command = $guicfg->get("command21")
																								$this->getServer()->dispatchCommand($player, $command);}
																								if($item->getId() === $guicfg->get("id22") && $item->getDamage() === $guicfg->get("meta22")){
																									$command = $guicfg->get("command22")
																									$this->getServer()->dispatchCommand($player, $command);}
																									if($item->getId() === $guicfg->get("id23") && $item->getDamage() === $guicfg->get("meta23")){
																										$command = $guicfg->get("command23")
																										$this->getServer()->dispatchCommand($player, $command);}
																										if($item->getId() === $guicfg->get("id24") && $item->getDamage() === $guicfg->get("meta24")){
																											$command = $guicfg->get("command24")
																											$this->getServer()->dispatchCommand($player, $command);}
																											if($item->getId() === $guicfg->get("id25") && $item->getDamage() === $guicfg->get("meta25")){
																												$command = $guicfg->get("command25")
																												$this->getServer()->dispatchCommand($player, $command);}
																												if($item->getId() === $guicfg->get("id26") && $item->getDamage() === $guicfg->get("meta26")){
																													$command = $guicfg->get("command26")
																													$this->getServer()->dispatchCommand($player, $command);}
																													if($item->getId() === $guicfg->get("id27") && $item->getDamage() === $guicfg->get("meta27")){
																														$command = $guicfg->get("command27")
																														$this->getServer()->dispatchCommand($player, $command);}
																														

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
        case "help":

          return $this->help->onHelpCommand($sender, $args);
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
        case "setzone":

          return $this->setzone->onSetZoneCommand($sender, $args);
        break;
        case "size":

          return $this->size->onSizeCommand($sender, $args);
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
        case "value":

          return $this->value->onValueCommand($sender);
        break;
      }
      $sender->sendMessage(TextFormat::WHITE . "");
      return true;
    }
  }
}

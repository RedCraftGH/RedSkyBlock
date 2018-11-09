<?php

namespace RedCraftPE;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\block\Block;
use pocketmine\level\generator\object\Tree;
use pocketmine\item\Item;
use pocketmine\event\block\BlockUpdateEvent;
use RedCraftPE\task\Generate;

class SkyBlock extends PluginBase implements Listener {
	
  public static $instance;

  public function onEnable(): void {
    
    //onEnable starts here (obviously): Mainly just setting up the config and loading necessary levels if possible.
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    self::$instance = $this;
    if(!file_exists($this->getDataFolder() . "skyblock.yml")){

      @mkdir($this->getDataFolder());
      $this->saveResource("skyblock.yml");
      $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
      $this->skyblock->set("SkyBlock", []);
      $this->skyblock->set("Islands", 0);
    } else {
	    
      $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
    }
    
    if(!file_exists($this->getDataFolder() . "config.yml")){

      @mkdir($this->getDataFolder());
      $this->saveResource("config.yml");
      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
      $this->cfg->set("SkyBlockWorld", "");
      $this->cfg->set("Interval", 300);
      $this->cfg->set("CobbleGen", true);
      //Island protection areas will be customizable, soon, in the future, but for now Island protections areas will be 100x100 standard.
      //Island starting Items will be customizable in the future, but for now standard starting items will be used.
      //How much/little islands are protected will be customizable soon, but for now it will be standard.
      //Many, many more things will be added to the config soon, but for now I am just building the basic SkyBlock plugin with little customization allowed.
    } else {
	    
      $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }
    
    $this->cfg->save();
    $this->cfg->reload();
    $this->skyblock->save();
    $this->skyblock->reload();
    
    $level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld"));
    if (!$level) {
    
      $this->getLogger()->info(TextFormat::RED . "In order for this plugin to function properly, you must set a SkyBlock world in your server.");
    } else {
    
    $this->getServer()->loadLevel($level->getName());
    }
    //Should be all done now with the onEnable() :shrug:
  }
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
    
    $level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld"));
    if (!$level) {
    
      if (!$args) {
      
        $sender->sendMessage(TextFormat::RED . "You need to set a SkyBlock world with '/is setworld' before any other commands will function!");
        return true;
      } elseif ($args[0] !== "setworld") {
      
      	$sender->sendMessage(TextFormat::RED . "You need to set a SkyBlock world with '/is setworld' before any other commands will function!");
        return true;
      }
    }
    $skyblockArray = $this->skyblock->get("SkyBlock", []);
    $interval = $this->cfg->get("Interval");
    $islands = $this->skyblock->get("Islands");
    $senderName = strtolower($sender->getname());
  
    switch(strtolower($command->getName())) {
    
      case "island":
        
        if (!$args) {
         
          if (array_key_exists($senderName, $skyblockArray)) {
         
            if ($sender->hasPermission("skyblock.tp") || $sender->hasPermission("skyblock.*")) {
             
              //Because the array key exists, the $sender has an island, thus, this will be used for teleporting a player to their island.
              $x = $skyblockArray[$senderName]["Area"]["start"]["X"];
              $z = $skyblockArray[$senderName]["Area"]["start"]["Z"];

              $sender->teleport(new Position($x + 50, 18, $z + 50, $level));
              $sender->sendMessage(TextFormat::GREEN . "You have been teleported to your island!");
              return true;
            } else {
           
              $sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
              return true;
            }
          } else {
         
            if ($sender->hasPermission("skyblock.create")) {
           
              //Create a new island for $sender, teleport them to it, and create the data.
              $sender->teleport(new Position($islands * $interval + 2, 18, $islands * $interval + 4, $level));
              $sender->setSpawn(new Vector3($islands * $interval + 2, 18, $islands * $interval + 4));
              $sender->setImmobile(true);
              $this->getScheduler()->scheduleDelayedTask(new Generate($islands, $level, $interval, $sender), 10);
              $sender->getInventory()->addItem(Item::get(79, 0, 2)); //Gonna change all of the addItems eventually to be customizable
              $sender->getInventory()->addItem(Item::get(325, 10, 1)); //by config.yml.
              $sender->getInventory()->addItem(Item::get(287, 0, 12)); //For now, I am just going to use the standard SkyBlock items
              $sender->getInventory()->addItem(Item::get(351, 15, 2));
              $sender->getInventory()->addItem(Item::get(338, 0, 1));
              $sender->getInventory()->addItem(Item::get(361, 0, 1));
              $sender->getInventory()->addItem(Item::get(81, 0, 1));
              $sender->getInventory()->addItem(Item::get(360, 0, 1));
              $sender->getInventory()->addItem(Item::get(39, 0, 1));
              $sender->getInventory()->addItem(Item::get(40, 0, 1));

              $this->skyblock->setNested("Islands", $islands + 1);
              $skyblockArray[$senderName] = Array("Name" => $sender->getName() . "'s Island", "Members" => [$sender->getName()], "Locked" => false, "Area" => Array( "start" => Array("X" => ($islands * $interval + 2) - 50, "Y" => 0, "Z" => ($islands * $interval + 4) - 50), "end" => Array("X" => ($islands * $interval + 2) + 50, "Y" => 256, "Z" => ($islands * $interval + 4) + 50)));
              $this->skyblock->set("SkyBlock", $skyblockArray);
              $this->skyblock->save();
              return true;
            } else {
           
              $sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
              return true;
            }
          }
        } else {
       
          switch ($args[0]) {
	  
	    case "setworld":
	      	
	      if ($sender->hasPermission("skyblock.setworld") || $sender->hasPermission("skyblock.*")) {
		      
	        $SBWorld = $sender->getLevel()->getName();
	        $this->cfg->set("SkyBlockWorld", $SBWorld);
	        $this->cfg->save();
	        $sender->sendMessage(TextFormat::GREEN . $SBWorld . " has been set as this server's SkyBlock world.");
	        return true;
	      } else {
	      
	      	$sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
              	return true;
	      }
	    break;
          }
        }
      break;
    }
    return false;
  }
  
  //Gonna' have this here for the island generation delay task.
  public static function getInstance(): self {
  
    return self::$instance;
  }
  
  /**
	 * Takes the current amount of $islands and the $interval, and finds the correct position to generate the next Island in the correct $level.
	 *
	 * @param int $islands
	 * @param Level $level
   * @param int $interval
	 */
  public function generateIsland(int $islands, Level $level, int $interval) {
    
    //I know this isn't the most efficient way to set blocks, but because of the small amount of blocks being set it would be pointless
    //to do something more complicated for an unnoticeable improvement in efficiency.
    
    for ($x = $islands * $interval; $x < ($islands * $interval) + 3; $x++) {
    
      for ($y = 15; $y < 18; $y++) {
      
        for ($z = $islands * $interval; $z < ($islands * $interval) + 6; $z++) {
        
          if ($y < 17) {
          
            $level->setBlock(new Vector3($x, $y, $z), Block::get(1));
          } else {
          
            $level->setBlock(new Vector3($x, $y, $z), Block::get(2));
          }
          if ($x === ($islands * $interval) + 1 && $z === $islands * $interval && $y === 17) {
          
            Tree::growTree($level, $x, $y + 1, $z, new Random(), 0);
          }
        }
      }
    }
    for ($x = ($islands * $interval) - 2; $x < $islands * $interval; $x++) {
    
      for ($y = 15; $y < 18; $y++) {
      
        for ($z = ($islands * $interval) + 3; $z < ($islands * $interval) + 6; $z++) {
	
	  if ($y < 17) {
          
            $level->setBlock(new Vector3($x, $y, $z), Block::get(1));
          } else {
          
            $level->setBlock(new Vector3($x, $y, $z), Block::get(2));
          }
	}
      }
    }
  }
	
  //The onUpdate will be used for creating the SkyBlock special CobbleGen. 
  //I am going to make customizable block spawns and spawn rates eventually, but for now it will be standard.
	
  public function onUpdate(BlockUpdateEvent $event) {
    var_dump($this->cfg->get("CobbleGen"));
    if ($this->cfg->get("CobbleGen")) {
	    
      $block = $event->getBlock();
      $isTouchingLava = false;

      if ($block instanceof Water) {

        for($side = 2; $side <= 5; $side++){

          if($block->getSide($side)->getId() === 10){

            $isTouchingLava = true;
            break;
          }
        }
      }
      if ($isTouchingLava) {

        $random = rand(0, 100);
        if ($random <= 88) {

          $block->getLevel()->setBlock($block, Block::get(4));
          return;
        } elseif ($random <= 91) {

          $block->getLevel()->setBlock($block, Block::get(16));
          return;
        } elseif ($random <= 94) {

          $block->getLevel()->setBlock($block, Block::get(21));
          return;
        } elseif ($random <= 96) {

          $block->getLevel()->setBlock($block, Block::get(15));
          return;
        } elseif ($random <= 98) {

          $block->getLevel()->setBlock($block, Block::get(14));
          return;
        } elseif ($random <= 99) {

          $block->getLevel()->setBlock($block, Block::get(56));
          return;
        } elseif ($random <= 100) {

          $block->getLevel()->setBlock($block, Block::get(57));
          return;
        }
      }
    }
  }
}

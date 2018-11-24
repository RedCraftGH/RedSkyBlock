<?php

/**
* Class and Function List:
* Function list:
* - onEnable()
* - onCommand()
* - getInstance()
* - generateIsland()
* - onUpdate()
* - onPlace()
* - onBreak()
* - onInteract()
* - onBucketEvent()
* Classes list:
* - SkyBlock extends PluginBase
*/

namespace RedCraftPE\RedSkyBlock;

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
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerBucketEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\block\Water;
use pocketmine\Player;
use RedCraftPE\RedSkyBlock\task\Generate;

class SkyBlock extends PluginBase implements Listener {

  public static $instance;

  public function onEnable(): void {
      //onEnable starts here (obviously): Mainly just setting up the config and loading necessary levels if possible.
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      self::$instance = $this;
      if (!file_exists($this->getDataFolder() . "skyblock.yml")) {
        @mkdir($this->getDataFolder());
        $this->saveResource("skyblock.yml");
        $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
        $this->skyblock->set("SkyBlock", []);
        $this->skyblock->set("Islands", 0);
      }
      else {
        $this->skyblock = new Config($this->getDataFolder() . "skyblock.yml", Config::YAML);
      }
      if (!file_exists($this->getDataFolder() . "config.yml")) {
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        //$this->cfg->set("SkyBlockWorld", "");
        //$this->cfg->set("Interval", 300);
        //$this->cfg->set("CobbleGen", true);
        //Island protection areas will be customizable, soon, in the future, but for now Island protections areas will be 100x100 standard.
        //Island starting Items will be customizable in the future, but for now standard starting items will be used.
        //How much/little islands are protected will be customizable soon, but for now it will be standard.
        //Many, many more things will be added to the config soon, but for now I am just building the basic SkyBlock plugin with little customization allowed.
        
      }
      else {
        $this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML);
      }
      $this->cfg->save();
      $this->cfg->reload();
      $this->skyblock->save();
      $this->skyblock->reload();
      if ($this->cfg->get("SkyBlockWorld") === "") {
      
        $this->getLogger()->info(TextFormat::RED . "In order for this plugin to function properly, you must set a SkyBlock world in your server.");
      } else {
      
        $this->level = $this->getServer()->getLevelByName($this->cfg->get("SkyBlockWorld"));
        if (!$this->level) {
        
          $this->getLogger()->info(TextFormat::RED . "The level currently set as the SkyBlock world does not exist.");
        } else {
        
          if ($this->getServer()->isLevelLoaded($this->level->getFolderName())) {
           
             $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on level {$this->level->getFolderName()}");
          } else {
           
            $this->getServer()->loadLevel($this->level->getFolderName());
            $this->getLogger()->info(TextFormat::GREEN . "SkyBlock is running on level {$this->level->getFolderName()}");
          }
        }
      }
      //Should be all done now with the onEnable() :shrug:
      
    }
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($this->skyblock->get("SkyBlockWorld") === "") {
          if (!$args) {
            $sender->sendMessage(TextFormat::RED . "You need to set a SkyBlock world with '/is setworld' before any other commands will function!");
            return true;
          }
          elseif ($args[0] !== "setworld") {
            $sender->sendMessage(TextFormat::RED . "You need to set a SkyBlock world with '/is setworld' before any other commands will function!");
            return true;
          }
        }
        $level = $this->level;
        $skyblockArray = $this->skyblock->get("SkyBlock", []);
        $interval = $this->cfg->get("Interval");
        $islands = $this->skyblock->get("Islands");
        $senderName = strtolower($sender->getname());
        switch (strtolower($command->getName())) {
          case "island":
            if (!$args) {
              if (array_key_exists($senderName, $skyblockArray)) {
                if ($sender->hasPermission("skyblock.tp") || $sender->hasPermission("skyblock.*")) {
                  //Because the array key exists, the $sender has an island, thus, this will be used for teleporting a player to their island.
                  $x = $skyblockArray[$senderName]["Area"]["start"]["X"];
                  $z = $skyblockArray[$senderName]["Area"]["start"]["Z"];
                  $sender->teleport(new Position($x + 50, 18, $z + 50, $this->level));
                  $sender->sendMessage(TextFormat::GREEN . "You have been teleported to your island!");
                  return true;
                }
                else {
                  $sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
                  return true;
                }
              }
              else {
                if ($sender->hasPermission("skyblock.create")) {
                  //Create a new island for $sender, teleport them to it, and create the data.
                  $sender->teleport(new Position($islands * $interval + 2, 18, $islands * $interval + 4, $this->level));
                  $sender->setSpawn(new Vector3($islands * $interval + 2, 18, $islands * $interval + 4));
                  $sender->setImmobile(true);
                  $this->getScheduler()->scheduleDelayedTask(new Generate($islands, $level, $interval, $sender), 10);
                  $itemsArray = $this->cfg->get("Starting Items");
                  foreach($itemsArray as $items) {
                    $itemArray = implode(" ", $items);
                    $id = $itemArray[0];
                    $damage = $itemArray[1];
                    $count = $itemArray[2];
                    $sender->getInventory()->addItem(Item::get($id, $damage, $count));
                  }
                  $this->skyblock->setNested("Islands", $islands + 1);
                  $skyblockArray[$senderName] = Array("Name" => $sender->getName() . "'s Island", "Members" => [$sender->getName() ], "Locked" => false, "Area" => Array("start" => Array("X" => ($islands * $interval + 2) - 50, "Y" => 0, "Z" => ($islands * $interval + 4) - 50), "end" => Array("X" => ($islands * $interval + 2) + 50, "Y" => 256, "Z" => ($islands * $interval + 4) + 50)));
                  $this->skyblock->set("SkyBlock", $skyblockArray);
                  $this->skyblock->save();
                  return true;
                }
                else {
                  $sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
                  return true;
                }
              }
            }
            else {
              switch ($args[0]) {
                case "setworld":
                  if ($sender->hasPermission("skyblock.setworld") || $sender->hasPermission("skyblock.*")) {
                    $SBWorld = $sender->getLevel()->getName();
                    $this->cfg->set("SkyBlockWorld", $SBWorld);
                    $this->cfg->save();
                    $sender->sendMessage(TextFormat::GREEN . $SBWorld . " has been set as this server's SkyBlock world.");
                    return true;
                  }
                  else {
                    $sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
                    return true;
                  }
                break;
                case "tp":
                case "goto":
                case "spawn":
                case "teleport":
                case "go":
                  if ($sender->hasPermission("skyblock.tp") || $sender->hasPermission("skyblock.*")) {
                    if (count($args) > 1) {
                      //Teleport $sender to someone elses island:
                      $player = strtolower(implode(" ", array_slice($args, 1)));
                      $playerN = implode(" ", array_slice($args, 1));
                      if(array_key_exists($player, $skyblockArray)) {
                        if ($skyblockArray[$player]["Locked"] === true) {
                          $sender->sendMessage(TextFormat::RED . $playerN . "'s island is locked!");
                          return true;
                        }
                        else {
                          //island not locked:
                          $islandX = $skyblockArray[$player]["Area"]["start"]["X"] + 50;
                          $islandZ = $skyblockArray[$player]["Area"]["start"]["Z"] + 50;
                          $sender->teleport(new Position($islandX, 18, $islandZ, $this->level));
                          $sender->sendMessage(TextFormat::GREEN . "Welcome to " . $skyblockArray[$player]["Name"]);
                          return true;
                        }
                      }
                      else {
                        $sender->sendMessage(TextFormat::RED . $playerN . " does not have an island!");
                        return true;
                      }
                    }
                    else {
                      //Teleport $sender to their own island:
                      $x = $skyblockArray[$senderName]["Area"]["start"]["X"];
                      $z = $skyblockArray[$senderName]["Area"]["start"]["Z"];
                      $sender->teleport(new Position($x + 50, 18, $z + 50, $this->level));
                      $sender->sendMessage(TextFormat::GREEN . "You have been teleported to your island!");
                      return true;
                    }
                }
                else {
                  $sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
                  return true;
                }
                break;
                case "lock":
                case "close":
                  if ($sender->hasPermission("skyblock.lock") || $sender->hasPermission("skyblock.*")) {
                    if (array_key_exists($senderName, $skyblockArray)) {
                      if ($skyblockArray[$senderName]["Locked"] === true) {
                        $sender->sendMessage(TextFormat::RED . "Your island is already locked!");
                        return true;
                      } else {
                        $skyblockArray[$senderName]["Locked"] = true;
                        $this->skyblock->set("SkyBlock", $skyblockArray);
                        $this->skyblock->save();
                        $sender->sendMessage(TextFormat::GREEN . "Your island is now locked!");
                        return true;
                      }
                    } else {
                      $sender->sendMessage(TextFormat::RED . "You don't have an island yet!");
                      return true;
                    }
                  } else {
                    $sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
                    return true;
                  }
                break;
                case "open":
                case "unlock":
                  if ($sender->hasPermission("skyblock.lock") || $sender->hasPermission("skyblock.*")) {
                    if (array_key_exists($senderName, $skyblockArray)) {
                      if ($skyblockArray[$senderName]["Locked"] === false) {
                        $sender->sendMessage(TextFormat::RED . "Your island is not locked!");
                        return true;
                      } else {
                        $skyblockArray[$senderName]["Locked"] = false;
                        $this->skyblock->set("SkyBlock", $skyblockArray);
                        $this->skyblock->save();
                        $sender->sendMessage(TextFormat::GREEN . "Your island is no longer locked!");
                        return true;
                      }
                    } else {
                      $sender->sendMessage(TextFormat::RED . "You don't have an island yet!");
                      return true;
                    }
                  } else {
                    $sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
                    return true;
                  }
                break;
                case "help":
                  if ($sender->hasPermission("skyblock.help") || $sender->hasPermission("skyblock.*")) {
                    if (!(count($args) > 1)) {
                      $this->sendHelp($sender, 1);
                      return true;
                    } else {
                      if (!is_numeric($args[1])) {
                        $sender->sendMessage(TextFormat::RED . $args[1] . " is not a valid page number.");
                        return true;
                      } else {
                        $this->sendHelp($sender, $args[1]);
                        return true;
                      }
                    }
                  } else {
                    $sender->sendMessage(TextFormat::RED . "Unfortunately, you do not have access to this SkyBlock command.");
                    return true;
                  }
                break;
              }
          }
          break;
        }
        $this->sendHelp($sender, 1);
        return true;
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
          for ($x = $islands * $interval;$x < ($islands * $interval) + 3;$x++) {
            for ($y = 15;$y < 18;$y++) {
              for ($z = $islands * $interval;$z < ($islands * $interval) + 6;$z++) {
                if ($y < 17) {
                  $level->setBlock(new Vector3($x, $y, $z), Block::get(1));
                }
                else {
                  $level->setBlock(new Vector3($x, $y, $z), Block::get(2));
                }
                if ($x === ($islands * $interval) + 1 && $z === $islands * $interval && $y === 17) {
                  Tree::growTree($level, $x, $y + 1, $z, new Random(), 0);
                }
              }
            }
          }
          for ($x = ($islands * $interval) - 2;$x < $islands * $interval;$x++) {
            for ($y = 15;$y < 18;$y++) {
              for ($z = ($islands * $interval) + 3;$z < ($islands * $interval) + 6;$z++) {
                if ($y < 17) {
                  $level->setBlock(new Vector3($x, $y, $z), Block::get(1));
                }
                else {
                  $level->setBlock(new Vector3($x, $y, $z), Block::get(2));
                }
              }
            }
          }
        }
        //The onUpdate will be used for creating the SkyBlock special CobbleGen.
        //I am going to make customizable block spawns and spawn rates eventually, but for now it will be standard.
        public function onUpdate(BlockUpdateEvent $event) {
          if ($this->cfg->get("CobbleGen")) {
            $block = $event->getBlock();
            $isTouchingLava = false;
            if ($block instanceof Water) {
              for ($side = 2;$side <= 5;$side++) {
                if ($block->getSide($side)->getId() === 10) {
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
              }
              elseif ($random <= 91) {
                $block->getLevel()->setBlock($block, Block::get(16));
                return;
              }
              elseif ($random <= 94) {
                $block->getLevel()->setBlock($block, Block::get(21));
                return;
              }
              elseif ($random <= 96) {
                $block->getLevel()->setBlock($block, Block::get(15));
                return;
              }
              elseif ($random <= 98) {
                $block->getLevel()->setBlock($block, Block::get(14));
                return;
              }
              elseif ($random <= 99) {
                $block->getLevel()->setBlock($block, Block::get(56));
                return;
              }
              elseif ($random <= 100) {
                $block->getLevel()->setBlock($block, Block::get(57));
                return;
              }
            }
          }
        }
        public function onPlace(BlockPlaceEvent $event) {
          $player = $event->getPlayer();
          $block = $event->getBlock();
          $level = $this->level;
          if ($player->getLevel() === $level) {
            $skyblockArray = $this->skyblock->get("SkyBlock", []);
            $blockX = $block->getX();
            $blockY = $block->getY();
            $blockZ = $block->getZ();
            $islandOwner = "";
            foreach (array_keys($skyblockArray) as $skyblocks) {
              $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
              $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
              $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
              $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
              $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
              $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];
              if ($blockX > $startX && $blockY > $startY && $blockZ > $startZ && $blockX < $endX && $blockY < $endY && $blockZ < $endZ) {
                $islandOwner = $skyblocks;
                break;
              }
            }
            if ($islandOwner === "") {
              $event->setCancelled(true);
              $player->sendMessage(TextFormat::RED . "You cannot build here!");
              return;
            }
            elseif (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {
              return;
            }
            else {
              $event->setCancelled(true);
              $player->sendMessage(TextFormat::RED . "You cannot build here!");
              return;
            }
          }
        }
        public function onBreak(BlockBreakEvent $event) {
          $player = $event->getPlayer();
          $block = $event->getBlock();
          $level = $this->level;
          if ($player->getLevel() === $level) {
            $skyblockArray = $this->skyblock->get("SkyBlock", []);
            $blockX = $block->getX();
            $blockY = $block->getY();
            $blockZ = $block->getZ();
            $islandOwner = "";
            foreach (array_keys($skyblockArray) as $skyblocks) {
              $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
              $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
              $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
              $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
              $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
              $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];
              if ($blockX > $startX && $blockY > $startY && $blockZ > $startZ && $blockX < $endX && $blockY < $endY && $blockZ < $endZ) {
                $islandOwner = $skyblocks;
                break;
              }
            }
            if ($islandOwner === "") {
              $event->setCancelled(true);
              $player->sendMessage(TextFormat::RED . "You cannot break blocks here!");
              return;
            }
            elseif (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {
              return;
            }
            else {
              $event->setCancelled(true);
              $player->sendMessage(TextFormat::RED . "You cannot break blocks here!");
              return;
            }
          }
        }
        public function onInteract(PlayerInteractEvent $event) {
          $player = $event->getPlayer();
          $block = $event->getBlock();
          $item = $event->getItem();
          $level = $this->level;
          if ($block->getID() === 54 || $block->getID() === 61 || $block->getID() === 62 || $block->getID() === 138 || $block->getID() === 130 || $item->getID() === 259) {
            if ($player->getLevel() === $level) {
              $skyblockArray = $this->skyblock->get("SkyBlock", []);
              $playerX = $player->getX();
              $playerY = $player->getY();
              $playerZ = $player->getZ();
              $islandOwner = "";
              foreach (array_keys($skyblockArray) as $skyblocks) {
                $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
                $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
                $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
                $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
                $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
                $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];
                if ($playerX > $startX && $playerY > $startY && $playerZ > $startZ && $playerX < $endX && $playerY < $endY && $playerZ < $endZ) {
                  $islandOwner = $skyblocks;
                  break;
                }
              }
              if ($islandOwner === "") {
                $event->setCancelled(true);
                $player->sendMessage(TextFormat::RED . "You cannot use this here!");
                return;
              }
              elseif (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {
                return;
              }
              else {
                $event->setCancelled(true);
                $player->sendMessage(TextFormat::RED . "You cannot use this here!");
                return;
              }
            }
          }
        }
        public function onBucketEvent(PlayerBucketEvent $event) {
          $player = $event->getPlayer();
          $level = $this->level;
          if ($player->getLevel() === $level) {
            $skyblockArray = $this->skyblock->get("SkyBlock", []);
            $playerX = $player->getX();
            $playerY = $player->getY();
            $playerZ = $player->getZ();
            $islandOwner = "";
            foreach (array_keys($skyblockArray) as $skyblocks) {
              $startX = $skyblockArray[$skyblocks]["Area"]["start"]["X"];
              $startY = $skyblockArray[$skyblocks]["Area"]["start"]["Y"];
              $startZ = $skyblockArray[$skyblocks]["Area"]["start"]["Z"];
              $endX = $skyblockArray[$skyblocks]["Area"]["end"]["X"];
              $endY = $skyblockArray[$skyblocks]["Area"]["end"]["Y"];
              $endZ = $skyblockArray[$skyblocks]["Area"]["end"]["Z"];
              if ($playerX > $startX && $playerY > $startY && $playerZ > $startZ && $playerX < $endX && $playerY < $endY && $playerZ < $endZ) {
                $islandOwner = $skyblocks;
                break;
              }
            }
            if ($islandOwner === "") {
              $event->setCancelled(true);
              $player->sendMessage(TextFormat::RED . "You cannot use this here!");
              return;
            }
            elseif (in_array($player->getName(), $skyblockArray[$islandOwner]["Members"])) {
              return;
            }
            else {
              $event->setCancelled(true);
              $player->sendMessage(TextFormat::RED . "You cannot use this here!");
              return;
            }
          }
        }
        public function sendHelp(Player $player, int $pageNumber) {
          if ($pageNumber <= 2) {
            switch($pageNumber) {
              case "1":
                $player->sendMessage(TextFormat::WHITE . "-=" . TextFormat::RED . "SkyBlock Help Menu " . TextFormat::GRAY . "({$pageNumber}, 2)" . TextFormat::WHITE . "=- \n" . TextFormat::GRAY . "Use /help [number] to open a specific help menu. \n" . TextFormat::BLUE . "/is: " . TextFormat::WHITE . "This is the main SkyBlock command. \n" . TextFormat::BLUE . "/is tp [player]: " . TextFormat::WHITE . "Use this to teleport to your island or another player's \n" . TextFormat::BLUE . "/is lock: " . TextFormat::WHITE . "This command will lock your island from visitors. \n" . TextFormat::BLUE . "/is unlock: " . TextFormat::WHITE . "This command opens your island to visitors.");
                return;
              break;
              case "2":
                $player->sendMessage(TextFormat::WHITE . "-=" . TextFormat::RED . "SkyBlock Help Menu " . TextFormat::GRAY . "({$pageNumber}, 2)" . TextFormat::WHITE . "=- \n" . TextFormat::GRAY . "Use /help [number] to open a specific help menu. \n" . TextFormat::BLUE . "/is setworld: " . TextFormat::WHITE . "This admin command is used to set the current SkyBlock world.");
                return;
              break;
            }
          } else {
            $player->sendMessage(TextFormat::RED . $pageNumber . " is not a valid page number.");
            return;
          }
        }
      }
     

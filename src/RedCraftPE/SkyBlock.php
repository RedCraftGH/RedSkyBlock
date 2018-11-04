<?php

namespace RedCraftPE;

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class SkyBlock extends PluginBase implements Listener {

  public function onEnable(): void {
  
  
  }
  public function onDisable(): void {
  
  
  }
  public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
  
    switch(strtolower($command->getName())) {
    
      case "island":
        
       if (!$args) {
         
         if (array_key_exists(strtolower($sender->getName()))) {
         
         
         } else {
         
           if ($sender->hasPermission("skyblock.create")) {
           
           
           } else {
           
             $sender->sendMessage("Unfortunately, you do not have access to this SkyBlock command.");
             return true;
           }
         }
       }
      break;
    }
  }
  return false;
}

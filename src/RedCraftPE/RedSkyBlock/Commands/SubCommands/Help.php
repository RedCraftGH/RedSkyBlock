<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class Help {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onHelpCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.help")) {

      if (count($args) < 1) {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is help <page#>");
        return true;
      } else {

        $page = intval($args[1]);
        if ($page === 1) {

          $sender->sendMessage(TextFormat::AQUA . "SkyBlock Help Page {$page}: \n" . TextFormat::WHITE . "/is add <player>: Add a player to your island \n" . "/is ban <player>: Ban a player from your island \n" . "/is banned: See who is banned from your island \n" . "/is create: Create an island \n" . "/is createworld <world name>: Create a SkyBlock world");
          return true;
        } elseif ($page === 2) {

          $sender->sendMessage(TextFormat::AQUA . "SkyBlock Help Page {$page}: \n" . TextFormat::WHITE . "/is delete <player>: Delete a player's island \n" . "/is fly: Enable/Disable flight \n" . "/is help <page#>: See the SkyBlock commands \n" . "/is kick <player(s)>: Kick players off of your island \n" . "/is leave <player>: Leave another person's island");
          return true;
        } elseif ($page === 3) {

          $sender->sendMessage(TextFormat::AQUA . "SkyBlock Help Page {$page}: \n" . TextFormat::WHITE . "/is lock <on/off>: Lock/Unlock your island \n" . "/is members: See who is a member of your island \n" . "/is name [name]: Check or set your island's name \n" . "/is nether: Go to the nether! \n" . "/is netherspawn: Set spawn in the nether");
          return true;
        } elseif ($page === 4) {

          $sender->sendMessage(TextFormat::AQUA . "SkyBlock Help Page {$page}: \n" . TextFormat::WHITE . "/is netherzone <1/2>: Set nether custom island zone \n" . "/is on: See who is on your island \n" . "/is rank: Check your island's ranking \n" . "/is reload: Reload skyblock data \n" . "/is remove <player>: Remove a player from your island");
          return true;
        } elseif($page === 5) {

          $sender->sendMessage(TextFormat::AQUA . "SkyBlock Help Page {$page}: \n" . TextFormat::WHITE . "/is restart: Restart your island \n" . "/is setspawn: Set your island spawn \n" . "/is setworld: Set the SkyBlock master world \n" . "/is setzone <1/2>: Set the custom island zone \n" . "/is size <size> <player>: Change the island size of players");
          return true;
        } elseif($page === 6) {

          $sender->sendMessage(TextFormat::AQUA . "SkyBlock Help Page {$page}: \n" . TextFormat::WHITE . "/is teleport [player]: Go to your or another island \n" . "/is top: Check the top islands \n" . "/is unban <player>: Unban a player from your island \n" . "/is updatezone [nether]: Update the custom [nether] island zone \n" . "/is value: Check your island value");
          return true;
        } else {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is help <page#>");
          return true;
        }
      }
      return true;
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
    }
  }
}

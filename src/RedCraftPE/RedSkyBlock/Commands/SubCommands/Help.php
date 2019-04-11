<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;

use RedCraftPE\RedSkyBlock\Commands\Island;

class Help {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }

  public function onHelpCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("skyblock.help")) {

      if (count($args) < 2) {

        $sender->sendMessage(TextFormat::RED . "SkyBlock Help Menu (1/6): \n" . TextFormat::WHITE . "/is add <player>: Use this command to add a player to your island. \n" . "/is ban <player>: This command is not implemented yet! \n" . "/is create: Use this command to create a SkyBlock island. \n" . "/is createworld <world name>: Use this command to create a SkyBlock world.");
        return true;
      } else {

        if (is_numeric($args[1])) {

          $page = intval($args[1]);
          if ($page === 1) {

            $sender->sendMessage(TextFormat::RED . "SkyBlock Help Menu (1/6): \n" . TextFormat::WHITE . "/is add <player>: Use this command to add a player to your island. \n" . "/is ban <player>: This command is not implemented yet! \n" . "/is create: Use this command to create a SkyBlock island. \n" . "/is createworld <world name>: Use this command to create a SkyBlock world.");
            return true;
          } else if ($page === 2) {

            $sender->sendMessage(TextFormat::RED . "SkyBlock Help Menu (2/6): \n" . TextFormat::WHITE . "/is custom <on/off>: This command enables/disabled custom SkyBlock islands. \n" . "/is delete <player>: This command is not yet implemented. \n" . "/is help [page#]: Use this command to open the SkyBlock help menu. \n" . "/is hunger <on/off>: Use this command to control player's hunger in the SkyBlock world. \n" . "/is info <player>: Use this command to see another player's SkyBlock information.");
            return true;
          } else if ($page === 3) {

            $sender->sendMessage(TextFormat::RED . "SkyBlock Help Menu (3/6): \n" . TextFormat::WHITE . "/is kick <player>: Use this command to kick another player off of your island. \n" . "/is lock: Use this command to prevent visitors. \n" . "/is makespawn: Use this command to create a custom island spawnpoint. \n" . "/is members: Use this command to see the members of your island. \n" . "/is name: Use this command to change or see the name of your island.");
            return true;
          } else if ($page === 4) {

            $sender->sendMessage(TextFormat::RED . "SkyBlock Help Menu (4/6): \n" . TextFormat::WHITE . "/is pos1: This command sets the first position for custom islands. \n" . "/is pos2: This command sets the second position for custom islands. \n" . "/is rank: This command is not yet implemented. \n" . "/is reload: Use this command to reload SkyBlock's data. \n" . "/is remove <player>: Use this command to remove a member from your island. \n" . "/is reset: Use this command to completely reset your SkyBlock island.");
            return true;
          } else if ($page === 5) {

            $sender->sendMessage(TextFormat::RED . "SkyBlock Help Menu (5/6): \n" . TextFormat::WHITE . "/is set: This command sets the custom island data for custom islands. \n" . "/is setspawn: Use this command to set the spawn position of your island. \n" . "/is setworld: Use this command to set the SkyBlock world to the one your are in. \n" . "/is teleport [player]: Use this command to teleport to your island or another player's island. \n" . "/is top: This command is not yet implemented. \n" . "/is unlock: Use this command to open your island to visitors.");
            return true;
          } else if ($page === 6) {

            $sender->sendMessage(TextFormat::RED . "SkyBlock Help Menu (6/6): \n" . TextFormat::WHITE . "/is unban <player>: This command unbans a banned player from your island. \n" . "/is void <on/off>: Use this command to disable/enable the void in the SkyBlock world.");
            return true;
          } else {

            $sender->sendMessage(TextFormat::WHITE . $args[1] . TextFormat::RED . " is not a valid help page number.");
            return true;
          }
        } else {

          $sender->sendMessage(TextFormat::WHITE . $args[1] . TextFormat::RED . " is not a valid help page number.");
          return true;
        }
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

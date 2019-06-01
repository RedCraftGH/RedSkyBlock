<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\tile\Chest;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BlockEntityDataPacket;
use pocketmine\nbt\NetworkLittleEndianNBTStream;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\EventListener;
use RedCraftPE\RedSkyBlock\Commands\Island;

class Settings {

  private static $instance;

  public function __construct() {

    self::$instance = $this;
  }
  public function onSettingsCommand(CommandSender $sender, array $args): bool {

    $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
    $senderName = strtolower($sender->getName());

    if ($sender->hasPermission("skyblock.island.settings")) {

      $build = $skyblockArray[$senderName]["Settings"]["Build"];
      $break = $skyblockArray[$senderName]["Settings"]["Break"];
      $pickup = $skyblockArray[$senderName]["Settings"]["Pickup"];
      $anvil = $skyblockArray[$senderName]["Settings"]["Anvil"];
      $chest = $skyblockArray[$senderName]["Settings"]["Chest"];
      $craftingTable = $skyblockArray[$senderName]["Settings"]["CraftingTable"];
      $fly = $skyblockArray[$senderName]["Settings"]["Fly"];
      $hopper = $skyblockArray[$senderName]["Settings"]["Hopper"];
      $brewing = $skyblockArray[$senderName]["Settings"]["Brewing"];
      $beacon = $skyblockArray[$senderName]["Settings"]["Beacon"];
      $buckets = $skyblockArray[$senderName]["Settings"]["Buckets"];
      $pvp = $skyblockArray[$senderName]["Settings"]["PVP"];
      $flintAndSteel = $skyblockArray[$senderName]["Settings"]["FlintAndSteel"];
      $furnace = $skyblockArray[$senderName]["Settings"]["Furnace"];
      $enderChest = $skyblockArray[$senderName]["Settings"]["EnderChest"];

      if (count($args) === 1) {

        if (array_key_exists(strtolower($sender->getName()), $skyblockArray)) {

          $sender->sendMessage(TextFormat::AQUA . "Settings: \n Build Protection: {$build} \n Break Protection: {$break} \n Pickup Protection: {$pickup} \n Anvil Protection: {$anvil} \n Chest Protection: {$chest} \n Crafting Tables: {$craftingTable} \n Flying: {$fly} \n Hopper Protection: {$hopper} \n Brewing: {$brewing} \n Beacon Protection: {$beacon} \n Bucket Protection: {$buckets} \n PVP: {$pvp} \n Flint and Steel Protection: {$flintAndSteel} \n Furnace Protection: {$furnace} \n Ender Chests: {$enderChest}");
          return true;
        } else {

          $sender->sendMessage(TextFormat::RED . "You do not have an island yet.");
          return true;
        }
      } else if (count($args) === 2) {

        if (array_key_exists(strtolower($sender->getName()), $skyblockArray)) {

          $flag = $args[1];

          if (strtolower($flag) === "flags") {

            $sender->sendMessage(TextFormat::AQUA . "Island Settings Flags (Flags are case sensetive): \n Build \n Break \n Pickup \n Anvil \n Chest \n CraftingTable \n Fly \n Hopper \n Brewing \n Beacon \n Buckets \n PVP \n FlintAndSteel \n Furnace \n EnderChest");
            return true;
          }

          if (isset($skyblockArray[$senderName]["Settings"][$flag])) {

            if ($skyblockArray[$senderName]["Settings"][$flag] === "on") {

              $skyblockArray[$senderName]["Settings"][$flag] = "off";
              SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
              SkyBlock::getInstance()->skyblock->save();
              $sender->sendMessage(TextFormat::GREEN . "Your island settings have been updated!");
              return true;
            } else {

              $skyblockArray[$senderName]["Settings"][$flag] = "on";
              SkyBlock::getInstance()->skyblock->set("SkyBlock", $skyblockArray);
              SkyBlock::getInstance()->skyblock->save();
              $sender->sendMessage(TextFormat::GREEN . "Your island settings have been updated!");
              return true;
            }
          } else {

            $sender->sendMessage(TextFormat::RED . "The flag you've specified does not exist!");
            return true;
          }
        } else {

          $sender->sendMessage(TextFormat::RED . "You do not have an island yet.");
          return true;
        }
      } else {

        $sender->sendMessage(TextFormat::WHITE . "Usage: /is settings [flag] [true/false]");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
}

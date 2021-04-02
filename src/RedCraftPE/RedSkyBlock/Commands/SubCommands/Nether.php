<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;
use pocketmine\level\Level;

use RedCraftPE\RedSkyBlock\Tasks\NetherGenerate;

class Nether {

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onNetherCommand(CommandSender $sender): bool {

    if ($sender->hasPermission("skyblock.nether")) {

      $plugin = $this->plugin;

      if ($plugin->cfg->get("Nether Islands")) {

        if ($plugin->getServer()->getLevelByName($plugin->skyblock->get("Master World") . "-Nether") instanceof Level) {

          $skyblockArray = $plugin->skyblock->get("SkyBlock", []);
          $senderName = strtolower($sender->getName());

          if (array_key_exists($senderName, $skyblockArray)) {

            if ($plugin->skyblock->get("Nether Zone Created")) {

              $filePath = $plugin->getDataFolder() . "Players/" . $senderName . ".json";
              $playerDataEncoded = file_get_contents($filePath);
              $playerData = (array) json_decode($playerDataEncoded);
              $netherWorld = $plugin->getServer()->getLevelByName($plugin->skyblock->get("Master World") . "-Nether");

              if ($playerData["Nether Spawn"] === []) {

                //only runs if this is the first time the player runs this command. activates nether island generation and sends player to nether world
                $playerData["Nether Spawn"] = $playerData["Island Spawn"];
                $x = $playerData["Nether Spawn"][0];
                $y = $playerData["Nether Spawn"][1];
                $z = $playerData["Nether Spawn"][2];
                $sender->teleport(new Position($x, $y, $z, $netherWorld));
                $sender->setImmobile(true);
                //generate nether island:
                $plugin->getScheduler()->scheduleDelayedTask(new NetherGenerate($plugin, $sender, $x - 3, $z - 3, $netherWorld), 50); //- 3 & - 3 there to sync where nether and overworld islands are spawned.
                $sender->sendMessage(TextFormat::GREEN . "You have created your Nether SkyBlock Island.");

                $playerDataEncoded = json_encode($playerData);
                file_put_contents($filePath, $playerDataEncoded);

                return true;
              } else {

                //send player to nether world
                $x = $playerData["Nether Spawn"][0];
                $y = $playerData["Nether Spawn"][1];
                $z = $playerData["Nether Spawn"][2];
                $sender->teleport(new Position($x, $y, $z, $netherWorld));
                return true;
              }
            } else {

              $sender->sendMessage(TextFormat::RED . "A custom nether island zone must be created before this command can be used.");
              return true;
            }
          } else {

            $sender->sendMessage(TextFormat::RED . "You don't have a SkyBlock island yet.");
            return true;
          }
        } else {

          $plugin->getServer()->generateLevel($plugin->skyblock->get("Master World") . "-Nether", null, 'pocketmine\level\generator\Flat', ["preset" => "3;minecraft:air;8;"]);
          return $this->onNetherCommand($sender);
        }
      } else {

        $sender->sendMessage(TextFormat::RED . "Nether SkyBlock has been disabled.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

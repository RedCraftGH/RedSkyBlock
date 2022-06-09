<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\utils\TextFormat;
use pocketmine\command\CommandSender;
use pocketmine\world\WorldCreationOptions;
use pocketmine\world\generator\FlatGeneratorOptions;
use pocketmine\world\generator\GeneratorManager;

use RedCraftPE\RedSkyBlock\SkyBlock;
use RedCraftPE\RedSkyBlock\Commands\Island;

class CreateWorld {

  private $worldGenerator;

  public function __construct($plugin) {

    $this->plugin = $plugin;
  }

  public function onCreateWorldCommand(CommandSender $sender, array $args): bool {

    if ($sender->hasPermission("redskyblock.createworld")) {

        if (count($args) < 2) {

          $sender->sendMessage(TextFormat::WHITE . "Usage: /is createworld <world name>");
          return true;
        } else {

          $plugin = $this->plugin;

          $world = (string) implode(" ", array_slice($args, 1));

          if ($plugin->getServer()->getWorldManager()->loadWorld($world)) {

            $sender->sendMessage(TextFormat::RED . "The world you are trying to create already exists.");
            return true;
          } else {

            $generator = GeneratorManager::getInstance()->getGenerator("void")->getGeneratorClass();
            $creationOptions = WorldCreationOptions::create();
            $creationOptions->setGeneratorClass($generator);

            $plugin->getServer()->getWorldManager()->generateWorld($world, $creationOptions);
            if ($plugin->cfg->get("Nether Islands")) {

              $plugin->getServer()->getWorldManager()->generateWorld($world . "-Nether", $creationOptions);
              $sender->sendMessage(TextFormat::GREEN . "The empty worlds " . TextFormat::WHITE . $world . TextFormat::GREEN . " and " . TextFormat::WHITE . "{$world}-Nether" . TextFormat::GREEN . " have been created for SkyBlock.");
              return true;
            } else {

              $sender->sendMessage(TextFormat::GREEN . "The empty world " . TextFormat::WHITE . $world . TextFormat::GREEN . " has been created for SkyBlock.");
              return true;
            }
          }
        }
    } else {

      $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
      return true;
    }
  }
}

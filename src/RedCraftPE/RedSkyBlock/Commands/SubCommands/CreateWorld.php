<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\world\WorldCreationOptions;
use pocketmine\world\generator\GeneratorManager;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\RawStringArgument;

class CreateWorld extends SBSubCommand {

  protected function prepare(): void {

    $this->setPermission("redskyblock.admin;redskyblock.createworld");
    $this->registerArgument(0, new RawStringArgument("name", false));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if (isset($args["name"])) {

      $name = $args["name"];
      $plugin = $this->plugin;

      if (!$plugin->getServer()->getWorldManager()->loadWorld($name)) {

        $generator = GeneratorManager::getInstance()->getGenerator("void")->getGeneratorClass();
        $creationOptions = WorldCreationOptions::create();
        $creationOptions->setGeneratorClass($generator);

        $plugin->getServer()->getWorldManager()->generateWorld($name, $creationOptions);

        if ($plugin->cfg->get("Nether Islands")) {

          $plugin->getServer()->getWorldManager()->generateWorld($name . "-Nether", $creationOptions);
          $message = $this->getMShop()->construct("CW_NETHER");
          $message = str_replace("{WORLD}", $name, $message);
          $sender->sendMessage($message);
          return;
        } else {

          $message = $this->getMShop()->construct("CW");
          $message = str_replace("{WORLD}", $name, $message);
          $sender->sendMessage($message);
          return;
        }
      } else {

        $message = $this->getMShop()->construct("CW_EXISTS");
        $message = str_replace("{WORLD}", $name, $message);
        $sender->sendMessage($message);
        return;
      }
    } else {

      $this->sendUsage();
      return;
    }
  }
}

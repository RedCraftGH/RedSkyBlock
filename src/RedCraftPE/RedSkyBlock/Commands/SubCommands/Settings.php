<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\item\StringToItemParser;
use pocketmine\item\ItemFactory;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;

class Settings extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if ($this->checkIsland($sender)) {

      $island = $this->plugin->islandManager->getIsland($sender);
      $islandSettings = $island->getSettings();

      $menu = InvMenu::create(InvMenu::TYPE_CHEST);
      $menu->setName(TextFormat::RED . TextFormat::BOLD . $island->getName() . TextFormat::RESET . " Settings");

      $slotTracker = 0;

      foreach($islandSettings as $setting => $bias) {

        $itemName = implode(" ", explode("_", $setting));

        if ($bias) {

          $item = ItemFactory::getInstance()->get(StringToItemParser::getInstance()->parse("stained_glass_pane")->getId(), 5);
          $biasString = "Enabled";
        } else {

          $item = ItemFactory::getInstance()->get(StringToItemParser::getInstance()->parse("stained_glass_pane")->getId(), 14);
          $biasString = "Disabled";
        }
        $item->setCustomName($itemName);
        $item->setLore([$biasString]);
        $menuInventory = $menu->getInventory();

        $slot = $slotTracker;
        if ($slot % 2 !== 0) {

          $slot += 1;
        }

        $menuInventory->setItem($slot, $item);
        $slotTracker = $slot + 1;
      }

      $menu->setListener(InvMenu::readonly(function(DeterministicInvMenuTransaction $transaction) use ($islandSettings, $island, $sender, $aliasUsed, $args) : void {

        $itemClicked = $transaction->getItemClicked();
        $itemName = $itemClicked->getCustomName();
        $settingName = implode("_", explode(" ", $itemName));
        $settingBias = $islandSettings[$settingName];
        if ($settingBias) {

          $settingBias = false;
        } else {

          $settingBias = true;
        }
        $island->changeSetting($settingName, $settingBias);
        $transaction->getPlayer()->removeCurrentWindow();
        $this->onRun($sender, $aliasUsed, $args);
      }));
      $menu->send($sender);
    } else {

      $message = $this->getMShop()->construct("NO_ISLAND");
      $sender->sendMessage($message);
    }
  }
}

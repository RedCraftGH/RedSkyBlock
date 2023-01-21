<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\item\VanillaItems;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\constraint\InGameRequiredConstraint;

use muqsit\invmenu\InvMenu;

class Members extends SBSubCommand {

  public function prepare(): void {

    $this->addConstraint(new InGameRequiredConstraint($this));
    $this->setPermission("redskyblock.island");
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    if ($this->checkIsland($sender)) {

      $island = $this->plugin->islandManager->getIsland($sender);
      $members = $island->getMembers();
      $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
      $menu->setName(TextFormat::RED . TextFormat::BOLD . $island->getName() . TextFormat::RESET . " Members");

      foreach($members as $member => $rank) {

        $item = VanillaItems::PLAYER_HEAD();
        $item->setCustomName($member);
        $item->setLore([$rank]);
        $menu->getInventory()->addItem($item);
      }

      $menu->setListener(InvMenu::readonly());
      $menu->send($sender);
    } else {

      $message = $this->getMShop()->construct("NO_ISLAND");
      $sender->sendMessage($message);
    }
  }
}

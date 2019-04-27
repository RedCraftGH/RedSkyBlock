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
  public function onSettingsCommand(CommandSender $sender): bool {

    $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);

    if ($sender->hasPermission("skyblock.island.settings")) {

      if (array_key_exists(strtolower($sender->getName()), $skyblockArray)) {

        $this->createSettingsInventory($sender);
        $sender->sendMessage(TextFormat::GREEN . "Island Settings Menu Opened.");
        return true;
      } else {

        $sender->sendMessage(TextFormat::RED . "You do not have an island yet.");
        return true;
      }
    } else {

      $sender->sendMessage(TextFormat::RED . "You do not have the proper permissions to run this command.");
      return true;
    }
  }
  public function createSettingsInventory(Player $player) {

    $skyblockArray = SkyBlock::getInstance()->skyblock->get("SkyBlock", []);
    $name = strtolower($player->getName());
    $xPos = (int) $player->getX();
    $yPos = (int) $player->getY();
    $zPos = (int) $player->getZ();

    $item = Item::get(Item::CHEST, 0, 1)->setCustomName(TextFormat::RED . $skyblockArray[strtolower($player->getName())]["Name"] . " Settings:");

    $block = Block::get(Block::CHEST);
    $block->x = $xPos;
    $block->y = $yPos + 3;
    $block->z = $zPos;
    $block->level = $player->getLevel();

    $block->level->sendBlocks([$player], [$block]);
    EventListener::getListener()->addFakeBlock($block);

    $nbt = Chest::createNBT(new Vector3($xPos, $yPos + 3, $zPos), null, $item, $player);
    $tile = Tile::createTile(Tile::CHEST, $player->getLevel(), $nbt);

    $pk = new BlockEntityDataPacket();
    $pk->x = $xPos;
    $pk->y = $yPos + 3;
    $pk->z = $zPos;
    $pk->namedtag = (new NetworkLittleEndianNBTStream())->write($nbt);

    $player->sendDataPacket($pk);

    $inv = $tile->getInventory();
    $inv->setItem(0, Item::get(Item::COBBLESTONE, 0, 1)->setCustomName("Build Protection")->setLore([$skyblockArray[$name]["Settings"]["Build"]]));
    $inv->setItem(2, Item::get(Item::DIAMOND_PICKAXE, 0, 1)->setCustomName("Break Protection")->setLore([$skyblockArray[$name]["Settings"]["Break"]]));
    $inv->setItem(4, Item::get(Item::GUNPOWDER, 0, 1)->setCustomName("Pickup Protection")->setLore([$skyblockArray[$name]["Settings"]["Pickup"]]));
    $inv->setItem(6, Item::get(Item::ANVIL, 0, 1)->setCustomName("Anvil Protection")->setLore([$skyblockArray[$name]["Settings"]["Anvil"]]));
    $inv->setItem(8, Item::get(Item::CHEST, 0, 1)->setCustomName("Chest Protection")->setLore([$skyblockArray[$name]["Settings"]["Chest"]]));
    $inv->setItem(9, Item::get(Item::CRAFTING_TABLE, 0, 1)->setCustomName("Crafting Table")->setLore([$skyblockArray[$name]["Settings"]["CraftingTable"]]));
    $inv->setItem(11, Item::get(Item::ELYTRA, 0, 1)->setCustomName("Flying")->setLore([$skyblockArray[$name]["Settings"]["Fly"]]));
    $inv->setItem(13, Item::get(Item::HOPPER, 0, 1)->setCustomName("Hopper Protection")->setLore([$skyblockArray[$name]["Settings"]["Hopper"]]));
    $inv->setItem(15, Item::get(Item::BREWING_STAND_BLOCK, 0, 1)->setCustomName("Brewing")->setLore([$skyblockArray[$name]["Settings"]["Brewing"]]));
    $inv->setItem(17, Item::get(Item::BEACON, 0, 1)->setCustomName("Beacon Protection")->setLore([$skyblockArray[$name]["Settings"]["Beacon"]]));
    $inv->setItem(18, Item::get(Item::BUCKET, 0, 1)->setCustomName("Buckets")->setLore([$skyblockArray[$name]["Settings"]["Buckets"]]));
    $inv->setItem(20, Item::get(Item::DIAMOND_SWORD, 0, 1)->setCustomName("PVP Protection")->setLore([$skyblockArray[$name]["Settings"]["PVP"]]));
    $inv->setItem(22, Item::get(Item::FLINT_STEEL, 0, 1)->setCustomName("Flint and Steel")->setLore([$skyblockArray[$name]["Settings"]["FlintAndSteel"]]));
    $inv->setItem(24, Item::get(Item::FURNACE, 0, 1)->setCustomName("Furnace Protection")->setLore([$skyblockArray[$name]["Settings"]["Furnace"]]));
    $inv->setItem(26, Item::get(Item::ENDER_CHEST, 0, 1)->setCustomName("Ender Chest")->setLore([$skyblockArray[$name]["Settings"]["EnderChest"]]));

    $player->addWindow($inv);
    EventListener::getListener()->addFakeInv($inv);
  }
  public static function getInstance(): self {

    return self::$instance;
  }
}

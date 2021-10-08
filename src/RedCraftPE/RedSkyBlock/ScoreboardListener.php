<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\event\listener;
use pocketmine\event\block\BlockBreakEvent;

use Ifera\ScoreHud\event\TagsResolveEvent;

class ScoreboardListener implements Listener {

  public function __construct($plugin) {

    $this->plugin = $plugin;
    $plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
  }

  public function onTagResolve(TagsResolveEvent $event) {

    $plugin = $this->plugin;
    $player = $event->getPlayer();
    $tag = $event->getTag();

    switch ($tag->getName()) {

      case "redskyblock.islename":

        $tag->setValue($plugin->getIslandName($player));
      break;
      case "redskyblock.islesize":

        $tag->setValue($plugin->getIslandSize($player));
      break;
      case "redskyblock.islevalue":

        $tag->setValue($plugin->getIslandValue($player));
      break;
      case "redskyblock.islerank":

        $tag->setValue("#" . $plugin->getIslandRank($player));
      break;
      case "redskyblock.islestatus":

        $status = $plugin->isIslandLocked($player);
        if ($status) {

          $tag->setValue("Locked");
        } else {

          $tag->setValue("Unlocked");
        }
      break;
      case "redskyblock.membercount":

        $memberArray = (array) $plugin->getIslandMembers($player);
        $memberCount = count($memberArray);

        $tag->setValue($memberCount);
      break;
    }
  }
}

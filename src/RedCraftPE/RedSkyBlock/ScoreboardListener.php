<?php

namespace RedCraftPE\RedSkyBlock;

use pocketmine\event\listener;

use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;

class ScoreboardListener implements Listener {

  public function __construct($plugin, $scoreHud) {

    $this->plugin = $plugin;
    $this->scoreHud = $scoreHud;
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

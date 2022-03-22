<?php

namespace RedCraftPE\RedSkyBlock;

use Ifera\ScoreHud\event\TagsResolveEvent;
use pocketmine\event\Listener;

class ScoreboardListener implements Listener{

	private SkyBlock $plugin;

	public function __construct($plugin){

		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function onTagResolve(TagsResolveEvent $event){

		$plugin = $this->plugin;
		$player = $event->getPlayer();
		$tag = $event->getTag();

		switch($tag->getName()){

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
				if($status == "N/A"){

					$tag->setValue("N/A");
				}elseif($status){

					$tag->setValue("Locked");
				}else{

					$tag->setValue("Unlocked");
				}
				break;
			case "redskyblock.membercount":

				$memberArray = $plugin->getIslandMembers($player);
				if($memberArray == "N/A"){
					$tag->setValue("N/A");
				}else{
					$memberCount = count((array) $memberArray);
					$tag->setValue($memberCount);
				}

				break;
		}
	}
}

<?php

namespace RedCraftPE\RedSkyBlock\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

use RedCraftPE\RedSkyBlock\Commands\SBSubCommand;

use CortexPE\Commando\args\IntegerArgument;

class TopIslands extends SBSubCommand {

  public function prepare(): void {

    $this->setPermission("redskyblock.island");
    $this->registerArgument(0, new IntegerArgument("page #", true));
  }

  public function onRun(CommandSender $sender, string $aliasUsed, array $args): void {

    $topIslands = $this->plugin->islandManager->getTopIslands();
    $islandNames = array_keys($topIslands);
    $pageCount = (int) ceil(count($topIslands) / 6);
    if ($pageCount === 0) $pageCount = 1;

    if (isset($args["page #"])) {

      $pageNumber = (int) $args["page #"];
    } else {

      $pageNumber = 1;
    }

    if ($pageNumber > $pageCount) $pageNumber = $pageCount;
    $pageNumber -= 1;
    $index = $pageNumber * 6;
    $islandsOnPage = array_slice($islandNames, $index, 6);

    $position1 = "#" . $index + 1 . "--N/A";
    $position2 = "#" . $index + 2 . "--N/A";
    $position3 = "#" . $index + 3 . "--N/A";
    $position4 = "#" . $index + 4 . "--N/A";
    $position5 = "#" . $index + 5 . "--N/A";
    $position6 = "#" . $index + 6 . "--N/A";

    for($x = 0; $x < count($islandsOnPage); $x++) {

      $rank = $index + ($x + 1);
      ${"position" . $x + 1} = "#{$rank}--" . $islandsOnPage[$x] . ": " . $topIslands[$islandsOnPage[$x]] . " value";
    }

    $message = $this->getMShop()->construct("TOP_ISLANDS");
    $message = str_replace("{PAGE_NUMBER}", $pageNumber + 1, $message);
    $message = str_replace("{TOTAL_PAGES}", $pageCount, $message);
    $message = str_replace("{POSITION_ONE}", $position1, $message);
    $message = str_replace("{POSITION_TWO}", $position2, $message);
    $message = str_replace("{POSITION_THREE}", $position3, $message);
    $message = str_replace("{POSITION_FOUR}", $position4, $message);
    $message = str_replace("{POSITION_FIVE}", $position5, $message);
    $message = str_replace("{POSITION_SIX}", $position6, $message);

    $sender->sendMessage($message);
  }
}

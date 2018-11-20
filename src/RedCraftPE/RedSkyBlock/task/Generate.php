<?PHP

namespace RedCraftPE\RedSkyBlock\task;

use RedCraftPE\SkyBlock;
use pocketmine\scheduler\Task;

class Generate extends Task {

  public function __construct($islands, $level, $interval, $sender) {

    $this->islands = $islands;
    $this->level = $level;
    $this->interval = $interval;
    $this->sender = $sender;
  }

  public function onRun(int $tick) : void {

    Skyblock::getInstance()->generateIsland($this->islands, $this->level, $this->interval);
    $this->sender->setImmobile(false);
    return;
  }
}

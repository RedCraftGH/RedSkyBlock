<?PHP

namespace RedCraftPE\RedSkyBlock\Tasks;

use pocketmine\scheduler\Task;
use pocketmine\level\Level;
use pocketmine\Player;

use RedCraftPE\RedSkyBlock\Generators\IslandGenerator;

class Generate extends Task {

  private $generator;

  public function __construct(int $islands, Level $level, int $interval, Player $sender) {

    $this->islands = $islands;
    $this->level = $level;
    $this->interval = $interval;
    $this->sender = $sender;

    $this->generator = new IslandGenerator();
  }

  public function onRun(int $tick) : void {

    $this->generator->generateIsland($this->level, $this->interval, $this->islands);
    $this->sender->setImmobile(false);
    return;
  }
}

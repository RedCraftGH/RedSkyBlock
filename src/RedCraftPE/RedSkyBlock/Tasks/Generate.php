<?PHP

namespace RedCraftPE\RedSkyBlock\Tasks;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\block\BlockFactory;
use pocketmine\world\World;

class Generate extends Task {

  private $generator;

  public function __construct($plugin, Player $sender, Int $lastX, Int $lastZ, World $world) {

    $this->plugin = $plugin;
    $this->sender = $sender;
    $this->lastX = $lastX;
    $this->lastZ = $lastZ;
    $this->world = $world;
  }

  public function onRun() : void {

    $plugin = $this->plugin;
    $lastX = $this->lastX;
    $lastZ = $this->lastZ;
    $world = $this->world;

    $islandZone = $plugin->cfg->get("Island Zone");
    $islandBlocks = $plugin->skyblock->get("Island Blocks");
    $counter = 0;
    $x1 = (int) $islandZone[0];
    $y1 = (int) $islandZone[1];
    $z1 = (int) $islandZone[2];
    $x2 = (int) $islandZone[3];
    $y2 = (int) $islandZone[4];
    $z2 = (int) $islandZone[5];

    for ($x = $lastX; $x <= $lastX + (max($x1, $x2) - min($x1, $x2)); $x++) {

      for ($y = 80; $y <= 80 + (max($y1, $y2) - min($y1, $y2)); $y++) {

        for ($z = $lastZ; $z <= $lastZ + (max($z1, $z2) - min($z1, $z2)); $z++) {

          $block = explode(" ", $islandBlocks[$counter]);

          $world->setBlock(new Vector3($x, $y, $z), BlockFactory::getInstance()->get($block[0], $block[1]), false);
          $counter++;
        }
      }
    }

    $this->sender->setImmobile(false);
    return;
  }
}

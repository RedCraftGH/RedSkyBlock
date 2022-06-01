<?PHP

namespace RedCraftPE\RedSkyBlock\Tasks;

use pocketmine\block\BlockFactory;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\World;

class NetherGenerate extends Task{


	public $plugin;

	public $sender;

	public $x;

	public $z;

	public $netherWorld;

	public function __construct($plugin, Player $sender, int $x, int $z, World $netherWorld){

		$this->plugin = $plugin;
		$this->sender = $sender;
		$this->x = $x;
		$this->z = $z;
		$this->netherWorld = $netherWorld;
	}

	public function onRun() : void{

		$plugin = $this->plugin;
		$spawnX = $this->x;
		$spawnZ = $this->z;
		$netherWorld = $this->netherWorld;

		$netherZone = $plugin->cfg->get("Nether Zone");
		$netherBlocks = $plugin->skyblock->get("Nether Blocks");
		$counter = 0;
		$x1 = (int) $netherZone[0];
		$y1 = (int) $netherZone[1];
		$z1 = (int) $netherZone[2];
		$x2 = (int) $netherZone[3];
		$y2 = (int) $netherZone[4];
		$z2 = (int) $netherZone[5];

		for($x = $spawnX; $x <= $spawnX + (max($x1, $x2) - min($x1, $x2)); $x++){

			for($y = 80; $y <= 80 + (max($y1, $y2) - min($y1, $y2)); $y++){

				for($z = $spawnZ; $z <= $spawnZ + (max($z1, $z2) - min($z1, $z2)); $z++){

					$block = explode(" ", $netherBlocks[$counter]);
					$netherWorld->loadChunk($x, $z);
					$netherWorld->setBlock(new Vector3($x, $y, $z), BlockFactory::getInstance()->get($block[0], $block[1]), false);
					$counter++;
				}
			}
		}

		$this->sender->setImmobile(false);
		return;
	}
}

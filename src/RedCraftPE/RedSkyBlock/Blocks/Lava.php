<?php

namespace RedCraftPE\RedSkyBlock\Blocks;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityCombustByBlockEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\block\Water;
use pocketmine\block\BlockFactory;

use RedCraftPE\RedSkyBlock\SkyBlock;

class Lava extends \pocketmine\block\Lava {

	protected $id = self::FLOWING_LAVA;

	public function __construct(int $meta = 0) {

		$this->meta = $meta;
	}

	public function getLightLevel(): int {

		return 15;
	}

	public function getName(): string {

		return "Lava";
	}

	public function getStillForm(): Block {

		return BlockFactory::get(Block::STILL_LAVA, $this->meta);
	}

	public function getFlowingForm(): Block {

		return BlockFactory::get(Block::FLOWING_LAVA, $this->meta);
	}

	public function getBucketFillSound(): int {

		return LevelSoundEventPacket::SOUND_BUCKET_FILL_LAVA;
	}

	public function getBucketEmptySound(): int {

		return LevelSoundEventPacket::SOUND_BUCKET_EMPTY_LAVA;
	}

	public function tickRate(): int {

		return 30;
	}

	public function getFlowDecayPerBlock(): int {

		return 2;
	}

	protected function checkForHarden() {

		$colliding = null;
		for ($side = 1; $side <= 5; ++$side) {

			$blockSide = $this->getSide($side);
			if ($blockSide instanceof Water) {

				$colliding = $blockSide;
				break;
			}
		}

		if ($colliding !== null) {

			if ($this->getDamage() === 0) {

				$this->liquidCollide($colliding, BlockFactory::get(Block::OBSIDIAN));
			} else if ($this->getDamage() <= 4) {

				if (SkyBlock::getInstance()->cfg->get("CobbleGen")) {

	        $oresArray = SkyBlock::getInstance()->cfg->get("MagicCobbleGen Ores", []);
	        if (array_sum($oresArray) > 100 || array_sum($oresArray) < 100) {

						$this->liquidCollide($colliding, BlockFactory::get(Block::COBBLESTONE));
						return;
					} else {

						$chance = rand(1, 100);
						$pChance = 0;
						foreach($oresArray as $ore) {

							$pChance += $ore;
							if ($chance <= $pChance) {

								$blockID = key($oresArray);
								break;
							}
							next($oresArray);
						}

						$this->liquidCollide($colliding, Block::get($blockID));
					}
	      } else {
	        $this->liquidCollide($colliding, BlockFactory::get(Block::COBBLESTONE));
	      }
			}
		}
	}

	protected function flowIntoBlock(Block $block, int $newFlowDecay): void {

		if ($block instanceof Water) {

			$block->liquidCollide($this, BlockFactory::get(Block::STONE));
		} else {

			parent::flowIntoBlock($block, $newFlowDecay);
		}
	}

	public function onEntityCollide(Entity $entity): void {

		$entity->fallDistance *= 0.5;

		$ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_LAVA, 4);
		$entity->attack($ev);

		$ev = new EntityCombustByBlockEvent($this, $entity, 15);
		$ev->call();

		if (!$ev->isCancelled()) {

			$entity->setOnFire($ev->getDuration());
		}

		$entity->resetFallDistance();
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool {

		$ret = $this->getLevel()->setBlock($this, $this, true, false);
		$this->getLevel()->scheduleDelayedBlockUpdate($this, $this->tickRate());

		return $ret;
	}
}

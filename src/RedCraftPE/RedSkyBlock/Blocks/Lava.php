<?php

declare(strict_types=1);

namespace RedCraftPE\RedSkyBlock\Blocks;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\Water;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityCombustByBlockEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use pocketmine\world\sound\Sound;
use RedCraftPE\RedSkyBlock\SkyBlock;

class Lava extends \pocketmine\block\Lava{

	public function __construct(int $meta, SkyBlock $plugin, BlockIdentifier $idInfo){

		$this->meta = $meta;
		$this->plugin = $plugin;
		$this->idInfo = $idInfo;
	}

	public function getLightLevel() : int{

		return 15;
	}

	public function getName() : string{

		return "Lava";
	}

	public function getStillForm() : Block{

		return BlockFactory::getInstance()->get(BlockLegacyIds::STILL_LAVA, $this->meta);
	}

	public function getFlowingForm() : Block{

		return BlockFactory::getInstance()->get(BlockLegacyIds::FLOWING_LAVA, $this->meta);
	}

	public function getBucketFillSound() : Sound{

		return LevelSoundEvent::BUCKET_FILL_LAVA;
	}

	public function getBucketEmptySound() : Sound{

		return LevelSoundEvent::BUCKET_EMPTY_LAVA;
	}

	public function tickRate() : int{

		return 30;
	}

	public function getFlowDecayPerBlock() : int{

		return 2; //TODO: this is 1 in the nether
	}

	protected function checkForHarden() : bool{

		$plugin = $this->plugin;

		$colliding = null;
		for($side = 1; $side <= 5; ++$side){ //don't check downwards side

			$blockSide = $this->getSide($side);
			if($blockSide instanceof Water){

				$colliding = $blockSide;
				break;
			}
		}

		if($colliding !== null){

			if($this->getDamage() === 0){

				$this->liquidCollide($colliding, BlockFactory::getInstance()->get(BlockLegacyIds::OBSIDIAN));
				return true;

			}elseif($this->getDamage() <= 4){

				$generatorOres = $plugin->cfg->get("Generator Ores", []);
				$masterWorld = $plugin->skyblock->get("Master World");

				if($this->level->getFolderName() === $masterWorld || $this->level->getFolderName() === $masterWorld . "-Nether"){

					if(count($generatorOres) === 0){

						$this->liquidCollide($colliding, BlockFactory::getInstance()->get(BlockLegacyIds::COBBLESTONE));
						return true;
					}else{

						if(array_sum($generatorOres) !== 100){

							$this->liquidCollide($colliding, BlockFactory::getInstance()->get(BlockLegacyIds::COBBLESTONE));
							return true;
						}else{

							$blockID;
							$randomNumber = rand(1, 100);
							$percentChance = 0;

							foreach($generatorOres as $key => $oreChance){

								$percentChance += $oreChance;

								if($randomNumber <= $percentChance){

									$blockID = $key;
									break;
								}
							}
							$this->liquidCollide($colliding, BlockFactory::getInstance()->get($blockID));
							return true;
						}
					}
				}else{

					$this->liquidCollide($colliding, BlockFactory::getInstance()->get(BlockLegacyIds::COBBLESTONE));
					return true;
				}
			}
		}
		return false;
	}

	protected function flowIntoBlock(Block $block, int $newFlowDecay, bool $falling) : void{

		if($block instanceof Water){

			$block->liquidCollide($this, BlockFactory::getInstance()->get(BlockLegacyIds::STONE));
		}else{

			parent::flowIntoBlock($block, $newFlowDecay, $falling);
		}
	}

	public function onEntityCollide(Entity $entity) : void{

		$entity->fallDistance *= 0.5;

		$ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_LAVA, 4);
		$entity->attack($ev);

		$ev = new EntityCombustByBlockEvent($this, $entity, 15);
		$ev->call();
		if(!$ev->isCancelled()){

			$entity->setOnFire($ev->getDuration());
		}

		$entity->resetFallDistance();
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{

		$ret = $this->getPosition()->getWorld()->setBlock($this, $this, true, false);
		$this->getPosition()->getWorld()->scheduleDelayedBlockUpdate($this, $this->tickRate());

		return $ret;
	}
}

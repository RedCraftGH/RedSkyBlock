<?php

namespace RedCraftPE\RedSkyBlock\Blocks;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityCombustByBlockEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\block\Block;
use pocketmine\block\Water;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;

use RedCraftPE\RedSkyBlock\SkyBlock;

class Lava extends \pocketmine\block\Lava {

	public function getLightLevel() : int{
		return 15;
	}
	public function getBucketFillSound() : int{
		return LevelSoundEventPacket::SOUND_BUCKET_FILL_LAVA;
	}
	public function getBucketEmptySound() : int{
		return LevelSoundEventPacket::SOUND_BUCKET_EMPTY_LAVA;
	}
	public function tickRate() : int{
		return 30;
	}
	public function getFlowDecayPerBlock() : int{
		return 2; //TODO: this is 1 in the nether
	}
	protected function checkForHarden() : void{
		$colliding = null;
		for ($side = 1; $side <= 6; $side++){
			if($side === 1){
				continue;
			}
			$blockSide = $this->getSide($side);
			if($blockSide instanceof Water){
				$colliding = $blockSide;
				break;
			}
		}
		if($colliding !== null) {

      if (SkyBlock::getInstance()->cfg->get("CobbleGen")) {

        $oresArray = SkyBlock::getInstance()->cfg->get("MagicCobbleGen Ores", []);
        $blockID = intval($oresArray[array_rand($oresArray)]);

        $this->liquidCollide($colliding, Block::get($blockID));
      } else {

        $this->liquidCollide($colliding, BlockFactory::get(BlockIds::COBBLESTONE));
      }
		}
	}
	protected function flowIntoBlock(Block $block, int $newFlowDecay) : void{
		if($block instanceof Water){
			$block->liquidCollide($this, BlockFactory::get(BlockIds::STONE));
		}else{
			parent::flowIntoBlock($block, $newFlowDecay);
		}
	}
	public function onEntityInside(Entity $entity) : void{
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
}

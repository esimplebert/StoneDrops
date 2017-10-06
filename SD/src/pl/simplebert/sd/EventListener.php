<?php
namespace pl\simplebert\sd;

use pl\simplebert\sd\SD;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\item\enchantment\Enchantment;

class EventListener implements Listener {
	private $sd;
	
	public function __construct(SD $sd) {
		$this->sd = $sd;
	}
	
	
	public function onBreak(BlockBreakEvent $e) {
		if(!$e->isCancelled()) {
			$player = $e->getPlayer();
			$inv = $player->getInventory();
			$level = $player->getLevel();
			$item = $e->getItem();
			$drop = false;
					
			if($player->isSurvival()) {
				$block = $e->getBlock();
				$loc = new Vector3($block->getX(), $block->getY(), $block->getZ());
			
				if($item->hasEnchantment(Enchantment::TYPE_MINING_FORTUNE, 1)) 	$doublechance = 5;
				elseif($item->hasEnchantment(Enchantment::TYPE_MINING_FORTUNE, 2)) 	$doublechance = 10;
				elseif($item->hasEnchantment(Enchantment::TYPE_MINING_FORTUNE, 3)) 	$doublechance = 20;
				else $doublechance = 0;
				
				if($block->getId() == Block::STONE) {
					foreach((array)$this->sd->config->get("drops") as $itemdrop) {
						if(!$player->hasPermission("stonedrops.vip")) $chance = $itemdrop["chance"];
						else $chance = $itemdrop["vipchance"];
					
						if($this->sd->chanceOf($chance)) {
							$tools = $itemdrop["tool"];
						
							if($itemdrop["max-y"] >= $block->getY()) {
								if(in_array($item->getId(), $tools)) {
									$quanity = $itemdrop["quanity"];
									$count = mt_rand($itemdrop["quanity"][0], $itemdrop["quanity"][1]);
									if($this->sd->chanceOf($doublechance)) $count *= 2;
									$di = Item::get($itemdrop["item"], 0 , $count);
														
									$e->setDrops(array(Item::get(0)));
									$player->addXp($itemdrop["xp"]);
									$level->dropItem($loc, $di);
									
									$drop = true;
								}
							}
						}
					}
					if(!$drop) {
						if($this->sd->config->get("eqdrop") == true) {
							if($inv->firstEmpty() != -1) {
								$e->setDrops(array(Item::get(0)));
								$drops = $block->getDrops($item);
								if($drops == null) return;
								
								$inv->addItem(Item::get($drops[0][0], $drops[0][1], $drops[0][2]));
							}
						}
					}
					return;
				}
				
				
				foreach((array)$this->sd->config->get("blocked") as $blocked) {
					if($block->getId() == $blocked) {	
						if($item->isPickaxe()) {
							$count = 1;
							$di = Item::get(4, 0, $count);
										
							$e->setDrops(array(Item::get(0)));
							$level->dropItem($loc, $di);
						}
						return;
					}
				}
				
				
				if($this->sd->config->get("eqdrop") == true) {
					if($inv->firstEmpty() != -1) {
						$e->setDrops(array(Item::get(0)));
						$drops = $block->getDrops($item);
						if($drops == null) return;
						
						$inv->addItem(Item::get($drops[0][0], $drops[0][1], $drops[0][2]));
						return;
					}
				}
			}
		}
		return;
	}
}

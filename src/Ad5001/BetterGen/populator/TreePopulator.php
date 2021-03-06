<?php
/**
 *  ____             __     __                    ____                       
 * /\  _`\          /\ \__ /\ \__                /\  _`\                     
 * \ \ \L\ \     __ \ \ ,_\\ \ ,_\     __   _ __ \ \ \L\_\     __     ___    
 *  \ \  _ <'  /'__`\\ \ \/ \ \ \/   /'__`\/\`'__\\ \ \L_L   /'__`\ /' _ `\  
 *   \ \ \L\ \/\  __/ \ \ \_ \ \ \_ /\  __/\ \ \/  \ \ \/, \/\  __/ /\ \/\ \ 
 *    \ \____/\ \____\ \ \__\ \ \__\\ \____\\ \_\   \ \____/\ \____\\ \_\ \_\
 *     \/___/  \/____/  \/__/  \/__/ \/____/ \/_/    \/___/  \/____/ \/_/\/_/
 * Tommorow's pocketmine generator.
 * @author Ad5001
 * @link https://github.com/Ad5001/BetterGen
 */

namespace Ad5001\BetterGen\populator;

use pocketmine\utils\Random;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use Ad5001\BetterGen\structure\SakuraTree;
use Ad5001\BetterGen\populator\AmountPopulator;
use Ad5001\BetterGen\Main;


class TreePopulator extends AmountPopulator {
	static $types = [ 
			"pocketmine\\level\\generator\\object\\OakTree",
			"pocketmine\\level\\generator\\object\\BirchTree",
			"Ad5001\\BetterGen\\structure\\SakuraTree" 
	];
	protected $level;
	protected $type;
	
	/*
	 * Constructs the class
	 */
	public function __construct($type = 0) {
		$this->type = $type;
		if(Main::isOtherNS()) {
			self::$types = [ 
				"pocketmine\\level\\generator\\normal\\object\\OakTree",
				"pocketmine\\level\\generator\\normal\\object\\BirchTree",
				"Ad5001\\BetterGen\\structure\\SakuraTree" 
			];
		}
	}
	
	/*
	 * Populate the chunk
	 * @param $level pocketmine\level\ChunkManager
	 * @param $chunkX int
	 * @param $chunkZ int
	 * @param $random pocketmine\utils\Random
	 */
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		for($i = 0; $i < $amount; $i++) {
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if ($y === -1) {
				continue;
			}
			$treeC = self::$types [$this->type];
			$tree = new  $treeC();
			$tree->placeObject($level, $x, $y, $z, $random);
		}
	}
	
	/*
	 * Gets the top block (y) on an x and z axes
	 * @param $x int
	 * @param $z int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = 127; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT or $b === Block::GRASS or $b === Block::PODZOL) {
				break;
			} elseif ($b !== 0 and $b !== Block::SNOW_LAYER) {
				return - 1;
			}
		}
		
		return $y++;
	}
}
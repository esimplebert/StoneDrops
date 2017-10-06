<?php
namespace pl\simplebert\sd;

use pl\simplebert\sd\commands\StoneDropsCommand;
use pl\simplebert\sd\commands\DropCommand;
use pl\simplebert\sd\EventListener;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class SD extends PluginBase {
	//todo
	//biomes
	const PREFIX = "[StoneDrops]";
	
	public $config;
	
	
	
	
	public function onLoad() {
		$this->getLogger()->info(TextFormat::GREEN . "Plugin has been loaded.");
	}
	
	
	public function onEnable() {
		if(!is_dir($this->getDataFolder())) {
			@mkdir($this->getDataFolder());
		}
		$this->saveDefaultConfig();
		$this->config = $this->getConfig();
		
		//register events
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		
		//register commands
		$this->getCommand("stonedrops")->setExecutor(new StoneDropsCommand($this));
		$this->getCommand("drop")->setExecutor(new DropCommand($this));
		
		$this->getLogger()->info(TextFormat::GREEN . "Plugin has been enabled.");
	}
	
	
	public function onDisable() {
		$this->getLogger()->info(TextFormat::RED . "Plugin has been disabled");
	}
	
	
	
	
	
	public function chanceOf($chance) {
		$dec = strlen(substr(strrchr($chance, "."), 1));
		
		$num = mt_rand(0, pow(10, $dec+2));
		if($dec != 0 )$chance *= pow(10, $dec);
			
		if($chance < $num) return false;
		else return true;
	}
}

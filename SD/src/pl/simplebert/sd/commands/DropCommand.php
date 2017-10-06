<?php
namespace pl\simplebert\sd\commands;

use pl\simplebert\sd\SD;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class DropCommand extends PluginBase implements CommandExecutor {
	private $sd;
	private $config;
	
	
	public function __construct(SD $sd) {
		$this->sd = $sd;
		$this->config = $sd->config;
	}
	
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		if(strtolower($cmd->getName()) == "drop") {
			if($sender->hasPermission("stonedrops.reload")) {
				$sender->sendMessage("");
				$sender->sendMessage(TextFormat::GREEN . "Drop:");
				foreach((array)$this->sd->config->get("drops") as $drop) {
					$name = array_search($drop, $this->sd->config->get("drops"));
					$chance = $drop["chance"];
					$vipchance = $drop["vipchance"];
					
					$sender->sendMessage(TextFormat::GREEN . "$name - $chance%, VIP: $vipchance%");
				}
				$sender->sendMessage("");
				return true;
			}
			else {
				$sender->sendMessage(TextFotmat::RED . "You haven't got permission to use this command!");
				return true;
			}
		}
		return false;
	}
}

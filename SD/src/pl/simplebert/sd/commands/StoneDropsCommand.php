<?php
namespace pl\simplebert\sd\commands;

use pl\simplebert\sd\SD;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class StoneDropsCommand extends PluginBase implements CommandExecutor {
	private $sd;
	private $config;
	
	
	public function __construct(SD $sd) {
		$this->sd = $sd;
		$this->config = $sd->config;
	}
	
	
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args) {
		if(strtolower($cmd->getName()) == "stonedrops") {
			if(count($args) == 1) {
				$args[0] = strtolower($args[0]);
				
				if($args[0] == "reload") {
					if($sender->hasPermission("stonedrops.reload")) {
						$this->sd->saveDefaultConfig();
						$this->sd->reloadConfig();
						
						if($sender instanceof Player) $sender->sendMessage(TextFormat::GREEN . SD::PREFIX . " Configuration reloaded.");
						$this->sd->getLogger()->info(TextFormat::GREEN . "Configuration reloaded.");
						return true;
					}
					else {
						$sender->sendMessage(TextFormat::RED . "You haven't got permission to use this command!");
						return true;
					}
				}
				elseif($args[0] == "info") {
					$plugin = $this->sd->getDescription();
					$info = array(
						"description" => $plugin->getDescription(),
						"author" => implode(", ", $plugin->getAuthors()),
						"version" => $plugin->getVersion(),
						"api" => implode(", ", $plugin->getCompatibleApis())
					);
					
					$sender->sendMessage("");
					$sender->sendMessage(TextFormat::GREEN . SD::PREFIX . " Plugin informations:");
					foreach($info as $name => $content) {
						$sender->sendMessage(TextFormat::GREEN . "$name: $content");
					}
					$sender->sendMessage("");
					return true;
				}
				else {
					$sender->sendMessage(TextFormat::RED . " Usage: " . $cmd->getUsage());
					return true;
				}
			}
			else {
				$sender->sendMessage(TextFormat::RED . " Usage: " . $cmd->getUsage());
				return true;
			}
		}
		return false;
	}
}

<?php

declare(strict_types=1);

namespace folosuru\ChatRecord;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\Listener;
use pocketmine\player;


class MainClass extends PluginBase implements Listener{



	public function onLoad() : void{
	}

	public function onEnable() : void{
		date_default_timezone_set("Asia/Tokyo");
		$this->chatlog  = "chat_log:Server started at ".date("Y-m-d H:i:s")."\n";
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getScheduler()->scheduleRepeatingTask(new DataSaveTask($this->getServer()), 6000);

	}
	public function onDisable() : void{
		$path = "plugin_data/ChatRecord/" . date("Y/m/d/");
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		$file = $path."chatlog-" . date("H").".txt";
		file_put_contents($file,$this->chatlog );
	}

	public function onPlayerCommand(PlayerCommandPreprocessEvent $event) {
		if ($event->isCancelled()) return;
		$args = explode(" ",$event->getMessage());
		$command = array_shift($args);	//	[/tell] folosuru hogehoge
		if (strtolower($command) === "/tell" or strtolower($command) === "/w" or strtolower($command) === "/msg"){
			if (count($args) < 2 ) return;
			$player = $this->getServer()->getPlayer(array_shift($args));	//	/tell [folosuru] hogehoge
			if($event->getPlayer() === $player) {
				return;
			}
			if($player instanceof Player) $this->getLogger()->info("text");
			$this->chatlog = $this->chatlog."[".date("Y-m/d H:i:s")."]  <".$event->getPlayer()->getName()." => ".$player->getName()."> ".implode(" ", $args)."\n";
		}
		if (strtolower($command) === "/me" or strtolower($command) === "/say"){
			$this->chatlog = $this->chatlog."[".date("Y-m/d H:i:s")."]  <".$event->getPlayer()->getName()."> ".implode(" ", $args)."\n";
		}

	}

	public function onChat(PlayerChatEvent $event){
		$this->chatlog = $this->chatlog."[".date("Y-m/d H:i:s")."]  <".$event->getPlayer()->getName()."> ".$event->getMessage()."\n";
	}
	public function onJoin(PlayerJoinEvent $event){
		$this->chatlog = $this->chatlog."[".date("Y-m/d H:i:s")."]  [Player_Join]".$event->getPlayer()->getName()."\n";
	}
	public function onQuit(PlayerQuitEvent $event){
		$this->chatlog = $this->chatlog."[".date("Y-m/d H:i:s")."]  [Player_Quit]".$event->getPlayer()->getName()."\n";
	}
	public function onDeath(PlayerDeathEvent $event){
		$this->chatlog = $this->chatlog."[".date("Y-m/d H:i:s")."]  [Player_Death]".$event->getPlayer()->getName()."\n";
	}

}
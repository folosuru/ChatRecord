<?php

declare(strict_types=1);

namespace folosuru\ChatRecord;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class DataSaveTask extends Task{

	/** @var Server */
	private $server;

	public function __construct(Server $server){
		$this->server = $server;
		$this->ChatRecord  = $this->server->getPluginManager()->getPlugin("ChatRecord");
	}

	public function onRun(int $currentTick) : void{
		$path = "plugin_data/ChatRecord/" . date("Y/m/d/");
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		$file = $path."chatlog-" . date("H");
		file_put_contents($file,$this->ChatRecord->chatlog );
		$this->server->getLogger()->info("Auto Save completed.");
	}
}

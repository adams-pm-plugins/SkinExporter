<?php

declare(strict_types=1);

namespace ARTulloss\SkinExporter;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
	public function onEnable() : void{
	    $this->getServer()->getCommandMap()->register('skinexport', new SkinExportCommand('skinexport', $this));
	}
}

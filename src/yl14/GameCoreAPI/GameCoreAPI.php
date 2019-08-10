<?php

namespace yl14\GameCoreAPI;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{
    TextFormat as TF, Config
};

class GameCoreAPI extends PluginBase {

    const VERSION = "2.0.0";

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(self), $this);
    }
}
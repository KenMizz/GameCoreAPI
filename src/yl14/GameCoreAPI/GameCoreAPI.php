<?php

namespace yl14\GameCoreAPI;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{
    TextFormat as TF, Config
};

class GameCoreAPI extends PluginBase {

    public function onEnable() {
        $this->initPlugin();
    }
}
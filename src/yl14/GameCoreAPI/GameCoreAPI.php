<?php

namespace yl14\GameCoreAPI;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{
    TextFormat as TF, Config
};

class GameCoreAPI extends PluginBase {

    public function onEnable() {
        $this->getLogger()->notice(TF::YELLOW . "小游戏框架正在初始化中...");
        $this->initPlugin();
    }

    private function initPlugin() {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        if(!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
    }
}
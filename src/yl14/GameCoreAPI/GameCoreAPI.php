<?php

namespace yl14\GameCoreAPI;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{
    TextFormat as TF, Config
};

class GameCoreAPI extends PluginBase {

    /** @var GameCoreAPI*/
    private static $instance;

    /** @var API*/
    private $api;

    /** @var Array*/
    private $config;

    public function onEnable() {
        $this->getLogger()->notice(TF::YELLOW . "GameCoreAPI已启用！正在初始化...");
        $this->initPlugin();
    }

    public function onLoad() {
        self::$instance = $this;
    }

    public function onDisable() {
        $this->getLogger()->warning("GameCoreAPI已关闭...");
    }

    private function initPlugin() {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->api = new API($this);
        @mkdir($this->getDataFolder());
        if(!is_file($this->getDataFolder() . '/config.yml')) {
            $this->saveDefaultConfig();
        }
        $this->config = (new Config($this->getDataFolder() . '/config.yml', Config::YAML))->getAll();
        $this->getLogger()->notice(TF::GREEN . '初始化成功！当前版本: ' . TF::WHITE . $this->getDescription()->getVersion());
    }
}
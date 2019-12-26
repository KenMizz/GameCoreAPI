<?php

namespace yl14\GameCoreAPI;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\{
    TextFormat as TF, Config
};
use yl14\GameCoreAPI\api\API;
use yl14\GameCoreAPI\utils\CustomPlayer;

class GameCoreAPI extends PluginBase {

    /** @var GameCoreAPI*/
    private static $instance;

    /** @var API*/
    private $api;

    /** @var Array*/
    private $config;

    /** @var Array*/
    private $activePlayers = []; //CustomPlayer

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

    public static function getInstance() : GameCoreAPI {
        return self::$instance;
    }

    public function getAPI() : API {
        return $this->api;
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

    public function addActivePlayer(EventListener $eventListener, CustomPlayer $customPlayer) {
        if(!isset($this->activePlayers[$customPlayer->getPlayer()->getName()])) {
            $this->activePlayers[$customPlayer->getPlayer()->getName()] = $customPlayer;
        }
        return false;
    }

    public function removeActivePlayer(EventListener $eventListener, Player $player) {
        if(isset($this->activePlayers[$player->getName()])) {
            unset($this->activePlayers[$player->getName()]);
        }
        return false;
    }

    public function getActivePlayer(EventListener $eventListener, Player $player) : CustomPlayer{
        return $this->activePlayers[$player->getName()];
    }
}
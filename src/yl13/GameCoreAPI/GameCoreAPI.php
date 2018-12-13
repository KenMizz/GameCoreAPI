<?php

namespace yl13\GameCoreAPI;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{
    Config, TextFormat as TF
};

use yl13\GameCoreAPI\api\API;

class GameCoreAPI extends PluginBase {

    const VERSION = '1.0.0';

    private static $instance;

    private $registeredGames = [];

    public $api;

    private $Configure = array(
        'chatchannel' => array(
            'enabled' => true,
            'default-chatchannel' => 'lobby'
        )
    );

    public function onEnable() {
        $this->getLogger()->notice(TF::YELLOW."GameCoreAPI启动中...");
        $this->initPlugin();
    }

    public function onLoad() {
        self::$instance = $this;
    }

    public function onDisable() {
        $this->getLogger()->warning("GameCoreAPI已关闭");
    }
    private function initPlugin() {
        $this->getLogger()->notice(TF::YELLOW."正在初始化...");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        if(!is_file($this->getDataFolder().'config.yml')) {
            $this->saveDefaultConfig();
        }
        if(!is_dir($this->getDataFolder().'maps')) {
            @mkdir($this->getDataFolder().'maps');
        }
        $this->initConfigure(new Config($this->getDataFolder().'config.yml', Config::YAML));
        $this->api = new API($this, $this->randnum(6));
        $this->getLogger()->notice(TF::GREEN."初始化成功!");
        $this->getLogger()->notice(TF::YELLOW."当前版本:".TF::WHITE.self::VERSION);
    }

    private function initConfigure(Config $config) {
        $chatchannel = $config->get('chatchannel');
        if(!is_bool($chatchannel['enabled'])) {
            $chatchannel['enabled'] = true;   
        }
        if(!is_string($chatchannel['default-chatchannel'])) {
            $chatchannel['default-chatchannel'] = 'lobby';
        }
        $this->Configure['chatchannel'] = $chatchannel;
    }

    public static function getInstance() {
        return self::$instance;
    }

    final public function randnum(int $digit) : int {
        $num = null;
        for($i = 0;$i < $digit;$i++) {
            $num .= mt_rand(0, 9);
        }
        return $num;
    }

    final public function get(GameCoreAPI $plugin, String $type) {
        switch($type) {

            case 'RGAME':
                return $this->registeredGames;
        }
    }

    public final function set(GameCoreAPI $plugin, String $type, $override) {
        switch($type) {

            case 'RGAME':
                $this->registeredGames = $override;
        }
    }
}
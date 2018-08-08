<?php

/**
 *    ____                       ____                    _    ____ ___ 
 * / ___| __ _ _ __ ___   ___ / ___|___  _ __ ___     / \  |  _ \_ _|
 *| |  _ / _` | '_ ` _ \ / _ \ |   / _ \| '__/ _ \   / _ \ | |_) | | 
 *| |_| | (_| | | | | | |  __/ |__| (_) | | |  __/  / ___ \|  __/| | 
 * \____|\__,_|_| |_| |_|\___|\____\___/|_|  \___| /_/   \_\_|  |___|
 * 
 * GameCoreAPI是一个PocketMine的小游戏框架
 * 游乐13制作
 */

namespace yl13\GameCoreAPI;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{TextFormat as TF, Config};

use yl13\GameCoreAPI\api\API;


class GameCoreAPI extends PluginBase {

    const GAMECORE_VERSION = "1.0.0";
    const API_VERSION = "v1";

    private $ids = [];
    private $settings = [
        "chatchannel-enabled" => true,
        "default-channel" => "lobby"
    ];
    
    private $ChatChannel = [];
<<<<<<< HEAD
    private $registeredGame = [];
    public $api;
=======
>>>>>>> 6f2fd704ee163af3414b6dd79d9b6a4b0d3df188
    
    private static $instance;

    public function onEnable() {
        $this->initPlugin();
<<<<<<< HEAD
    }

    private function initPlugin() {
        $this->getLogger()->notice(TF::GREEN."初始化小游戏框架中...");
        for($i = 0;$i == 1; $i++) {
            $this->ids[$i] = utils::generateId(4);
        }
        $this->api = new API($this, $ids[0], $ids[1]);
        if(!is_folder($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
        if(!is_file($this->getDataFolder()."config.yml")) {
            $this->saveDefaultConfig();
            $config = $this->getConfigs("config");
            $this->initConfig($config);
        }
    }

    private function initConfig(Config $config) {
        $chatchannelenabled = $config->get("chatchannel-enabled");
        $defaultchatchannel = $config->get("default-chatchannel");
        if(!is_bool($chatchannelenabled)) {
            $chatchannelenabled = true;
        }
        if(!is_string($defaultchatchannel)) {
            $defaultchatchannel = "lobby";
        }
    }

    public function get(int $id, String $type) {
        if(utils::deep_in_array($id, $ids)) {
            switch($type) {

                case 'GAMECORE_VERSION':
                    return self::GAMECORE_VERSION;
                break;

                case 'API_VERSION':
                    return self::API_VERSION;
                break;

                case 'REGISTERED_GAME':
                    return $this->registeredGame;
            }
        }
    }

    private function getConfigs(String $name, $type = Config::YAML) {
        return new Config($this->getDataFolder()."{$name}.yml", $type);
    } 
=======
        $this->getLogger()->notice(TF::GOLD."小游戏框架已启动!");
    }

    public function onLoad() {
        self::$instance = $this;
    }

    private function initPlugin() {
        //TODO
    }

    public static function getInstance() {
        return self::$instance;
    }
>>>>>>> 6f2fd704ee163af3414b6dd79d9b6a4b0d3df188
}
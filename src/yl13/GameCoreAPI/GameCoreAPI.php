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
    private $registeredGame = [];
    public $api;
    
    private static $instance;

    public function onEnable() {
        $this->initPlugin();
    }

    public function onLoad() {
        self::$instance = $this;
    }

    public static function getInstance() {
        /**
         * 获取GameCore的Instance来使用GameCoreAPI
         */
        return self::$instance;
    }

    private function initPlugin() {
        $this->getLogger()->notice(TF::GREEN."初始化GameCoreAPI中...");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->ids[0] = utils::generateId(4);
        $this->ids[1] = utils::generateId(4);
        $this->api = new API($this, $this->ids[0], $this->ids[1]);
        if(!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
        if(!is_file($this->getDataFolder()."config.yml")) {
            $this->saveDefaultConfig();
        }
        $config = $this->getConfigs("config");
        $this->initConfig($config);
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
        $this->settings["chatchannel-enabled"] = $chatchannelenabled;
        $this->settings["defaultchatchannel"] = $defaultchatchannel;
        $this->getLogger()->notice(TF::GREEN."GameCoreAPI初始化成功");
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
                break;
                
                case 'SETTINGS':
                    return $this->settings;
            }
        }
        return false;
    }

    public function override(int $id, String $type, $override) {
        if(utils::deep_in_array($id, $this->ids)) {
            switch($type) {

                case 'REGISTERED_GAME':
                    $this->registeredGame = $override;
                break;
            }
        }
        return false;
    }

    private function getConfigs(String $name, $type = Config::YAML) {
        switch($type) {

            case Config::YAML:
                if(!is_file($this->getDataFolder()."{$name}.yml")) {
                    return new Config($this->getDataFolder()."{$name}.yml", Config::YAML, array());
                }
                return new Config($this->getDataFolder()."{$name}.yml", Config::YAML);
            
        }
    } 
}
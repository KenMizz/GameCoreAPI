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
use pocketmine\Player;

use yl13\GameCoreAPI\api\API;


class GameCoreAPI extends PluginBase {

    const GAMECORE_VERSION = "1.0.0";
    const API_VERSION = "v1";

    private $ids = [];
    private $gid;
    private $settings = [
        "default-chatchannel" => "lobby"
    ];
    
    private $ChatChannel = [];
    private $registeredGame = [];
    private $playerdata = [];
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
        $this->gid = utils::generateId(8);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this, $this->gid), $this);
        $this->ids[0] = utils::generateId(4);
        $this->ids[1] = utils::generateId(4);
        $this->ids[2] = utils::generateId(4);
        $this->api = new API($this, $this->ids[0], array($this->ids[1], $this->gid), $this->ids[2]);
        if(!is_dir($this->getDataFolder())) {
            @mkdir($this->getDataFolder());
        }
        if(!is_dir($this->getDataFolder()."maps")) {
            @mkdir($this->getDataFolder()."maps");
        }
        if(!is_file($this->getDataFolder()."config.yml")) {
            $this->saveDefaultConfig();
        }
        $config = $this->getConfigs("config");
        $this->initConfig($config);
    }

    private function initConfig(Config $config) {
        $defaultchatchannel = $config->get("default-chatchannel");
        $this->settings["default-chatchannel"] = $defaultchatchannel;
        if(is_string($this->settings['default-chatchannel'])) {
            $this->getLogger()->notice(TF::WHITE."正在创建默认聊天频道");  
            $this->api->getChatChannelAPI()->createDefaultChatChannel($this->gid, $this->settings['default-chatchannel']);
        }
        $this->getLogger()->notice(TF::GREEN."GameCoreAPI初始化成功");
    }

    public final function get(int $id, String $type) {
        if(utils::deep_in_array($id, $this->ids) or $id == $this->gid) {
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

                case 'CHATCHANNEL':
                    return $this->ChatChannel;
                break;
                
                case 'SETTINGS':
                    return $this->settings;
            }
        }
        return false;
    }

    public final function override(int $id, String $type, $override) {
        if(utils::deep_in_array($id, $this->ids)) {
            switch($type) {

                case 'REGISTERED_GAME':
                    $this->registeredGame = $override;
                break;

                case 'CHATCHANNEL':
                    $this->ChatChannel = $override;
            }
        }
        return false;
    }

    public final function getGameNameById(int $id, int $gameid) : String {
        if(utils::deep_in_array($id, $this->ids)) {
            if(utils::deep_in_array($gameid, $this->registeredGame)) {
                return $this->registeredGame[$gameid]['name'];
            }
            return '未知';
        }
        return '未知';
    }

    private final function getConfigs(String $name, $type = Config::YAML) : Config {
        switch($type) {

            case Config::YAML:
                if(!is_file($this->getDataFolder()."{$name}.yml")) {
                    return new Config($this->getDataFolder()."{$name}.yml", Config::YAML, array());
                }
                return new Config($this->getDataFolder()."{$name}.yml", Config::YAML);
            
        }
    }

    /**
     * playerData
     */
    public final function initPlayerData(int $gid, Player $player) : bool {
        //initPlayerData
        if($this->gid == $gid) {
            if(!isset($this->playerdata[$player->getName()])) {
                $this->playerdata[$player->getName()] = array(
                    'chatchannel' => null
                );
                return true;
            }
            return false;
        }
        return false;
    }

    public final function setPlayerData(int $gid, Player $player, String $type, $override) : bool {
        //setPlayerData
        if($this->gid == $gid) {
            if(isset($this->playerdata[$player->getName()])) {
                switch($type) {

                    default:
                        return false;
                    break;

                    case 'CHATCHANNEL':
                        $this->playerdata[$player->getName()] = array(
                            'chatchannel' => $override
                        );
                        return true;
                }
            }
            return false;
        }
        return false;
    }

    public final function getPlayerData(int $gid, Player $player, String $type) : ?bool {
        //getPlayerData
        if($this->gid == $gid) {
            if(isset($this->playerdata[$player->getName()])) {
                switch($type) {

                    case 'CHATCHANNEL':
                        return $this->playerdata[$player->getName()]['chatchannel'];
                }
            }
            return false;
        }
        return false;
    }

    public final function getPlayerAllData(int $gid, Player $player) : ?Array {
        //getPlayerAllData
        if($this->gid == $gid) {
            if(isset($this->playerdata[$player->getName()])) {
                return $this->playerdata[$player->getName()];
            }
            return false;
        }
        return false;
    }

    public final function removePlayerData(int $gid, Player $player) : bool {
        //removePlayerData
        if($this->gid == $gid) {
            if(isset($this->playerdata[$player->getName()])) {
                unset($this->playerdata[$player->getName()]);
                return true;
            }
            return false;
        }
        return false;
    }

}
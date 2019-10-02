<?php

namespace yl14\GameCoreAPI\utils;
/**
 * Session是一个游戏房间的基类
 * 你在做游戏房间时可以继承这个类，并且通过GameCoreAPI管理
 * 在GameCoreAPI文件夹的config.yml内，Sessiong功能必须为开启，不然无法通过GameCoreAPI对其进行管理
 */

class Session {

    /** @var \pocketmine\plugin\Plugin*/
    private $plugin;

    /** @var \pocketmine\Player[] */
    private $players = [];
    private $spectators = []; //用于观察者的状态

    /**
     * @method void __construct(Plugin $plugin)
     * 
     * @param \pocketmine\plugin\Plugin $plugin 插件类
     */
    public function __construct(Plugin $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * 添加一名玩家到Session
     * Note: 如果玩家本身是观察者的话，将会把玩家从观察者内移除
     * 
     * @method bool addPlayer(\pocketmine\Player $player)
     * 
     * @param \pocketmine\Player $player 需要加入的玩家
     * 
     * @return Boolean
     */
    public function addPlayer(\pocketmine\Player $player) : bool {
        if(!isset($this->players[$player->getName()])) {
            $this->players[$player->getName()] = $player;
            if($this->getSpectator($player) != null) {
                $this->removeSpectator($player);
            }
            return true;
        }
        return false;
    }

    /**
     * 从Session内移除一名玩家
     * 
     * @method bool removePlayer(\pocketmine\Player $player)
     * 
     * @param \pocketmine\Player $player 需要移除的玩家
     * 
     * @return Boolean  
     */
    public function removePlayer(\pocketmine\Player $player) : bool {
        if(isset($this->players[$player->getName()])) {
            unset($this->players[$player->getName()]);
            return true;
        }
        return false;
    }

    /**
     * 添加一名观察者玩家到Session
     * Note: 如果玩家本身只是普通玩家，会将玩家从普通玩家内移除
     * 
     * @method bool addSpectator(\pocketmine\Player $player)
     * 
     * @param \pocketmine\Player $player 需要加入的玩家
     * 
     * @return Boolean
     */
    public function addSpectator(\pocketmine\Player $player) : bool {
        if(!isset($this->spectators[$player->getName()])) {
            $this->spectators[$player->getName()] = $player;
            if($this->getPlayer($player) != null) {
                $this->removePlayer($player);
            }
            return true;
        }
        return false;
    }

    /**
     * 从Session内移除一名观察者玩家
     * 
     * @method bool removeSpectator(\pocketmine\Player $player)
     * 
     * @param \pocketmine\Player $player
     * 
     * @return Boolean
     */
    public function removeSpectator(\pocketmine\Player $player) : bool {
        if(isset($this->spectators[$player->getName()])) {
            unset($this->spectators[$player->getName()]);
            return true;
        }
        return false;
    }

    /**
     * 获取Session内所有玩家
     * 
     * @method Array getPlayers()
     * 
     * @return Player[]|Array
     */
    public function getPlayers() : Array {
        return $this->players;
    }

    /**
     * 获取Session内所有观察者玩家
     * 
     * @method Array getSpectators()
     * 
     * @return Player[]|Array
     */
    public function getSpectators() : Array {
        return $this->spectators;
    }

    /**
     * 获取Session内的某名玩家
     * 
     * @method ?\pocketmine\Player getPlayer(\pocketmine\Player)
     * 
     * @param \pocketmine\Player $player 要获取的玩家
     * 
     * @return Player|null
     */
    public function getPlayer(\pocketmine\Player $player) : ?\pocketmine\Player {
        return $this->players[$player->getName()] ?? null;
    }

    /**
     * 获取Sessionn内的某名观察者
     * 
     * @method ?\pocketmine\Player getSpectator(\pocketmine\Player)
     * 
     * @param \pocketmine\Player $player 要获取的玩家
     * 
     * @return Player|null
     */
    public function getSpectator(\pocketmine\Player $player) : ?\pocketmine\Player {
        return $this->spectators[$player->getName()] ?? null;
    }
}
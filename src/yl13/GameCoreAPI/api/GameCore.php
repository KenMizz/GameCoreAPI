<?php

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;

use yl13\GameCoreAPI\GameCoreAPI;

class gamecore {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    
    final public function registerGame(String $name, String $author = null) : int {
        /**
         * 注册小游戏以获取小游戏id
         * require: String 小游戏名
         * optional: String 作者名
         * return: int
         */
        $registeredGames = $this->plugin->get($this->plugin, 'RGAME');
        $id = $this->plugin->randnum(8);
        while(isset($registeredGames[$id])) {
            $id = $this->plugin->randnum(8); 
        }
        $registeredGames[$id] = array(
            'name' => $name,
            'author' => $author 
        );
        $this->plugin->set($this->plugin, 'RGAME', $registeredGames);
        if($author != null) {
            $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$name.TF::GREEN." 注册成功!作者:".TF::WHITE.$author);
        } else {
            $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$name.TF::GREEN." 注册成功!");
        }
        return $id;
    }

    final public function getVersion() : String {
        /**
         * 获取GameCoreAPI的版本
         * return: String
         */
        return GameCoreAPI::VERSION;
    }

    final public function isGameRegistered(int $id) : bool {
        /**
         * 检查游戏是否被注册
         * return: bool
         */
        $registeredGames = $this->plugin->get($this->plugin, 'RGAME');
        if(!isset($registeredGames[$id])) {
            return false;
        }
        return true;
    }
}
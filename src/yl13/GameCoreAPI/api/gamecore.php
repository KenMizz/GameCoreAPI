<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;


class gamecore extends API {
    
    final public function registerGame(String $name, String $author = null) : int {
        /**
         * 注册小游戏以获取小游戏id
         * require: String 小游戏名
         * optional: String 作者名
         * return: int
         */
        $registeredGames = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        $id = parent::getPlugin()->randnum(8);
        while(isset($registeredGames[$id])) {
            $id = parent::getPlugin()->randnum(8); 
        }
        $registeredGames[$id] = array(
            'name' => $name,
            'author' => $author 
        );
        parent::getPlugin()->set(parent::getPlugin(), 'RGAME', $registeredGames);
        if($author != null) {
            parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$name.TF::GREEN." 注册成功!作者:".TF::WHITE.$author);
        } else {
            parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$name.TF::GREEN." 注册成功!");
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
        $registeredGames = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(!isset($registeredGames[$id])) {
            return false;
        }
        return true;
    }
}
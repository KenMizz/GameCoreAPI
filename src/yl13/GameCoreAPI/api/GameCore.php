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

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;

use yl13\GameCoreAPI\GameCoreAPI;
use yl13\GameCoreAPI\utils;

class GameCore {

    private $plugin;
    private $id;

    private $failedreason = [
        "gamename.already.existed" => "小游戏名已经被注册"
    ];

    public function __construct(GameCoreAPI $plugin, int $id) {
        $this->plugin = $plugin;
        $this->id = $id;
    }

    //getVersion
    public final function getVersion() : String {
        /**
         * 获取GameCoreAPI的版本
         * 返回值: String
         */
        return $this->plugin->get($this->id, "GAMECORE_VERSION");
    }

    //getApiVersion
    public final function getApiVersion() : String {
        /**
         * 获取GameCoreAPI的api版本
         * 返回值: String
         */
        return $this->plugin->get($this->id, "API_VERSION");
    }

    //registerGame
    public final function registerGame(String $gamename, String $authorname = "unknown") : ?int {
        /**
         * 注册小游戏
         * 以获取小游戏id来使用GameCoreAPI
         */
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        if(!utils::deep_in_array($gamename, $registeredGame)) {
            $id = utils::generateId(8);
            while(utils::deep_in_array($id, $registeredGame)) {
                $id = utils::generateId(8);
            }
            $registeredGame[$id] = array(
                "name" => $gamename,
                "id" => $id,
                "author" => $authorname
            );
            $this->plugin->override($this->id, "REGISTERED_GAME", $registeredGame);
            if($authorname == "unknown") {
                $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$gamename.TF::GREEN." 注册成功");
            } else {
                $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$gamename.TF::GREEN." 注册成功,作者:".TF::WHITE.$authorname);
            }
            return $id;
        }
        $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$gamename.TF::RED." 注册失败,原因:".TF::WHITE.$this->failedreason['gamename.already.existed']);
        return false;
    }
}
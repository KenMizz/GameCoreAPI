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

    /**
     * 
     * API
     * 
     * 
     */

    /**
     * getGameCoreVersion
     * 获取GameCore的版本
     * 返回值: String
     */
        public function getGameCoreVersion() : String {
            return $this->plugin->get($this->id, "GAMECORE_VERSION");
        }
    
    /**
     * getGameCoreAPIVersion
     * 获取GameCore的API版本
     * 返回值: String
     */
        public function getGameCoreAPIVersion() : String {
            return $this->plugin->get($this->id, "API_VERSION");
        }
    
    /**
     * registerGame
     * 注册小游戏来获取小游戏id,从而来使用小游戏框架的API
     * 需要: 小游戏名(String)
     * 可用: 作者名(String)
     * 返回值: int
     */
        public function registerGame(String $gamename, String $authorname = "unknown") : ?int {
            $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
            if(!utils::deep_in_array($gamename, $registeredGame)) {
                $id = utils::generateId(6);
                while(utils::deep_in_array($id, $registeredGame)) {
                    $id = utils::generateId(6);
                }
                $registeredGame[$id] = array(
                    "name" => $gamename,
                    "id" => $id,
                    "author" => $authorname
                );
                $this->plugin->override($this->id, "REGISTERED_GAME", $registeredGame);
                if($authorname == "unknown") {
                    $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::AQUA." 注册成功");
                }
                else {
                    $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::AQUA." 注册成功,作者:".TF::WHITE.$authorname);
                }
                return $id;
            }
            else {
                $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::AQUA." 注册失败,原因:".TF::WHITE.$failedreason['gamename.already.existed']);
                return false;
            }
        }
}
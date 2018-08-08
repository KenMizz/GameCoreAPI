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

class API {

    private $plugin;

    public $gamecore;
    public $chatchannel;

    public function __construct(GameCoreAPI $plugin, int $gamecoreid, int $chatchannelid) {
        $this->plugin = $plugin;
        $this->gamecore = new GameCore($plugin, $gamecoreid);
        $this->chatchannel = new ChatChannel($plugin, $chatchannelid);
        $this->plugin->getLogger()->notice(TF::GREEN."API初始化完成");
    }
}
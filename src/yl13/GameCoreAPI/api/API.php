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

class API {

    private $plugin;

    private $gamecore;
    private $chatchannel;
    private $maploader;

    public function __construct(GameCoreAPI $plugin, int $gamecoreid, array $chatchannel, int $maploaderid) {
        $this->plugin = $plugin;
        $this->gamecore = new GameCore($plugin, $gamecoreid);
        $this->chatchannel = new ChatChannel($plugin, $chatchannel[0], $chatchannel[1]);
        $this->maploader = new MapLoader($plugin, $maploaderid);
    }

    public final function getGameCoreAPI() {
        return $this->gamecore;
    }

    public final function getChatChannelAPI() {
        return $this->chatchannel;
    }

    public final function getMapLoaderAPI() {
        return $this->maploader;
    }
}
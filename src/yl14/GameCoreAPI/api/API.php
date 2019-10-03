<?php

namespace yl14\GameCoreAPI\api;

use yl14\GameCoreAPI\{
    GameCoreAPI, api\GameCore
};

class API {

    private $plugin;

    private $gamecore;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
        $this->gamecore = new GameCore($this->plugin);
    }

    public function getGameCore() : Gamecore {
        return $this->gamecore;
    }
}
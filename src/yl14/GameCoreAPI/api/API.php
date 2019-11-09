<?php

namespace yl14\GameCoreAPI\api;

use yl14\GameCoreAPI\{
    GameCoreAPI, api\GameCore
};

class API {

    private $plugin;

    private $gamecore;
    private $chatchannel;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
        $this->gamecore = new GameCore($this->plugin);
        $this->chatchannel = new ChatChannel($this->plugin);
    }

    public function getGameCore() : Gamecore {
        return $this->gamecore;
    }

    public function getChatChannel() : ChatChannel {
        return $this->chatchannel;
    }
}
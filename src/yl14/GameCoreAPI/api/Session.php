<?php

namespace yl14\GameCoreAPI\api;

use yl14\GameCoreAPI\GameCoreAPI;
use yl14\GameCoreAPI\utils\Session as GameSession;

class Session {

    private $plugin;

    private $Sessions = [];

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * 添加Session
     * 
     * @method bool addSession(int $gameid, GameSession $session)
     * 
     * @param int $gameid
     * @param yl14\GameCoreAPI\utils\Session $session
     * 
     * @return Boolean
     */
    public function addSession(int $gameid, GameSession $session) {
        if(!isset($this->Sessions[$gameid])) {
            $this->Sessions[$gameid] = array();
            //TODO
        }
    }
}
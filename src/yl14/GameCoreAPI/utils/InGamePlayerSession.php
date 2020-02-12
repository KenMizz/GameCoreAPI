<?php

namespace yl14\GameCoreAPI\utils;

use pocketmine\Player;

use yl14\GameCoreAPI\GameCoreAPI;

class InGamePlayerSession {

    private static $players = [];

    static public function addPlayer(CustomPlayer $player) {
        if(!isset($this->players[$player->getPlayer()->getName()])) {
            self::$players[$player->getPlayer()->getName()] = $player;
        }
    }

    static public function removePlayer(CustomPlayer $player) {
        if(isset(self::$players[$player->getPlayer()->getName()])) {
            unset(self::$players[$player->getPlayer()->getName()]);
        }
    }

    static public function &getPlayer(Player $player) {
        return self::$players[$player->getName()] ?? false;
    }
}
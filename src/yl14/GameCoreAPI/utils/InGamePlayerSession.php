<?php

namespace yl14\GameCoreAPI\utils;

use pocketmine\Player;

/**
 * It handles in game player
 */

class InGamePlayerSession {

    private static $players = [];

    static public function addPlayer(CustomPlayer $customPlayer) {
        if(!isset(self::$players[$customPlayer->getPlayer()->getName()])) {
            self::$players[$customPlayer->getPlayer()->getName()] = $customPlayer;
        }
    }

    static public function removePlayer(CustomPlayer $customPlayer) {
        if(isset(self::$players[$customPlayer->getPlayer()->getName()])) {
            unset(self::$players[$customPlayer->getPlayer()->getName()]);
        }
    }

    static public function getPlayer(Player $player) {
        return self::$players[$player->getName()];   
    }

    static public function updatePlayer(CustomPlayer $customPlayer) {
        if(isset(self::$players[$customPlayer->getPlayer()->getName()])) {
            self::$players[$customPlayer->getPlayer()->getName()] = $customPlayer;
        }
    }
}
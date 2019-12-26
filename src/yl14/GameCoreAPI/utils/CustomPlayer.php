<?php

namespace yl14\GameCoreAPI\utils;

use pocketmine\Player;
use yl14\GameCoreAPI\api\GameCore;
use yl14\GameCoreAPI\GameCoreAPI;

class CustomPlayer {
    
    private $player;

    private $chatchannel;
    private $money;
    
    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function getPlayer() : Player {
        return $this->player;
    }

    public function getChatChannel() : ChatChannel {
        return $this->chatchannel;
    }
}
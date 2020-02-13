<?php

namespace yl14\GameCoreAPI\utils;

use pocketmine\Player;

use yl14\GameCoreAPI\GameCoreAPI;

class CustomPlayer {

    private $plugin;

    private $chatChannel = null;

    private $player = null;

    public function __construct(GameCoreAPI $plugin, Player $player) {
        $this->plugin = $plugin;
        $this->player = $player;
    }

    public function getPlayer() : Player {
        return $this->player;
    }
    
    public function setChatChannel(&$channel) {
        $this->chatChannel = $channel;
    }

    public function &getChatChannel() : ?\yl14\GameCoreAPI\utils\ChatChannel {
        return $this->chatChannel;
    }
}
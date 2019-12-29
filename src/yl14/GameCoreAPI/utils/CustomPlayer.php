<?php

namespace yl14\GameCoreAPI\utils;

use pocketmine\Player;
use yl14\GameCoreAPI\event\CustomPlayerUpdateEvent;
use yl14\GameCoreAPI\GameCoreAPI;

class CustomPlayer {
    
    private $player;

    private $chatchannel;
    private $money;

    private $plugin;
    
    public function __construct(GameCoreAPI $plugin, Player $player) {
        $this->player = $player;
        $this->plugin = $plugin;
    }

    public function getPlayer() : Player {
        return $this->player;
    }

    public function getChatChannel() : ChatChannel {
        return $this->chatchannel;
    }
    
    public function setChatChannel(ChatChannel &$chatChannel) {
        $this->chatchannel = $chatChannel;
        $this->plugin->getServer()->getPluginManager()->callEvent(new CustomPlayerUpdateEvent($this));
    }
}
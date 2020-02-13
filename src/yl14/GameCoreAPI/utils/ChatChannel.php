<?php

namespace yl14\GameCoreAPI\utils;

use yl14\GameCoreAPI\GameCoreAPI;

class ChatChannel {

    private $plugin;

    private $channelName = null;
    private $players = []; /** @var \yl14\GameCoreAPI\utils\CustomPlayer */

    private $chatformat = "";
    private $isMute = false;

    public function __construct(GameCoreAPI $plugin, string $channelName, string $chatformat = "") {
        $this->plugin = $plugin;
        $this->channelName = $channelName;
        $this->chatformat = $chatformat;
    }

    public function addPlayer(CustomPlayer $player) {
        if(!isset($this->players[$player->getPlayer()->getName()])) {
            $player->setChatChannel($this);
            $this->players[$player->getPlayer()->getName()] = $player;
        }
    }

    public function removePlayer(CustomPlayer $player) {
        if(isset($this->players[$player->getPlayer()->getName()])) {
            $player->setChatChannel(null);
            unset($this->players[$player->getPlayer()->getName()]);
        }
    }

    public function removeAll() {
        foreach($this->players as $player) {
            $this->removePlayer($player);
        }
    }

    public function sendMessage(CustomPlayer $player, String $message) {
        if(!$this->isMute) {
            if($this->chatformat == "") {
                $this->broadcastMessage('[' . $player->getPlayer()->getName() . ']' . $message);
            } else {
                $this->broadcastMessage(str_replace(['PLAYERNAME', 'MESSAGE'], [$player->getPlayer()->getName()], $message));
            }
        }
    }

    public function broadcastMessage(String $message) {
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $player->sendMessage($message);
        }
    }

    public function setMute(bool $mute = false) {
        $this->mute = $mute;
    }
}
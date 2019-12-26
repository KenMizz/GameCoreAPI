<?php

namespace yl14\GameCoreAPI\utils;

class ChatChannel {

    private $channelname;


    private $players = [];
    private $mute = false;
    private $chatFormat = "";

    public function __construct(string $channelname, string $chatFormat = "") {
        $this->channelname = $channelname;
        $this->chatFormat = $chatFormat;
    }

    public function addPlayer(CustomPlayer $player) {
        if(!isset($this->players[$player->getPlayer()->getName()])) {
            $this->players[$player->getPlayer()->getName()] = $player;
        }
        return false;
    }

    public function removePlayer(CustomPlayer $player) {
        if(isset($this->players[$player->getPlayer()->getName()])) {
            unset($this->players[$player->getPlayer()->getName()]);
        }
        return false;
    }

    public function setMute($mute = false) {
        $this->mute = $mute;
    }

    public function sendMessage(CustomPlayer $player, string $message) {
        if(isset($this->players[$player->getPlayer()->getName()])) {
            if(!$this->mute) {
                $message = str_replace(['playername', 'message'], [$player->getPlayer()->getName(), $message], $message);
                $this->broadcastMessage($message);
            }
        }
    }

    public function broadcastMessage(string $message) {
        foreach($this->players as $key => $value) {
            $value->getPlayer()->sendMessage($message);
        }
    }
}
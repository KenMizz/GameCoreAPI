<?php

namespace yl14\GameCoreAPI;

use pocketmine\event\{
    Listener, player\PlayerJoinEvent, player\PlayerChatEvent, player\PlayerQuitEvent
};

use yl14\GameCoreAPI\utils\CustomPlayer;
use yl14\GameCoreAPI\utils\InGamePlayerSession;

class EventListener implements Listener {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    public function onPlayerJoin(PlayerJoinEvent $ev) {
        InGamePlayerSession::addPlayer(new CustomPlayer($this->plugin, $ev->getPlayer()));
        InGamePlayerSession::getPlayer($ev->getPlayer())->setChatChannel($this->plugin->getAPI()->getChatChannel()->getDefaultChatChannel());
    }

    public function onPlayerQuit(PlayerQuitEvent $ev) {
        InGamePlayerSession::removePlayer(new CustomPlayer($this->plugin, $ev->getPlayer()));
    }

    public function onPlayerChat(PlayerChatEvent $ev) {
        $ev->setCancelled();
        InGamePlayerSession::getPlayer($ev->getPlayer())->getChatChannel()->sendMessage(InGamePlayerSession::getPlayer($ev->getPlayer()), $ev->getMessage());
    }
}
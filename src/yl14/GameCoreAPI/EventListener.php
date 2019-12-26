<?php

namespace yl14\GameCoreAPI;

use pocketmine\event\{
    Listener, player\PlayerJoinEvent, player\PlayerChatEvent, player\PlayerQuitEvent
};

use yl14\GameCoreAPI\utils\CustomPlayer;

class EventListener implements Listener {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    public function onPlayerJoin(PlayerJoinEvent $ev) {
        $this->plugin->addActivePlayer($this, new CustomPlayer($ev->getPlayer()));
    }

    public function onPlayerQuit(PlayerQuitEvent $ev) {
        $this->plugin->removeActivePlayer($this, $ev->getPlayer());
    }

    public function onPlayerChat(PlayerChatEvent $ev) {
        $ev->setCancelled();
        $this->plugin->getActivePlayer($this, $ev->getPlayer())->getChatChannel()->sendMessage($this->plugin->getActivePlayer($this, $ev->getPlayer()), $ev->getMessage());
    }
}
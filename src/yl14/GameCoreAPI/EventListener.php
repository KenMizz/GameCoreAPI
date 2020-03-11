<?php

namespace yl14\GameCoreAPI;

use pocketmine\event\{
    Listener, player\PlayerJoinEvent, player\PlayerChatEvent, player\PlayerQuitEvent
};
use pocketmine\utils\TextFormat as TF;

class EventListener implements Listener {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    public function onPlayerJoin(PlayerJoinEvent $ev) {
        
    }

    public function onPlayerQuit(PlayerQuitEvent $ev) {

    }

    public function onPlayerChat(PlayerChatEvent $ev) {
        
    }
}
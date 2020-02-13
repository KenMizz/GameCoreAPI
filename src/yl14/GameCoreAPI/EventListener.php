<?php

namespace yl14\GameCoreAPI;

use pocketmine\event\{
    Listener, player\PlayerJoinEvent, player\PlayerChatEvent, player\PlayerQuitEvent
};
use pocketmine\utils\TextFormat as TF;

use yl14\GameCoreAPI\utils\CustomPlayer;
use yl14\GameCoreAPI\utils\InGamePlayerSession;

class EventListener implements Listener {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    public function onPlayerJoin(PlayerJoinEvent $ev) {
        InGamePlayerSession::addPlayer(new CustomPlayer($this->plugin, $ev->getPlayer()));
    }

    public function onPlayerQuit(PlayerQuitEvent $ev) {
        InGamePlayerSession::removePlayer(InGamePlayerSession::getPlayer($ev->getPlayer()));
    }

    public function onPlayerChat(PlayerChatEvent $ev) {
        $ev->setCancelled();
        $player = InGamePlayerSession::getPlayer($ev->getPlayer());
        if(!is_null($player->getChatChannel())) {
            $player->getChatChannel()->sendMessage($player, $ev->getMessage());
        } else {
            $ev->getPlayer()->sendMessage(TF::RED . '你当前没有加入任何的聊天频道');
        }
    }
}
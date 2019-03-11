<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI;

use pocketmine\event\Listener;

use pocketmine\event\player\{
    PlayerJoinEvent, PlayerQuitEvent, PlayerChatEvent
};

class EventListener implements Listener {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    public function onPlayerJoin(PlayerJoinEvent $ev) {
        $player = $ev->getPlayer();
        $this->plugin->initPlayerData($this->plugin, $player);
        if(is_string($this->plugin->getConfigure('chatchannel', 'default'))) {
            //让玩家加入默认聊天频道如果有的话
            $ChatChannel = $this->plugin->get($this->plugin, 'CHATCHANNEL');
            $ChatChannel[$this->plugin->getConfigure('chatchannel', 'default')]['players'][$player->getName()] = $player;
            $this->plugin->set($this->plugin, 'CHATCHANNEL', $ChatChannel);
            $this->plugin->setPlayerData($this->plugin, $player, 'CHATCHANNEL', $this->plugin->getConfigure('chatchannel', 'default'));
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $ev) {
        $player = $ev->getPlayer();
        $ChatChannel = $this->plugin->get($this->plugin, 'CHATCHANNEL');
        if(isset($ChatChannel[$this->plugin->getConfigure('chatchannel', 'default')]['players'][$player->getName()])) {
            unset($ChatChannel[$this->plugin->getConfigure('chatchannel', 'default')]['players'][$player->getName()]);
        }
        //TODO: 储存金钱数据
        $this->plugin->removePlayerData($this->plugin, $player);
    }

    public function onPlayerChat(PlayerChatEvent $ev) {
        if($this->plugin->getConfigure('chatchannel', 'enabled')) {
            $ev->setCancelled(true);
            $player = $ev->getPlayer();
            $ChatChannel = $this->plugin->get($this->plugin, 'CHATCHANNEL');
            $playerData = $this->plugin->getPlayerData($this->plugin, $player);
            if(isset($ChatChannel[$playerData['chatchannel']])) {
                $players = $ChatChannel[$playerData['chatchannel']]['players'];
                $format = $ChatChannel[$playerData['chatchannel']]['format'];
                if(!$ChatChannel[$playerData['chatchannel']]['mute']) {
                    if($format != null) {
                        $format = str_replace(['PLAYER_NAME', 'MESSAGE'], [$player->getName(), $ev->getMessage()], $format);
                        $this->plugin->getServer()->broadcastMessage($format, $players);
                    }
                    $this->plugin->getServer()->broadcastMessage("[{$player->getName()}] {$ev->getMessage()}", $players);
                }
            }
        }
    }
}
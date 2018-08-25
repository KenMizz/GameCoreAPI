<?php

/**
 *    ____                       ____                    _    ____ ___ 
 * / ___| __ _ _ __ ___   ___ / ___|___  _ __ ___     / \  |  _ \_ _|
 *| |  _ / _` | '_ ` _ \ / _ \ |   / _ \| '__/ _ \   / _ \ | |_) | | 
 *| |_| | (_| | | | | | |  __/ |__| (_) | | |  __/  / ___ \|  __/| | 
 * \____|\__,_|_| |_| |_|\___|\____\___/|_|  \___| /_/   \_\_|  |___|
 * 
 * GameCoreAPI是一个PocketMine的小游戏框架
 * 游乐13制作
 */

namespace yl13\GameCoreAPI;

use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent, PlayerChatEvent};

use yl13\GameCoreAPI\GameCoreAPI;


class EventListener implements Listener {

    private $plugin;
    private $gid;

    public function __construct(GameCoreAPI $plugin, int $gid) {
        $this->plugin = $plugin;
        $this->gid = $gid;
    }

    public function onJoin(PlayerJoinEvent $ev) {
        $Player = $ev->getPlayer();
        $this->plugin->initPlayerData($this, $Player);
        $Settings = $this->plugin->get($this->gid, "SETTINGS");
        if($Settings['default-chatchannel'] != null) {
            $this->plugin->setPlayerData($this->gid, $Player, "CHATCHANNEL", $Settings['default-chatchannel']);
        }
    }

    public function onQuit(PlayerQuitEvent $ev) {
        $Player = $ev->getPlayer();
        $this->plugin->removePlayerData($this, $Player->getName());
    }

    public function onChat(PlayerChatEvent $ev) {
        $Player = $ev->getPlayer();
        $ChatChannelData = $this->plugin->getPlayerData($this->gid, $Player, "CHATCHANNEL");
        if($ChatChannelData == null) {
            $ev->setCancelled(true);
        } else {
            $chatchannel = $this->plugin->get($this->gid, "CHATCHANNEL");
            if(!utils::deep_in_array($ChatChannelData, $chatchannel)) {
                $ev->setCancelled(true);
            } else {
                $players = $chatchannel[$ChatChannelData]['players'];
                $ev->setCancelled(true);
                $this->plugin->getServer()->broadcastMessage($ev->getMessage(), $players);
            }
        }
    }
}
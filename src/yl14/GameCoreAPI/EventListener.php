<?php

namespace yl14\GameCoreAPI;

use pocketmine\event\Listener;

class EventListener implements Listener {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }
}
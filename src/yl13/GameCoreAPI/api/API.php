<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI\api;

use yl13\GameCoreAPI\GameCoreAPI;


class API {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    final public function getGameCoreAPI() {
        return new gamecore($this->plugin);
    }

    final public function getChatChannelAPI() {
        return new chatchannel($this->plugin);
    }

    final public function getMapLoaderAPI() {
        return new maploader($this->plugin);
    }

    final public function getPlugin() {
        return $this->plugin;
    }
}
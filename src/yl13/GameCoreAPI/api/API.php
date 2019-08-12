<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI\api;

use yl13\GameCoreAPI\GameCoreAPI;


class API {

    private $plugin;

    private $gamecore, $chatchannel, $maploader, $economy;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
        $this->gamecore = new gamecore($this->plugin);
        $this->chatchannel = new chatchannel($this->plugin);
        $this->maploader = new maploader($this->plugin);
        $this->economy = new economy($this->plugin);
    }

    final public function getGameCoreAPI() {
        return $this->gamecore;
    }

    final public function getChatChannelAPI() {
        return $this->chatchannel;
    }

    final public function getMapLoaderAPI() {
        return $this->maploader;
    }

    final public function getEconomyAPI() {
        return $this->maploader;
    }
}
<?php

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;

use yl13\GameCoreAPI\GameCoreAPI;

class MapLoader {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }
}
<?php

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;

use yl13\GameCoreAPI\{GameCoreAPI, utils};

class MapLoader {

    private $plugin, $id;

    public function __construct(GameCoreAPI $plugin, int $id) {
        $this->plugin = $plugin;
        $this->id = $id;
    }

    
}
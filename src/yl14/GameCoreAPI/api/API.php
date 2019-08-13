<?php

namespace yl14\GameCoreAPI\api;

class API {

    private $plugin;

    private $gamecore;
    private $chatchannel;
    private $maploader;

    public function __construct(\yl14\GameCoreAPI\GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    
}
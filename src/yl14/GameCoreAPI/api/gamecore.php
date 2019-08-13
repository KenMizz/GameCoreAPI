<?php

namespace yl14\GameCoreAPI\api;


class gamecore {

    private $plugin;

    private $registered = [];


    public function __construct(\yl14\GameCoreAPI\GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    public function registerGame(string $name) : bool{
        if(!isset($this->registered[$name])) {
            $this->registered[$name] = array(
                'id' => \yl14\GameCoreAPI\utils::generateENum(),
                'chatchannel' => array()
            );
            return true;
        }
        return false;
    }
}
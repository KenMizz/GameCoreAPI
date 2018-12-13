<?php

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;

use yl13\GameCoreAPI\GameCoreAPI;

class gamecore {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    
    public function registerGame(String $name, String $author = null) {
        $registeredGames = $this->plugin->get($this->plugin, 'RGAME');
        $id = $this->plugin->randnum(8);
        while(isset($registeredGames[$id])) {
            $id = $this->plugin->randnum(8); 
        }
        $registeredGames[$id] = array(
            'name' => $name,
            'author' => $author 
        );
        $this->plugin->set($this->plugin, 'RGAME', $registeredGames);
        if($author != null) {
            $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$name.TF::GREEN." 注册成功!作者:".TF::WHITE.$author);
        } else {
            $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$name.TF::GREEN." 注册成功!");
        }
    }
}
<?php

namespace yl14\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;
use yl14\GameCoreAPI\GameCoreAPI;

class GameCore {

    private $plugin;

    private $Games = [];

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * 注册小游戏以使用GameCoreAPI
     * @method bool registerGame(string $name, string $author)
     * 
     * @param String[] $name 小游戏名
     * @param String[] $author 作者名
     * 
     * @return String|null 如果注册成功将会返回
     */
    public function registerGame(string $name, array $author = []) : ?string {
        if(!isset($this->Games['name'])) {
            $id = \yl14\GameCoreAPI\utils\utils::generateENum(15);
            foreach($this->Games as $key => $value) {
                if($value['id'] == $id) {
                    continue;
                }
                break;
            }
            $this->Games[$name] = array(
                'id' => $id,
                'author' => $author,
                'chatchannel' => array(),
                'maps' => array()
            );
            $this->plugin->getLogger()->notice(TF::GREEN . '小游戏 ' . TF::WHITE . $name . TF::GREEN . ' 注册成功！作者: ' . TF::WHITE . implode(",", $author));
            return $id;
        }
        return null;
    }
}
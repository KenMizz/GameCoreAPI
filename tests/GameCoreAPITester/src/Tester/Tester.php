<?php

/**
 * The only purpose of this plugin is to test GameCoreAPI
 */
namespace Tester;

use pocketmine\plugin\PluginBase;

use yl14\GameCoreAPI\GameCoreAPI;

class Tester extends PluginBase {

    public $gid = "";

    public function onEnable() {
        $this->gid = GameCoreAPI::getInstance()->getAPI()->getGameCore()->registerGame("GameCoreAPITester", array("游乐14"));
        echo $this->gid . PHP_EOL;
    }
}
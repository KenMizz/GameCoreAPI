<?php

/**
 * The only purpose of this plugin is to test GameCoreAPI
 */
namespace Tester;

use pocketmine\plugin\PluginBase;

use yl14\GameCoreAPI\GameCoreAPI;
use yl14\GameCoreAPI\utils\InGamePlayerSession;

class Tester extends PluginBase {

    public $gid = "";

    public function onEnable() {
        $this->getLogger()->notice("GameCoreAPITester has been enabled");
        $this->getLogger()->info("GameCoreAPI Version: " . GameCoreAPI::getInstance()->getAPI()->getGameCore()->getGameCoreAPIVersion());
        $this->gid = GameCoreAPI::getInstance()->getAPI()->getGameCore()->registerGame("GameCoreAPITester", array("游乐14", "游乐13", "游乐12", "KenMizz"));
        var_dump($this->gid);
        GameCoreAPI::getInstance()->getAPI()->getChatChannel()->create($this->gid, "ChannelTest");
        GameCoreAPI::getInstance()->getAPI()->getChatChannel()->create($this->gid, "FormatTest", "(PLAYERNAME)[MESSAGE]");
    }

    public function onCommand(\pocketmine\command\CommandSender $sender, \pocketmine\command\Command $command, string $label, array $args): bool{
        $cmd= strtolower($command->getName());
        if($cmd == 'gtest') {
            if(!isset($args[0])) {
                return false;
            }
            switch($args[0]) {

                default:
                    return false;
                break;

                case 'ct':
                    GameCoreAPI::getInstance()->getAPI()->getChatChannel()->getChatChannel($this->gid, "ChannelTest")->addPlayer(InGamePlayerSession::getPlayer($sender));
                    return true;
                break;

                case 'tt':
                    GameCoreAPI::getInstance()->getAPI()->getChatChannel()->getChatChannel($this->gid, "FormatTest")->addPlayer(InGamePlayerSession::getPlayer($sender));
                    return true;
            }
        }
    }
}
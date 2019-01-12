<?php

declare(strict_types=1);

namespace Tester;

use pocketmine\plugin\PluginBase;
use pocketmine\command\{
    CommandSender, Command
};

use yl13\GameCoreAPI\GameCoreAPI;

class Tester extends PluginBase {

    private $id = null;

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        if($command->getName() == 'rtp') {
            if(!isset($args[0])) {
                return false;
            }
            switch($args[0]) {

                default:
                    return false;
                break;

                case 'r':
                    $this->id = GameCoreAPI::getInstance()->api->getGameCoreAPI()->registerGame("Tester");
                    return true;
                break;

                case 'c':
                    GameCoreAPI::getInstance()->api->getChatChannelAPI()->create($this->id, 'Tester');
                    GameCoreAPI::getInstance()->api->getChatChannelAPI()->setFormat($this->id, "Tester", "{PLAYER_NAME} MESSAGE");
                    return true;
                break;

                case 'add':
                    GameCoreAPI::getInstance()->api->getChatChannelAPI()->addPlayer($this->id, 'Tester', [$this->getServer()->getPlayerExact('YL12Win10')]);
                    return true;
                break;

                case 'remove':
                    GameCoreAPI::getInstance()->api->getChatChannelAPI()->removePlayer($this->id, 'Tester', [$this->getServer()->getPlayerExact('YL12Win10')]);
                    return true;
                break;

                case 'mute':
                    GameCoreAPI::getInstance()->api->getChatChannelAPI()->setMute($this->id, 'Tester', true);
                break;
            }
        }
    }
}
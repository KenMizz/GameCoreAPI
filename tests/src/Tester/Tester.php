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

    public function onEnable() {
        $this->id = GameCoreAPI::getInstance()->api->getGameCoreAPI()->registerGame("Tester");
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        if($command->getName() == 'rtp') {
            if(!isset($args[0])) {
                return false;
            }
            switch($args[0]) {

                default:
                    return false;
                break;

                //ChatChannel
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
                    return true;
                break;
                //End

                case 'getm':
                    $sender->sendMessage('You have:'.GameCoreAPI::getInstance()->api->getEconomyAPI()->getMoney($this->id, $sender));
                    return true;
                break;

                case 'addm':
                    $sender->sendMessage('add '.$args[1]);
                    $result = GameCoreAPI::getInstance()->api->getEconomyAPI()->addMoney($this->id, $sender, (int)$args[1]);
                    var_dump($result);
                    return true;
                break;

                case 'reducem':
                    $sender->sendMessage('remove '.$args[1]);
                    $result = GameCoreAPI::getInstance()->api->getEconomyAPI()->reduceMoney($this->id, $sender, (int)$args[1]);
                    var_dump($result);
                    return true;
            }
        }
    }
}
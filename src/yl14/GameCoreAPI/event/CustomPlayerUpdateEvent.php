<?php

namespace yl14\GameCoreAPI\event;

use pocketmine\event\Cancellable;
use pocketmine\event\plugin\PluginEvent;

use yl14\GameCoreAPI\utils\CustomPlayer;
use yl14\GameCoreAPI\utils\InGamePlayerSession;

class CustomPlayerUpdateEvent extends PluginEvent implements Cancellable{

    public static $handlerList = null;

    public function __construct(CustomPlayer $customPlayer) {
        InGamePlayerSession::updatePlayer($customPlayer);
    }
} 
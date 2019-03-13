<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI\api;

use pocketmine\Player;


class economy extends API {

    private const FAILED_REASON = [
        'player.not.exist' => '玩家不存在'
    ];

    /**
     * @param int $gameid
     * @param pocketmine\Player $player
     * @param int $digit
     * 
     * @return bool
     */
    final public function setMoney(int $gameid, Player $player, int $digit) : bool {
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGame[$gameid])) { //小游戏id有效
            if(parent::getPlugin()->getConfigure('economy', 'enabled')) { //API开启了
                parent::getPlugin()->setPlayerData(parent::getPlugin(), $player, 'MONEY', $digit);
                //parent::getPlugin()->getLogger()->notice();
            }
        }
    }
}
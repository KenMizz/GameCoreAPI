<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI\api;

use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;


class economy extends API {

    private const FAILED_REASON = [
        'API_DISABLED' => 'api没有被启用',
        'MONEY_MAX_LIMIT' => '已超过配置文件的最大数值限制',
        'MONEY_MIN_LIMIT' => '数值不能小于0'
    ];

    /**
     * @param int $gameid
     * @param pocketmine\Player $player
     * @param int $digit
     * @description 设置玩家金钱
     * 
     * @return bool
     */
    final public function setMoney(int $gameid, Player $player, int $digit) : bool {
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGame[$gameid])) { //小游戏id有效
            if(parent::getPlugin()->getConfigure('economy', 'enabled')) { //API开启了
                if($digit <= parent::getPlugin()->getConfigure('economy', 'money-max-limit')) {
                    parent::getPlugin()->setPlayerData(parent::getPlugin(), $player, 'MONEY', $digit);
                    parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 设置玩家金钱".TF::GREEN."成功");
                    return true;
                }
                parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW."设置玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['MONEY_MAX_LIMIT']);
                return false;
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW."设置玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."设置玩家金钱".TF::YELLOW."失败，原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
        return false;
    }

    /**
     * @param int $gameid
     * @param pocketmine\Player $player
     * @param int $digit
     * @description 增加玩家金钱
     * 
     * @return bool
     */
    final public function addMoney(int $gameid, Player $player, int $digit) : bool {
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGame[$gameid])) { //小游戏id有效
            if(parent::getPlugin()->getConfigure('economy', 'enabled')) {
                $playerData = parent::getPlugin()->getPlayerData(parent::getPlugin(), $player);
                if(!($playerData['money'] + $digit >= parent::getPlugin()->getConfigure('economy', 'money-max-limit'))) {
                    parent::getPlugin()->setPlayerData(parent::getPlugin(), $player, 'MONEY', $playerData['money'] + $digit);
                    parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 添加玩家金钱".TF::GREEN."成功");
                    return true;
                }
                parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 添加玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['MONEY_MAX_LIMIT']);
                return false;
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 添加玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."添加玩家金钱".TF::YELLOW."失败，原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
        return false;
    }

    /**
     * @param int $gameid
     * @param pocketmine\Player $player
     * @param int $digit
     * @description 减少玩家金钱
     * 
     * @return bool
     */
    final public function reduceMoney(int $gameid, Player $player, int $digit) : bool {
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGame[$gameid])) { //小游戏id有效
            if(parent::getPlugin()->getConfigure('economy', 'enabled')) {
                $playerData = parent::getPlugin()->getPlayerData(parent::getPlugin(), $player);
                if(!($playerData['money'] - $digit < 0)) {
                    parent::getPlugin()->setPlayerData(parent::getPlugin(), $player, 'MONEY', $playerData['money'] - $digit);
                    parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 减少玩家金钱".TF::GREEN."成功");
                    return true;
                }
                parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW."减少玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['MONEY_MIN_LIMIT']);
                return false;
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW."减少玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."减少玩家金钱".TF::YELLOW."失败，原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
        return false;
    }

    /**
     * @param int $gameid
     * @param pocketmine\Player $player
     * @description 获取玩家金钱
     * 
     * @return int|null
     */
    final public function getMoney(int $gameid, Player $player) : ?int {
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if($registeredGame[$gameid]) {
            if(parent::getPlugin()->getConfigure('economy', 'enabled')) {
                $playerData = parent::getPlugin()->getPlayerData(parent::getPlugin(), $player);
                parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 获取玩家金钱".TF::GREEN."成功");
                return $playerData['money'];
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW."获取玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."获取玩家金钱".TF::YELLOW."失败，原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
        return false;
    }
}
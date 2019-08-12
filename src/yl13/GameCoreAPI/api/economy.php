<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI\api;

use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;


class economy {

    private $plugin;

    private const FAILED_REASON = [
        'API_DISABLED' => 'api没有被启用',
        'MONEY_MAX_LIMIT' => '已超过配置文件的最大数值限制',
        'MONEY_MIN_LIMIT' => '数值不能小于0'
    ];

    public function __construct(\yl13\GameCoreAPI\GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param int $gameid
     * @param pocketmine\Player $player
     * @param int $digit
     * @description 设置玩家金钱
     * 
     * @return bool
     */
    final public function setMoney(int $gameid, Player $player, int $digit) : bool {
        $registeredGame = $this->plugin->get($this->plugin, 'RGAME');
        if(isset($registeredGame[$gameid])) { //小游戏id有效
            if($this->plugin->getConfigure('economy', 'enabled')) { //API开启了
                if($digit <= $this->plugin->getConfigure('economy', 'money-max-limit')) {
                    $this->plugin->setPlayerData($this->plugin, $player, 'MONEY', $digit);
                    $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 设置玩家金钱".TF::GREEN."成功");
                    return true;
                }
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW."设置玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['MONEY_MAX_LIMIT']);
                return false;
            }
            $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW."设置玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['API_DISABLED']);
            return false;
        }
        $this->plugin->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."设置玩家金钱".TF::YELLOW."失败，原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
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
        $registeredGame = $this->plugin->get($this->plugin, 'RGAME');
        if(isset($registeredGame[$gameid])) { //小游戏id有效
            if($this->plugin->getConfigure('economy', 'enabled')) {
                $playerData = $this->plugin->getPlayerData($this->plugin, $player);
                if(!($playerData['money'] + $digit >= $this->plugin->getConfigure('economy', 'money-max-limit'))) {
                    $this->plugin->setPlayerData($this->plugin, $player, 'MONEY', $playerData['money'] + $digit);
                    $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 添加玩家金钱".TF::GREEN."成功");
                    return true;
                }
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 添加玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['MONEY_MAX_LIMIT']);
                return false;
            }
            $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 添加玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['API_DISABLED']);
            return false;
        }
        $this->plugin->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."添加玩家金钱".TF::YELLOW."失败，原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
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
        $registeredGame = $this->plugin->get($this->plugin, 'RGAME');
        if(isset($registeredGame[$gameid])) { //小游戏id有效
            if($this->plugin->getConfigure('economy', 'enabled')) {
                $playerData = $this->plugin->getPlayerData($this->plugin, $player);
                if(!($playerData['money'] - $digit < 0)) {
                    $this->plugin->setPlayerData($this->plugin, $player, 'MONEY', $playerData['money'] - $digit);
                    $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 减少玩家金钱".TF::GREEN."成功");
                    return true;
                }
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW."减少玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['MONEY_MIN_LIMIT']);
                return false;
            }
            $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW."减少玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['API_DISABLED']);
            return false;
        }
        $this->plugin->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."减少玩家金钱".TF::YELLOW."失败，原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
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
        $registeredGame = $this->plugin->get($this->plugin, 'RGAME');
        if($registeredGame[$gameid]) {
            if($this->plugin->getConfigure('economy', 'enabled')) {
                $playerData = $this->plugin->getPlayerData($this->plugin, $player);
                $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 获取玩家金钱".TF::GREEN."成功");
                return $playerData['money'];
            }
            $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW."获取玩家金钱失败，原因:".TF::WHITE.self::FAILED_REASON['API_DISABLED']);
            return false;
        }
        $this->plugin->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."获取玩家金钱".TF::YELLOW."失败，原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
        return false;
    }
}
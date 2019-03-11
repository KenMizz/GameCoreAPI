<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI\api;

use pocketmine\Player;
use pocketmine\utils\TextFormat as TF;

class chatchannel extends API {

    private const FAILED_REASON = [
        'GAMEID_NOT_REGISTERED' => '游戏id没有被注册!',
        'CHATCHANNEL_NOT_OWNED' => '小游戏不是指定聊天频道的创建者',
        'NAME_EXISTED' => '聊天频道名已存在',
        'NAME_NOT_EXISTED' => '聊天频道名不存在',
        'API_DISABLED' => 'api没有被启用'
    ];

    final public function create(int $gameid, String $name) : bool {
        /**
         * 创建聊天频道
         * require: int 小游戏id, String 聊天频道名
         * return: bool
         */
        $registeredGames = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGames[$gameid])) {
            $ChatChannel = parent::getPlugin()->get(parent::getPlugin(), 'CHATCHANNEL');
            if(parent::getPlugin()->getConfigure('chatchannel', 'enabled')) {
                if(!isset($ChatChannel[$name])) {
                    $ChatChannel[$name] = array(
                        'id' => $gameid,
                        'format' => null,
                        'players' => [],
                        'mute' => false
                    );
                    parent::getPlugin()->set(parent::getPlugin(), 'CHATCHANNEL', $ChatChannel);
                    parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 创建聊天频道".TF::WHITE.$name.TF::GREEN."成功");
                    return true;
                }
                parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 创建聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['NAME_EXISTED']);
                return false;
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 创建聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."创建聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
        return false;
    }

    final public function setFormat(int $gameid, String $name, String $format) : bool {
        /**
         * 设置聊天频道的聊天格式
         * require int 小游戏id, String 聊天频道名, String 格式
         * return: bool
         * 聊天频道格式设定可用:
         * PLAYER_NAME 玩家名
         * MESSAGE 玩家信息
         */
        $registeredGames = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGames[$gameid])) {
            $ChatChannel = parent::getPlugin()->get(parent::getPlugin(), 'CHATCHANNEL');
            if(parent::getPlugin()->getConfigure('chatchannel', 'enabled')) {
                if(isset($ChatChannel[$name])) {
                    if($ChatChannel[$name]['id'] == $gameid) {
                        $ChatChannel[$name]['format'] = $format;
                        parent::getPlugin()->set(parent::getPlugin(), 'CHATCHANNEL', $ChatChannel);
                        parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 设置聊天频道格式".TF::WHITE.$name.TF::GREEN."成功");
                        return true;
                    }
                    parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 设置聊天频道格式".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['CHATCHANNEL_NOT_OWNED']);
                    return false;
                }
                parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 设置聊天频道格式".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['NAME_NOT_EXISTED']);
                return false;
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 设置聊天频道格式".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."设置聊天频道格式".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['GAMEID_NOT_REGISTERED']);
        return false;
    }

    final public function addPlayer(int $gameid, String $name, Array $players) : bool {
        /**
         * 添加聊天频道内的玩家
         * require int 小游戏id, String 聊天频道名, \pocketmine\Player Array 玩家
         * return: bool
         */
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGame[$gameid])) {
            $ChatChannel = parent::getPlugin()->get(parent::getPlugin(), 'CHATCHANNEL');
            if(parent::getPlugin()->getConfigure('chatchannel', 'enabled')) {
                if(isset($ChatChannel[$name])) {
                    $unsuccess = 0;
                    $success = 0;
                    foreach($players as $player) {
                        if($player instanceof Player) {
                            if($player->isOnline()) {
                                if(!isset($ChatChannel[$name]['players'][$player->getName()])) {
                                    $ChatChannel[$name]['players'][$player->getName()] = $player;
                                    parent::getPlugin()->setPlayerData(parent::getPlugin(), $player, 'CHATCHANNEL', $name);
                                    $success++;
                                } else {
                                    $unsuccess++;
                                }
                            } else {
                                $unsuccess++;
                            }
                        } else {
                            $unsuccess++;
                        }
                    }
                    parent::getPlugin()->set(parent::getPlugin(), 'CHATCHANNEL', $ChatChannel);
                    parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 添加玩家至聊天频道".TF::WHITE.$name.TF::GREEN."成功,成功添加:".TF::WHITE.$success.TF::GREEN."个,添加失败:".TF::WHITE.$unsuccess.TF::GREEN."个");
                    return true;
                }
                parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 添加玩家至聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['NAME_NOT_EXISTED']);
                return false;
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 添加玩家至聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."添加玩家至聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['GAMEID_NOT_REGISTERED']);
        return false;
    }

    final public function removePlayer(int $gameid, String $name, Array $players) : bool {
        /**
         * 移除聊天频道内的玩家
         * require: int 小游戏id, String 聊天频道名, \pocketmine\Player Array 玩家
         * return: bool
         * 只允许移除自己创建的聊天频道内的玩家
         */
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGame[$gameid])) {
            $ChatChannel = parent::getPlugin()->get(parent::getPlugin(), 'CHATCHANNEL');
            if(parent::getPlugin()->getConfigure('chatchannel', 'enabled')) {
                if(isset($ChatChannel[$name])) {
                    if($ChatChannel[$name]['id'] == $gameid) {
                        $unsuccess = 0;
                        $success = 0;
                        foreach($players as $player) {
                            if($player instanceof Player) {
                                if($player->isOnline()) {
                                    if(isset($ChatChannel[$name]['players'][$player->getName()])) {
                                        unset($ChatChannel[$name]['players'][$player->getName()]);
                                        parent::getPlugin()->setPlayerData(parent::getPlugin(), $player, 'CHATCHANNEL', null);
                                        $success++;
                                    } else {
                                        $unsuccess++;
                                    }
                                } else {
                                    $unsuccess++;
                                }
                            } else {
                                $unsuccess++;
                            }
                        }
                        parent::getPlugin()->set(parent::getPlugin(), 'CHATCHANNEL', $ChatChannel);
                        parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 从聊天频道".TF::WHITE.$name.TF::GREEN."移除玩家成功,成功移除:".TF::WHITE.$success.TF::GREEN."个,移除失败:".TF::WHITE.$unsuccess.TF::GREEN."个");
                        return true;
                    }
                    parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 从聊天频道".TF::WHITE.$name.TF::YELLOW."移除玩家失败,原因:".TF::WHITE.self::failedreason['CHATCHANNEL_NOT_OWNED']);
                    return false;
                }
                parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 从聊天频道".TF::WHITE.$name.TF::YELLOW."移除玩家失败,原因:".TF::WHITE.self::failedreason['NAME_NOT_EXISTED']);
                return false;
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 从聊天频道".TF::WHITE.$name.TF::YELLOW."移除玩家失败,原因:".TF::WHITE.self::failedreason['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."从聊天频道".TF::WHITE.$name.TF::YELLOW."移除玩家失败,原因:".TF::WHITE.self::failedreason['GAMEID_NOT_REGISTERED']);
        return false;
    }

    final public function setMute(int $gameid, String $name, boolean $options) : bool {
        /**
         * 设置聊天频道禁言
         * require: int 小游戏id, String 聊天频道名, boolean 开/关禁言
         * return: bool
         * 只允许给自己创建的聊天频道设置禁言 
         */
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGame[$gameid])) {
            $ChatChannel = parent::getPlugin()->get(parent::getPlugin(), 'CHATCHANNEL');
            if(parent::getPlugin()->getConfigure('chatchannel', 'enabled')) {
                if(isset($ChatChannel[$name])) {
                    if($ChatChannel[$name]['id'] == $gameid) {
                        $ChatChannel[$name]['mute'] = $options;
                        parent::getPlugin()->set(parent::getPlugin(), 'CHATCHANNEL', $ChatChannel);
                        parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 设置聊天频道禁言".TF::WHITE.$name.TF::GREEN."成功,当前状态:".TF::WHITE.$options);
                        return true;
                    }
                    parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 设置聊天频道禁言".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['CHATCHANNEL_NOT_OWNED']);
                    return false;
                }
                parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 设置聊天频道禁言".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['NAME_NOT_EXISTED']);
                return false;
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 设置聊天频道禁言".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."设置聊天频道禁言".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['GAMEID_NOT_REGISTERED']);
        return false;
    }

    final public function broadcastMessage(int $gameid, String $name, String $message) : bool {
        /**
         * 在指定频道发送全体信息
         * require: int 小游戏id, String 聊天频道名, String 聊天信息
         * return: bool
         */
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGame[$gameid])) {
            $ChatChannel = parent::getPlugin()->get(parent::getPlugin(), 'CHATCHANNEL');
            if(parent::getPlugin()->getConfigure('chatchannel', 'enabled')) {
                if($ChatChannel[$name]['id'] == $gameid) {
                    $Players = $ChatChannel[$name]['players'];
                    parent::getPlugin()->getServer()->broadcastMessage($message, $Players);
                    parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 发送全局信息到".TF::WHITE.$name.TF::GREEN."成功");
                    return true;
                }
                parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 发送全局信息到".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['CHATCHANNEL_NOT_OWNED']);
                return false;
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 发送全局信息到".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."发送全局信息到".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['GAMEID_NOT_REGISTERED']);
        return false;
    }

    final public function remove(int $gameid, String $name) : bool {
        /**
         * 移除聊天频道
         * require: int 小游戏id, String 聊天频道名
         * return: bool
         * 只允许移除自己创建的聊天频道
         */
        $registeredGame = parent::getPlugin()->get(parent::getPlugin(), 'RGAME');
        if(isset($registeredGame[$gameid])) {
            $ChatChannel = parent::getPlugin()->get(parent::getPlugin(), 'CHATCHANNEL');
            if(parent::getPlugin()->getConfigure('chatchannel', 'enabled')) {
                if(isset($ChatChannel[$name])) {
                    if($ChatChannel[$name]['id'] == $gameid) {
                        $players = $ChatChannel[$name]['players'];
                        foreach($players as $player) {
                            if($player instanceof Player) {
                                if($player->isOnline()) {
                                    unset($ChatChannel[$name]['players'][$player->getName()]);
                                    parent::getPlugin()->setPlayerData(parent::getPlugin(), $player, 'CHATCHANNEL', null);
                                }
                            }
                        }
                        unset($ChatChannel[$name]);
                        parent::getPlugin()->set(parent::getPlugin(), 'CHATCHANNEL', $ChatChannel);
                        parent::getPlugin()->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::GREEN." 移除聊天频道".TF::WHITE.$name.TF::GREEN."成功");
                        return true;
                    }
                    parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 创建聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['CHATCHANNEL_NOT_OWNED']);
                    return false;
                }
            }
            parent::getPlugin()->getLogger()->warning("小游戏 ".TF::WHITE.parent::getPlugin()->getGameNameById($gameid).TF::YELLOW." 创建聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['API_DISABLED']);
            return false;
        }
        parent::getPlugin()->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."移除聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.self::failedreason['GAMEID_NOT_REGISTERED']);
        return false;
    }
}
<?php

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;

use yl13\GameCoreAPI\{GameCoreAPI, utils};

class ChatChannel {

    private $plugin;
    private $id;
    private $gid;

    private $failedreason = [
        'channelname.already.existed' => '聊天频道名已创建',
        'gameid.unregonize' => '小游戏id不存在',
        'chatchannel.not.created.by.this.game' => '指定聊天频道非此小游戏创建',
        'chatchannel.not.existed' => '指定聊天频道不存在'
    ];

    public function __construct(GameCoreAPI $plugin, int $id, int $gid) {
        $this->plugin = $plugin;
        $this->id = $id;
        $this->gid = $gid;
    }

    //create
    public final function create(int $gameid, String $chatchannelname) {
        /**
         * 创建一个聊天频道
         * 需要:小游戏id(int) 聊天频道名(String)
         */
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
        if(utils::deep_in_array($gameid, $registeredGame)) {
            $gamename = $this->plugin->getGameNameById($this->id, $gameid);
            if(!utils::deep_in_array($chatchannelname, $chatchannel)) {
                $chatchannel[$chatchannelname] = array(
                    'name' => $chatchannelname,
                    'id' => $gameid,
                    'format' => null,
                    'players' => array()
                );
                $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
                $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$gamename.TF::GREEN." 创建聊天频道".TF::WHITE.$chatchannelname.TF::GREEN."成功");
            } else {
                $this->plugin->getLogger()->warning(TF::RED."小游戏 ".TF::WHITE.$gamename.TF::RED." 创建聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['channelname.already.existed']);
            }
        } else {
            $this->plugin->getLogger()->warning(TF::RED."小游戏ID:".TF::WHITE.$gameid.TF::RED." 创建聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }

    //remove
    public final function remove(int $gameid, String $chatchannelname) {
        /**
         * 删除一个聊天频道
         * 需要:小游戏id(int) 聊天频道名(String)
         * 注:只允许删除自己创建的聊天频道
         */
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
        if(utils::deep_in_array($gameid, $registeredGame)) {
            $id = $chatchannel[$chatchannelname]['id'];
            $gamename = $this->plugin->getGameNameById($this->id, $gameid);
            if($id == $gameid) {
                $players = $chatchannel[$chatchannelname]['players'];
                foreach($players as $p) {
                    $Player = $this->plugin->getServer()->getPlayerExact($p);
                    if($Player) {
                        unset($chatchannel[$chatchannelname]['players'][$p]);
                        $this->plugin->setPlayerData($this->id, $Player->getName(), "CHATCHANNEL", null);
                    }
                }
                unset($chatchannel[$chatchannelname]);
                $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
                $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::AQUA." 已移除聊天频道".TF::WHITE.$chatchannelname);
            } else {
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$gamename.TF::RED." 移除聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['chatchannel.not.created.by.this.game']);
            }
        } else {
            $this->plugin->getLogger()->warning("小游戏ID:".TF::WHITE.$gameid.TF::RED."移除聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }

    //addPlayer
    public final function addPlayer(int $gameid, String $chatchannelname, Array $players) {
        /**
         * 添加玩家至指定的聊天频道
         * 需要:小游戏id(int) 聊天频道名(String) 玩家名(Array)
         * 注:玩家名不强制大小写
         */
        $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        if(utils::deep_in_array($gameid, $registeredGame)) {
            $gamename = $this->plugin->getGameNameById($this->id, $gameid);
            if(utils::deep_in_array($chatchannelname, $chatchannel)) {
                $players = $chatchannel[$chatchannelname]['players'];
                foreach($players as $p) {
                    $Player = $this->plugin->getServer()->getPlayerExact($p);
                    if($Player) {
                        if(!utils::deep_in_array($Player->getName(), $players)) {
                            $players[$Player->getName()] = $Player;
                        }
                    }
                }
                $chatchannel[$chatchannelname]['players'] = $players;
                $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
                $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::AQUA." 添加玩家至聊天频道".TF::WHITE.$chatchannelname.TF::AQUA."成功");
            } else {
                $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::RED." 添加玩家至聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".$this->failedreason['chatchannel.not.existed']);
            }
        } else {
            $this->plugin->getLogger()->warning("小游戏ID:".TF::WHITE.$gameid.TF::RED."添加玩家至聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }

    //removePlayer
    public final function removePlayer(int $gameid, String $chatchannelname, Array $players) {
        /**
         * 从指定的聊天频道移除玩家
         * 需要:小游戏id(int) 聊天频道名(String) 玩家名(Array)
         * 注:玩家名不强制大小写
         * 注:只允许移除自己创建的聊天频道
         */
        $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        if(utils::deep_in_array($gameid, $registeredGame)) {
            $gamename = $this->plugin->getGameNameById($this->id, $gameid);
            if(utils::deep_in_array($chatchannelname, $chatchannel)) {
                $id = $chatchannel[$chatchannelname]['id'];
                if($id == $gameid) {
                    $players = $chatchannel[$chatchannelname]['players'];
                    foreach($players as $p) {
                        $Player = $this->plugin->getServer()->getPlayerExact($p);
                        if($Player) {
                            if(utils::deep_in_array($Player->getName(), $players)) {
                                unset($players[$Player->getName()]);
                            }
                        }
                    }
                    $chatchannel[$chatchannelname]['players'] = $players;
                    $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
                    $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::AQUA." 移除玩家至聊天频道".TF::WHITE.$chatchannelname.TF::AQUA."成功");
                } else {
                    $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$gamename.TF::RED." 移除玩家至聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".$this->failedreason['chatchannel.not.created.by.this.game']);
                }
            } else {
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$gamename.TF::RED." 移除玩家至聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".$this->failedreason['chatchannel.not.existed']);
            }
        } else {
            $this->plugin->getLogger()->warning("小游戏ID:".TF::WHITE.$gameid.TF::RED."移除玩家至聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }

    //setFormat
    public final function setFormat(int $gameid, String $chatchannelname, String $format) {
        /**
         * 设置指定聊天频道的聊天格式
         * 需要:小游戏id(int) 聊天频道名(Stirng) 格式(String)
         * 注:只允许设置自己创建的聊天频道的聊天格式
         * 可用:PLAYER_NAME(玩家名) MESSAGE(玩家聊天内容)
         */
        $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        if(utils::deep_in_array($gameid, $registeredGame)) {
            $gamename = $this->plugin->getGameNameById($this->id, gameid);
            if(utils::deep_in_array($chatchannelname, $chatchannel)) {
                $id = $chatchannel[$chatchannelname]['id'];
                if($id == $gameid) {
                    $chatchannel[$chatchannelname]['format'] = $format;
                    $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
                    $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::AQUA." 设置聊天频道".TF::WHITE.$chatchannelname.TF::AQUA."的聊天格式成功");
                } else {
                    $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$gamename.TF::RED." 设置聊天频道".TF::WHITE.$chatchannelname.TF::RED."的聊天格式失败,原因:".TF::WHITE.$this->failedreason['chatchannel.not.created.by.this.game']);
                }
            } else {
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$gamename.TF::RED." 设置聊天频道".TF::WHITE.$chatchannelname.TF::RED."的聊天格式失败,原因:".TF::WHITE.$this->failedreason['chatchannel.not.existed']);
            }
        } else {
            $this->plugin->getLogger()->warning("小游戏ID:".TF::WHITE.$gameid.TF::RED."设置聊天频道".TF::WHITE.$chatchannelname.TF::RED."的聊天格式失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }

    //broadcastMessage
    public final function broadcastMessage(int $gameid, String $chatchannelname, String $message) {
        /**
         * 在指定的聊天频道输出全局信息
         * 需要:小游戏id(int) 聊天频道名(String) 信息(String)
         */
        $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        if(utils::deep_in_array($gameid, $registeredGame)) {
            $gamename = $this->plugin->getGameNameById($this->id, $gameid);
            if(utils::deep_in_array($chatchannelname, $chatchannel)) {
                $Player = $chatchannel[$chatchannelname]['players'];
                $this->plugin->getServer()->broadcastMessage($message, $Player);
                $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::AQUA." 向聊天频道".TF::WHITE.$chatchannelname.TF::AQUA."发送全局信息成功");
            } else {
                $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::RED." 向聊天频道".TF::WHITE.$chatchannelname.TF::RED."发送全局信息失败,原因:".TF::WHITE.$this->reason['chatchannel.not.existed']);
            }
        } else {
            $this->plugin->getLogger()->warning("小游戏ID:".TF::WHITE.$gameid.TF::RED."向聊天频道".TF::WHITE.$chatchannelname.TF::RED."发送全局信息失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }

    //createDefaultChatChannel
    public final function createDefaultChatChannel(int $gid, String $name) : bool {
        if($this->gid = $gid) {
            $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
            $chatchannel[$name] = array(
                'name' => $name,
                'id' => $gid,
                'format' => null,
                'players' => array()
            );
            $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
            $this->plugin->getLogger()->notice(TF::WHITE."默认聊天频道设置:{$name}");
            return true;
        }
        return false;
    }

    //addPlayerToDefaultChatChannel
    public final function addPlayerToDefaultChatChannel(int $gid, Array $players) : bool {
        if($this->gid = $gid) {
            $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
            $Settings = $this->plugin->get($this->id, "SETTINGS");
            if(!$Settings['default-chatchannel'] == null) {
                $DefaultChatChannelName = $Settings['default-chatchannel'];
                $ps = $chatchannel[$DefaultChatChannelName]['players'];
                foreach($players as $p) {
                    $Player = $this->plugin->getServer()->getPlayerExact($p);
                    if($Player) {
                        if(!utils::deeep_in_array($Player->getName(), $ps)) {
                            $ps[$Player->getName()] = $Player;
                        }
                    }
                }
                $chatchannel[$chatchannelname]['players'] = $ps;
                $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
                return true;
            }
            return false;
        }
        return false;
    }
}
<?php

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;

use yl13\GameCoreAPI\GameCoreAPI;
use yl13\GameCoreAPI\utils;

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
    public function create(int $gameid, String $chatchannelname) {
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
                    'players' => array()
                );
                $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
                $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$gamename.TF::GREEN." 创建聊天频道".TF::WHITE.$chatchannelname.TF::GREEN."成功");
            } else {
                $this->plugin->getLogger()->warning(TF::RED."小游戏 ".TF::WHITE.$gamename.TF::RED." 创建聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['channelname.already.existed']);
            }
        } else {
            $this->plugin->getLogger()->warning(TF::RED."小游戏 ".TF::WHITE.$gamename.TF::RED." 创建聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }

    //remove
    public function remove(int $gameid, String $chatchannelname) {
        /**
         * 删除一个聊天频道
         * 需要:小游戏id(int) 聊天频道名(String)
         * 注:只允许删除自己创建的聊天频道
         */
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
        if(utils::deep_in_array($gameid, $registeredGame)) {
            $id = $chatchannel[$chatchannelname]['id'];
            $gamename = $this->plugin->getGameNameById($gameid);
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
    public function addPlayer(int $gameid, String $chatchannelname, Array $players) {
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
                            $this->players[$Player->getName()] = $Player;
                        }
                    }
                }
                $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
                $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$gamename.TF::AQUA." 添加玩家至聊天频道".TF::WHITE.$chatchannelname.TF::AQUA."成功");
            } else {
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$gamename.TF::RED." 添加玩家至聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['chatchannel.not.created.by.this.game']);
            }
        } else {
            $this->plugin->getLogger()->warning("小游戏ID:".TF::WHITE.$gameid.TF::RED."添加玩家至聊天频道".TF::WHITE.$chatchannelname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }

    //createDefaultChatChannel
    public function createDefaultChatChannel(int $gid, String $name) {
        if($this->gid = $gid) {
            $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
            $chatchannel[$name] = array(
                'name' => $name,
                'id' => $gid,
                'players' => array()
            );
            $this->plugin->override($this->id, "CHATCHANNEL", $chatchannel);
            $this->plugin->getLogger()->notice(TF::WHITE."默认聊天频道设置:{$name}");
        }
    }

    //addPlayerToDefaultChatChannel
    /*
    public function addPlayerToDefaultChatChannel(int $gid, Array $players) {
        if($this->gid = $gid) {
            $chatchannel = $this->plugin->get($this->id, "CHATCHANNEL");
            
        }
    }*/
}
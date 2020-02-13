<?php

namespace yl14\GameCoreAPI\api;

use yl14\GameCoreAPI\GameCoreAPI;
use yl14\GameCoreAPI\utils\ChatChannel as UtilsChatChannel;


class ChatChannel {

    private $plugin;

    private $channels = [];

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * 创建新的聊天频道
     * @param String $gameId 小游戏id
     * @param String $channelname 聊天频道名
     * @param String $chatformat 聊天格式
     * 
     * @return Boolean
     * 
     * Note:
     *  聊天格式提供了聊天频道内如何输出玩家信息的功能，以下是可用的格式
     *      玩家名: PLAYERNAME
     *      聊天信息: MESSAGE
     *      如果聊天频道的chatformat是{PLAYERNAME} === MESSAGE, 那么在游戏内就会是{xxx} === xxx，以此类推
     */
    public function create(string $gameId, string $channelname, string $chatformat = "") : bool{
        $isIdValid = $this->plugin->getAPI()->getGameCore()->isIdValid($this->plugin, $gameId);
        if($isIdValid) {
            if(!isset($this->channels[$gameId])) {
                $this->channels[$gameId] = [];
            }
            if(!isset($this->channels[$gameId][$channelname])) {
                $this->channels[$gameId][$channelname] = new UtilsChatChannel($this->plugin, $channelname, $chatformat);
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 移除聊天频道
     * @param String $gameId 小游戏id
     * @param String $channelname 聊天频道名
     * 
     * @return Boolean
     */
    public function remove(string $gameId, string $channelname) : bool{
        $isIdValid = $this->plugin->getAPI()->getGameCore()->isIdValid($this->plugin, $gameId);
        if($isIdValid) {
            if(isset($this->channels[$gameId][$channelname])) {
                $this->channels[$gameId][$channelname]->removeAll();
                unset($this->channels[$gameId][$channelname]);
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 获取聊天频道
     * @param String $gameId 小游戏id
     * @param String $channelname 频道名
     * 
     * @return \yl14\GameCoreAPI\utils\ChatChannel|Boolean
     */
    public function &getChatChannel(string $gameId, string $channelname) {
        $isIdValid = $this->plugin->getAPI()->getGameCore()->isIdValid($this->plugin, $gameId);
        if($isIdValid) {
            if(isset($this->channels[$gameId][$channelname])) {
                return $this->channels[$gameId][$channelname];
            }
            return false;
        }
        return false;
    }
}
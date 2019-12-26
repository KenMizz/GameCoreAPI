<?php

namespace yl14\GameCoreAPI\api;

use yl14\GameCoreAPI\{
    GameCoreAPI, utils\TextContainer
};
use yl14\GameCoreAPI\utils\ChatChannel as UtilsChatChannel;

class ChatChannel {

    private $plugin;
    
    private $channels = [];

    const USAGE = [
        'usage.chatchannel.create' => 'GREEN小游戏 WHITE %gamename% GREEN创建聊天频道 WHITE %channelname% 成功'
    ];

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * 创建聊天频道
     * 
     * @method bool create(string $gameid, string $channelname, string $chatformat = "")
     * 
     * @param String $gameid 小游戏id
     * @param String $channelname 频道名
     * @param String $chatformat 聊天格式
     * 
     * @return Boolean
     * 
     * Note: 聊天格式可以调整玩家在聊天时输出的内容，GameCoreAPI提供以下格式
     * message: 玩家发送的信息
     * playername: 玩家名
     * 例: $chatformat = "[playername]: message"
     * 这样的话在游戏内输出的内容就会是[XXX]: 你好！
     */
    public function create(string $gameid, string $channelname, string $chatformat = "") : bool {
        $isIdValid = $this->plugin->getAPI()->getGameCore()->isIdValid($this->plugin, $gameid);
        if($isIdValid) {
            if(!isset($this->channels[$gameid])) {
                $this->channels[$gameid] = array();
            }
            if(!isset($this->channels[$gameid][$channelname])) {
                $this->channels[$gameid][$channelname] = new UtilsChatChannel($channelname, $chatformat);
                $usage = str_replace(['%gamename%', '%channelname%'], [$this->plugin->getAPI()->getGameCore()->getGameNameById($gameid), $channelname], self::USAGE['usage.chatchannel.create']);
                $this->plugin->getLogger()->notice((new TextContainer($usage))->convertColor());
                return true;
            }
            return false;
        }
        return false;
    }
}
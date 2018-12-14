<?php

namespace yl13\GameCoreAPI\api;

use yl13\GameCoreAPI\GameCoreAPI;

class chatchannel {

    private $plugin;

    private $failreason = [
        'GAMEID_NOT_REGISTERED' => '游戏id没有被注册!',
        'NAME_EXISTED' => '聊天频道名已存在',
        'API_DISABLED' => 'api没有被启用'
    ];
    
    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    final public function create(int $gameid, String $name) : bool {
        /**
         * 创建聊天频道
         * require: int 小游戏id, String 聊天频道名
         * return: bool
         */
        $registeredGames = $this->plugin->get($this->plugin, 'RGAME');
        if(isset($registeredGames[$gameid])) {
            $ChatChannel = $this->plugin->get($this->plugin, 'CHATCHANNEL');
            if($this->plugin->getConfigure('chatchannel-enabled') == true) {
                if(!isset($ChatChannel[$name])) {
                    $ChatChannel[$name] = array(
                        'name' => $name,
                        'format' => null,
                        'players' => [],
                        'mute' => false
                    );
                    $this->plugin->set($this->plugin, 'CHATCHANNEL', $ChatChannel);
                    $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 创建聊天频道".TF::WHITE.$name.TF::GREEN."成功");
                    return true;
                }
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 创建聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.$this->failedreason['NAME_EXISTED']);
                return false;
            }
            $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 创建聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.$this->failedreason['API_DISABLED']);
            return false;
        }
        $this->plugin->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."创建聊天频道".TF::WHITE.$name.TF::YELLOW."失败,原因:".TF::WHITE.$this->failedreason['GAMEID_NOT_REGISTERED']);
        return false;
    }


}
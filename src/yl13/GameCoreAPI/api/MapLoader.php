<?php

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;

use yl13\GameCoreAPI\{GameCoreAPI, utils};

class MapLoader {

    private $plugin, $id;
    private $requestList = [];
    private $failedreason = [
        'map.not.found' => '指定地图无法找到',
        'map.remove.permission.denined' => '权限不足,无法移除指定地图',
        'map.not.load.by.this.game' => '指定地图非此游戏加载',
        'gameid.unregonize' => '小游戏id不存在'
    ];

    public function __construct(GameCoreAPI $plugin, int $id) {
        $this->plugin = $plugin;
        $this->id = $id;
    }

    public final function loadMap(int $gameid, String $mapname, String $changename = "unknown") {
        /**
         * 加载一张指定地图
         * (地图文件夹需要先放至plugins\GameCoreAPI\maps文件夹下)
         * 需要:小游戏id(int) 地图名(String)
         * 可用:更换地图名(String)
         */
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        if(utils::deep_in_array($gameid, $registeredGame)) {
            if(!is_dir($this->plugin->getDataFolder()."maps/{$mapname}")) {
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($this->id, $gameid).TF::RED." 无法加载地图,原因:".TF::WHITE.$this->$failedreason['map.not.found']);
            } else {
                if($changename == "unknown") {
                    $this->requestList[$gameid][$mapname] = $changename;
                    utils::recurse_copy($this->plugin->getDataFolder()."maps/{$mapname}", $this->plugin->getServer()->getDataPath()."worlds/{$mapname}");
                    $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($this->id, $gameid).TF::AQUA." 加载地图".TF::WHITE.$mapname.TF::AQUA."成功");
                } else {
                    $this->requestList[$gameid][$changename] = $mapname;
                    utils::recurse_copy($this->plugin->getDataFolder()."maps/{$mapname}", $this->plugin->getServer()->getDataPath()."worlds/{$changename}");
                    $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($this->id, $gameid).TF::AQUA." 加载地图".TF::WHITE.$mapname.TF::AQUA."成功,并改名为".TF::WHITE.$changename);
                }
            }
        } else {
            $this->plugin->getLogger()->warning("小游戏ID:".TF::WHITE.$gameid.TF::RED."加载地图".TF::WHITE.$mapname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }

    public final function removeMap(int $gameid, String $mapname) {
        /**
         * 移除一张指定地图
         * 注:只允许移除由自己加载的地图
         * 需要:小游戏id(int) 地图名(String)
         * 注:如果你加载的地图名是更改过名字的,那么地图名参数填成更改过名字的即可
         */
        $registeredGame = $this->plugin->get($this->id, "REGISTERED_GAME");
        if(utils::deep_in_array($gameid, $registeredGame)) {
            if(isset($this->requestList[$gameid][$mapname])) {
                if(is_dir($this->plugin->getServer()->getDataPath()."worlds/{$mapname}")) {
                    unset($this->requestList[$gameid][$mapname]);
                    $df = unlink($this->plugin->getServer()->getDataPath()."worlds/{$mapname}");
                    if($df) {
                        $this->plugin->getLogger()->notice("小游戏 ".TF::WHITE.$this->plugin->getGameNamById($this->id, $gameid).TF::AQUA." 移除地图".TF::WHITE.$mapname.TF::AQUA."成功");
                    } else {
                        $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($this->id, $gameid).TF::AQUA." 移除地图".TF::WHITE.$mapname.TF::RED."原因:".TF::WHITE.$this->failedreason['map.remove.permission.denined']);
                    }
                } else {
                    unset($this->requestList[$gameid][$mapname]);
                    $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($this->id, $gameid).TF::AQUA." 移除地图失败".TF::WHITE.$mapname.TF::RED."原因:".TF::WHITE.$this->failedreason['map.not.found']);
                }
            }
        } else {
            $this->plugin->getLogger()->warning("小游戏ID:".TF::WHITE.$gameid.TF::RED."移除地图".TF::WHITE.$mapname.TF::RED."失败,原因:".TF::WHITE.$this->failedreason['gameid.unregonize']);
        }
    }
}
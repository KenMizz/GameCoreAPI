<?php

namespace yl13\GameCoreAPI\api;

use pocketmine\utils\TextFormat as TF;
use pocketmine\level\Level;

use yl13\GameCoreAPI\GameCoreAPI;

class maploader {

    private $plugin;

    private const FAILED_REASON = [
        'GAMEID_NOT_REGISTERED' => '游戏id没有被注册!',
        'map.not.exists' => '指定地图不存在',
        'map.exists' => '指定地图已存在',
        'map.unload.failed' => '指定地图卸载失败'
    ];

    public function __construct(GameCoreAPI $plugin) {
        $this->plugin = $plugin;
    }

    final public function create(int $gameid, String $worldname) : bool {
        /**
         * 将/worlds文件夹下的某张地图创建成MapLoader API可调用的地图
         * require: int 小游戏id, String 世界名
         * return: bool
         */
        $registeredGame = $this->plugin->get($this->plugin, 'RGAME');
        if(isset($registeredGame[$gameid])) {
            $Level = $this->plugin->getServer()->getLevelByName($worldname);
            if($Level instanceof Level) {
                if(!is_dir($this->plugin->getDataFolder()."maps/{$worldname}")) {
                    $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 创建".TF::WHITE.$worldname.TF::GREEN."为游戏地图请求成功");
                    $result = $this->plugin->getServer()->unloadLevel($Level);
                    if($result) {
                        $this->plugin->getLogger()->notice(TF::YELLOW."卸载地图: ".TF::WHITE.$worldname.TF::YELLOW."成功,正在创建为游戏地图...");
                        $this->recurse_copy($this->plugin->getServer()->getDataPath()."worlds/{$worldname}", $this->plugin->getDataFolder()."maps/{$worldname}");
                        $this->plugin->getLogger()->notice(TF::GREEN."游戏地图创建成功!");
                        return true;
                    }
                    $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 创建".TF::WHITE.$worldname.TF::YELLOW."为游戏地图失败,原因:".TF::WHITE.self::FAILED_REASON['map.unload.failed']);
                    return false;
                }
                $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 创建".TF::WHITE.$worldname.TF::YELLOW."为游戏地图失败,原因:".TF::WHITE.self::FAILED_REASON['map.exists']);
                return false;
            }
            $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 创建".TF::WHITE.$worldname.TF::YELLOW."为游戏地图失败,原因:".TF::WHITE.self::FAILED_REASON['map.not.exists']);
            return false;
        }
        $this->plugin->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."创建".TF::WHITE.$worldname.TF::YELLOW."为游戏地图失败,原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
        return false;
    }

    final public function load(int $gameid, String $worldname, String $changedname = null) : bool {
        /**
         * 将GameCoreAPI的/maps文件夹下的某个地图加载进世界
         * require: int 小游戏id, String 世界名
         * 可用: String 加载进世界时的世界名
         * 注: $changedname参数可以让你以另一个名字把指定地图加载进世界,比如你想要加载的地图名为a,然后你在$changedname参数里填上了b,那么加载进世界的会是一个叫做b的世界名
         */
        $registeredGame = $this->plugin->get($this->plugin, 'RGAME');
        if(isset($registeredGame[$gameid])) {
            if(is_dir($this->plugin->getDataFolder()."maps/{$worldname}")) {
                if(is_string($changedname)) {
                    if(!is_dir($this->plugin->getServer()->getDataPath()."worlds/{$changedname}")) {
                        $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 加载地图".TF::WHITE.$worldname.TF::GREEN."请求成功");
                        $this->recurse_copy($this->plugin->getDataFolder()."maps/".$worldname."", $this->plugin->getServer()->getDataPath()."worlds/".$changedname."");
                        $this->plugin->getLogger()->notice(TF::YELLOW."地图: ".TF::WHITE.$worldname.TF::YELLOW."复制成功,正在修改nbt内的数据以保证地图名和文件夹名一致");
                        $this->plugin->getServer()->loadLevel($changedname);
                        $Level = $this->plugin->getServer()->getLevelByName($changedname);
                        $Tag = $Level->getProvider()->getLevelData();
                        $Tag->setString("LevelName", $changedname, true); //强制修改
                        $this->plugin->getLogger()->notice(TF::YELLOW."地图: ".TF::WHITE.$worldname.TF::YELLOW."nbt数据修改成功");
                        $this->plugin->getServer()->unloadLevel($Level);
                        $result = $this->plugin->getServer()->loadLevel($changedname);
                        if($result) {
                            $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 加载地图".TF::WHITE.$worldname.TF::GREEN."成功");
                            return true;
                        }
                        $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 加载地图".TF::WHITE.$worldname.TF::YELLOW."失败,原因:".TF::WHITE.self::FAILED_REASON['map.not.exists']);
                        return false;
                    }
                    $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 加载地图".TF::WHITE.$worldname.TF::YELLOW."失败,原因:".TF::WHITE.self::FAILED_REASON['map.exists']);
                    return false;
                } else {
                    if(!is_dir($this->plugin->getServer()->getDataPath()."worlds\{$worldname}")) {
                        $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 加载地图".TF::WHITE.$worldname.TF::GREEN."请求成功");
                        $this->recurse_copy($this->plugin->getDataFolder()."maps/".$worldname."", $this->plugin->getServer()->getDataPath()."worlds/".$worldname."");
                        $this->plugin->getLogger()->notice(TF::YELLOW."地图: ".TF::WHITE.$worldname.TF::YELLOW."复制成功,正在修改nbt内的数据以保证地图名和文件名一致");
                        $this->plugin->getServer()->loadLevel($worldname);
                        $Level = $this->plugin->getServer()->getLevelByName($worldname);
                        $Tag = $Level->getProvider()->getLevelData();
                        $Tag->setString("LevelName", $worldname, true); //强制修改
                        $this->plugin->getLogger()->notice(TF::YELLOW."地图: ".TF::WHITE.$worldname.TF::YELLOW."nbt数据修改成功");
                        $this->plugin->getServer()->unloadLevel($Level);
                        $result = $this->plugin->getServer()->loadLevel($worldname);
                        if($result) {
                            $this->plugin->getLogger()->notice(TF::GREEN."小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::GREEN." 加载地图".TF::WHITE.$worldname.TF::GREEN."成功");
                            return true;
                        }
                        $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 加载地图".TF::WHITE.$worldname.TF::YELLOW."失败,原因:".TF::WHITE.self::FAILED_REASON['map.not.exists']);
                        return false;
                    }
                    $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 加载地图".TF::WHITE.$worldname.TF::YELLOW."失败,原因:".TF::WHITE.self::FAILED_REASON['map.exists']);
                    return false;
                }
            }
            $this->plugin->getLogger()->warning("小游戏 ".TF::WHITE.$this->plugin->getGameNameById($gameid).TF::YELLOW." 加载地图".TF::WHITE.$worldname.TF::YELLOW."失败,原因:".TF::WHITE.self::FAILED_REASON['map.not.exists']);
            return false;
        }
        $this->plugin->getLogger()->warning("游戏id:".TF::WHITE.$gameid.TF::YELLOW."加载地图".TF::WHITE.$worldname.TF::YELLOW."失败,原因:".TF::WHITE.self::FAILED_REASON['GAMEID_NOT_REGISTERED']);
        return false;
    }

    private function recurse_copy($src,$dst) {
        //from http://php.net/manual/en/function.copy.php 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 
}
<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{
    Config, TextFormat as TF
};
use pocketmine\Player;

use yl13\GameCoreAPI\api\API;

class GameCoreAPI extends PluginBase {

    const VERSION = '1.0.0';
    private $gcid;

    private static $instance;

    private $registeredGames = [];
    private $ChatChannel = [];
    private $playerData = [];

    public $api;

    private $Configure = array(
        'chatchannel' => array(
            'enabled' => true,
            'default' => 'lobby',
            'chatFormat' => null
        )
    );

    public function onEnable() {
        $this->getLogger()->notice(TF::YELLOW."GameCoreAPI启动中...");
        $this->initPlugin();
    }

    public function onLoad() {
        self::$instance = $this;
    }

    public function onDisable() {
        $this->getLogger()->warning("GameCoreAPI已关闭");
    }

    private function initPlugin() {
        $this->getLogger()->notice(TF::YELLOW."正在初始化...");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        if(!is_file($this->getDataFolder().'config.yml')) {
            $this->saveDefaultConfig();
        }
        if(!is_dir($this->getDataFolder().'maps')) {
            @mkdir($this->getDataFolder().'maps');
        }
        $this->initConfigure(new Config($this->getDataFolder().'config.yml', Config::YAML));
        $this->api = new API($this, $this->randnum(6));
        $this->getLogger()->notice(TF::GREEN."初始化成功!");
        $this->getLogger()->notice(TF::YELLOW."当前版本:".TF::WHITE.self::VERSION);
    }

    private function initConfigure(Config $config) {
        $chatchannel = $config->get('chatchannel');
        if(!is_bool($chatchannel['enabled'])) {
            $chatchannel['enabled'] = true;   
        }
        if(!is_string($chatchannel['default'])) {
            if(!$chatchannel['default'] == null) {
                $chatchannel['default'] = 'lobby';
            }
        }
        if(!$chatchannel['default'] == null) {
            $this->gcid = $this->randnum(8);
            $this->ChatChannel[$chatchannel['default']] = array(
                'id' => $this->gcid,
                'format' => $chatchannel['chatFormat'],
                'players' => [],
                'mute' => false
            );
            $this->getLogger()->notice(TF::GREEN."已创建默认聊天频道:".TF::WHITE.$chatchannel['default']);
        }
        $this->Configure['chatchannel'] = $chatchannel;
    }

    public static function getInstance() {
        return self::$instance;
    }

    final public function randnum(int $digit) : int {
        $num = null;
        for($i = 0;$i < $digit;$i++) {
            $num .= mt_rand(0, 9);
        }
        return (int)$num;
    }

    final public function initPlayerData(GameCoreAPI $plugin, Player $player) : bool {
        if(!isset($this->playerData[$player->getName()])) {
            $this->playerData[$player->getName()] = array(
                'chatchannel' => null
            );
            return true;
        }
        return false;
    }

    final public function setPlayerData(GameCoreAPI $plugin, Player $player, String $type, $value) : bool {
        if(isset($this->playerData[$player->getName()])) {
            switch($type) {

                default:
                    return false;
                break;

                case 'CHATCHANNEL':
                    $this->playerData[$player->getName()]['chatchannel'] = $value;
                    return true;
            }
        }
    }

    final public function getPlayerData(GameCoreAPI $plugin, Player $player) : ?Array {
        if(isset($this->playerData[$player->getName()])) {
            return $this->playerData[$player->getName()];
        }
        return null;
    }

    final public function removePlayerData(GameCoreAPI $plugin, Player $player) : bool {
        if(isset($this->playerData[$player->getName()])) {
            unset($this->playerData[$player->getName()]);
            return true;
        }
        return false;
    }

    final public function getConfigure(String $value) {
        switch($value) {

            default:
                return false;
            break;

            case 'chatchannel-enabled':
                return $this->Configure['chatchannel']['enabled'];
            break;

            case 'chatchannel-default':
                return $this->Configure['chatchannel']['default'];
        }
    }

    final public function get(GameCoreAPI $plugin, String $type) {
        switch($type) {

            default:
                return false;
            break;

            case 'RGAME':
                return $this->registeredGames;
            break;

            case 'CHATCHANNEL':
                return $this->ChatChannel;
        }
    }

    final public function set(GameCoreAPI $plugin, String $type, $override) {
        switch($type) {

            default:
                return false;
            break;

            case 'RGAME':
                $this->registeredGames = $override;
            break;
            
            case 'CHATCHANNEL':
                $this->ChatChannel = $override;
        }
    }

    final public function getGameNameById(int $gameid) : ?String {
        if(isset($this->registeredGames[$gameid])) {
            return $this->registeredGames[$gameid]['name'];
        }
        if($gameid == $this->gcid) {
            return 'GameCoreAPI';
        }
        return null;
    }
}
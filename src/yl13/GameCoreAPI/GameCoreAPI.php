<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\{
    Config, TextFormat as TF
};
use pocketmine\Player;

class GameCoreAPI extends PluginBase {

    const VERSION = '1.0.1';
    private $gcid;

    private static $instance;

    private $registeredGames = [];
    private $ChatChannel = [];
    private $playerData = [];
    private $playerMoneyData = [];

    public $api;

    private $Configure = array(
        'chatchannel' => array(
            'enabled' => true,
            'default' => 'lobby',
            'chatFormat' => null
        ),
        'economy' => array(
            'enabled' => true,
            'money-max-limit' => 9223372036854775807,
            'auto-save' => true,
            'auto-save-time' => 6000
        )
    );

    public function onEnable() : void {
        $this->getLogger()->notice(TF::YELLOW."GameCoreAPI启动中...");
        $this->initPlugin();
    }

    public function onLoad() : void {
        self::$instance = $this;
    }

    public function onDisable() : void {
        $config = new Config($this->getDataFolder().'money.yml', Config::YAML);
        $config->setAll($this->playerMoneyData);
        $config->save();
        $this->getLogger()->notice(TF::GREEN."金钱数据已保存");
        $this->getLogger()->warning("GameCoreAPI已关闭");
    }

    private function initPlugin() : void {
        $this->getLogger()->notice(TF::YELLOW."正在初始化...");
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        if(!is_file($this->getDataFolder().'config.yml')) {
            $this->saveDefaultConfig();
        }
        if(!is_dir($this->getDataFolder().'maps')) {
            @mkdir($this->getDataFolder().'maps');
        }
        $this->initChatChannel(new Config($this->getDataFolder().'config.yml', Config::YAML));
        $this->initEconomy(new Config($this->getDataFolder().'config.yml', Config::YAML));
        $this->api = new api\API($this);
        $this->getLogger()->notice(TF::GREEN."初始化成功!");
        $this->getLogger()->notice(TF::GREEN."当前版本:".TF::WHITE.self::VERSION);
    }

    /**
     * @param pocketmine\utils\Config $config
     * 
     * @return void
     */
    private function initChatChannel(Config $config) : void {
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

    /**
     * @param pocketmine\utils\Config $config
     * 
     * @return void
     */
    private function initEconomy(Config $config) : void {
        $economy = $config->get('economy');
        if(!is_bool($economy['enabled'])) {
            $economy['enabled'] = true;
        }
        if(!is_int($economy['money-max-limit'])) {
            $economy['money-max-limit'] = PHP_INT_MAX;
        }
        if(!is_bool($economy['auto-save'])) {
            $economy['auto-save'] = true;
        }
        if(!is_int($economy['auto-save-time'])) {
            $economy['auto-save-time'] = 6000;
        }
        $this->Configure['economy'] = $economy;
        if($this->getConfigure('economy', 'enabled') == true) {
            if(!is_file($this->getDataFolder().'money.yml')) {
                new Config($this->getDataFolder().'money.yml', Config::YAML);   
            }
            $config = new Config($this->getDataFolder().'money.yml', Config::YAML);
            $moneydata = $config->getAll();
            $this->playerMoneyData = $config->getAll();
            foreach($moneydata as $key => $value) {
                $this->playerData[$key]['money'] = $value; 
            }
            if($this->getConfigure('economy', 'auto-save') == true) {
                $this->getScheduler()->scheduleRepeatingTask(new AutoSaveTask($this), $this->getConfigure('economy', 'auto-save-time'));
                $this->getLogger()->notice(TF::GREEN."经济系统自动储存金钱已开启");
            }
        }
    }

    /**
     * @return yl13\GameCoreAPI\GameCoreAPI
     */
    public static function getInstance() : GameCoreAPI {
        return self::$instance;
    }

    /**
     * @param int $digit
     * 
     * @return int
     */
    final public function randnum(int $digit) : int {
        $num = null;
        for($i = 0;$i < $digit;$i++) {
            $num .= mt_rand(0, 9);
        }
        return (int)$num;
    }

    /**
     * @param yl13\GameCoreAPI\GameCoreAPI $plugin
     * @param pocketmine\Player $player
     * 
     * @return void
     */
    final public function initPlayerData(GameCoreAPI $plugin, Player $player) : void {
        if(!isset($this->playerData[$player->getName()])) {
            $this->playerData[$player->getName()] = array(
                'chatchannel' => null,
                'money' => 0
            );
        }
        $this->playerData[$player->getName()]['chatchannel'] = null;
    }

    /**
     * @param yl13\GameCoreAPI\GameCoreAPI $plugin
     * @param pocketmine\Player $player
     * 
     * @return bool
     */
    final public function setPlayerData(GameCoreAPI $plugin, Player $player, string $type, $value) : bool {
        if(isset($this->playerData[$player->getName()])) {
            switch($type) {

                default:
                    return false;
                break;

                case 'CHATCHANNEL':
                    $this->playerData[$player->getName()]['chatchannel'] = $value;
                    return true;
                break;

                case 'MONEY':
                    $this->playerData[$player->getName()]['money'] = $value;
                    return true;
            }
        }
    }

    /**
     * @param yl13\GameCoreAPI\GameCoreAPI $plugin
     * @param pocketmine\Player $player
     * 
     * @return array|null
     */
    final public function getPlayerData(GameCoreAPI $plugin, Player $player) : ?array {
        return $this->playerData[$player->getName()] ?? null;
    }

    /**
     * @param yl13\GameCoreAPI\GameCoreAPI $plugin
     * @param pocketmine\Player $player
     * 
     * @return bool
     */
    final public function removePlayerData(GameCoreAPI $plugin, Player $player) : bool {
        if(isset($this->playerData[$player->getName()])) {
            unset($this->playerData[$player->getName()]);
            return true;
        }
        return false;
    }

    /**
     * @param yl13\GameCoreAPI\GameCoreAPI $plugin
     * @param pocketmine\Player $player
     * @param int $digit
     */
    final public function setPlayerMoneyData(GameCoreAPI $plugin, Player $player, int $digit) : void {
        $this->playerMoneyData[$player->getName()] = $digit;
    }

    /**
     * @param yl13\GameCoreAPI\GameCoreAPI $plugin
     * @param pocketmine\Player $player
     * 
     * @return bool|null
     */
    final public function removePlayerMoneyData(GameCoreAPI $plugin, Player $player) : ?bool {
        if(isset($this->playerMoneyData[$player->getName()])) {
            unset($this->playerMoneyData[$player->getName()]);
            return true;
        }
        return null;
    }

    /**
     * @param yl13\GameCoreAPI\GameCoreAPI $plugin
     * @param pocketmine\Player
     * 
     * @description 在这里获取的金钱数据不是最新的，想要直接获取就直接获取玩家数据吧
     * 
     * @return int|null
     */
    final public function getPlayerMoneyData(GameCoreAPI $plugin, Player $player) : ?int {
        return $this->playerMoneyData[$player->getName()] ?? null;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * 
     * @return mixed|null
     */
    final public function getConfigure($key, $value) {
        return $this->Configure[$key][$value] ?? null;
    }

    /**
     * @param yl13\GameCoreAPI\GameCoreAPI $plugin
     * @param string $type
     * 
     * @return array|null
     */
    final public function get(GameCoreAPI $plugin, string $type) : ?array {
        switch($type) {

            default:
                return null;
            break;

            case 'RGAME':
                return $this->registeredGames;
            break;

            case 'CHATCHANNEL':
                return $this->ChatChannel;
        }
    }

    /**
     * @param yl13\GameCoreAPI\GameCoreAPI $plugin
     * @param string $type
     * @param mixed $override
     * 
     * @return void
     */
    final public function set(GameCoreAPI $plugin, string $type, $override) : void {
        switch($type) {

            default:
                return;
            break;

            case 'RGAME':
                $this->registeredGames = $override;
            break;
            
            case 'CHATCHANNEL':
                $this->ChatChannel = $override;
        }
    }

    /**
     * @param int $gameid
     * 
     * @return string|null
     */
    final public function getGameNameById(int $gameid) : ?string {
        if(isset($this->registeredGames[$gameid])) {
            return $this->registeredGames[$gameid]['name'];
        }
        if($gameid == $this->gcid) {
            return 'GameCoreAPI';
        }
        return null;
    }
}
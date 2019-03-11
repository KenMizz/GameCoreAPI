<?php

declare(strict_types=1);

namespace yl13\GameCoreAPI;

use pocketmine\scheduler\Task;
use pocketmine\utils\{
    Config, TextFormat as TF
};

class AutoSaveTask extends Task {

    private $plugin;

    public function __construct(GameCoreAPI $plugin) { $this->plugin = $plugin; }

    public function onRun(int $tick) {
        $this->plugin->getLogger()->notice(TF::YELLOW."玩家金钱数据储存中。。");
        $config = new Config($this->getDataFolder().'money.yml', Config::YAML);
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
            $config->set($player->getName(), $this->plugin->getPlayerMoneyData($this->plugin, $player));
        }
        $config->save();
        $this->plugin->getLogger()->notice(TF::GREEN."玩家金钱数据储存成功！);
    }
}
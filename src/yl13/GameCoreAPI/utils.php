<?php

/**
 *    ____                       ____                    _    ____ ___ 
 * / ___| __ _ _ __ ___   ___ / ___|___  _ __ ___     / \  |  _ \_ _|
 *| |  _ / _` | '_ ` _ \ / _ \ |   / _ \| '__/ _ \   / _ \ | |_) | | 
 *| |_| | (_| | | | | | |  __/ |__| (_) | | |  __/  / ___ \|  __/| | 
 * \____|\__,_|_| |_| |_|\___|\____\___/|_|  \___| /_/   \_\_|  |___|
 * 
 * GameCoreAPI是一个PocketMine的小游戏框架
 * 游乐13制作
 */

namespace yl13\GameCoreAPI;

class utils {

    public static function generateId(int $digit) : int {
        $id = '';
        for($i = 0;$i < $digit;$i++) {
            $id .= mt_rand(0, 9);
        }
        return (int)$id;
    }

    public static function deep_in_array($value, Array $array) : bool {
        //TODO
    }
}
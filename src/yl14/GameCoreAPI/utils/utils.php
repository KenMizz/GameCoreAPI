<?php

namespace yl14\GameCoreAPI\utils;

class utils {

    static public function generateENum(int $digit = 6) : string{
        return substr(sha1(mt_rand()),17, $digit);
    }
}
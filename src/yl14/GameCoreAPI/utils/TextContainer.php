<?php


namespace yl14\GameCoreAPI\utils;

class TextContainer {

    private $string = "";

    public function __construct(string $string) {
        $this->string = $string;
    }

    public function convertColor() : string {
        return str_replace(['BLACK', 'DARK_BLUE', 'DARK_GREEN', 'DARK_AQUA', 'DARK_RED', 'DARK_PURPLE', 'GOLD', 'GRAY', 'DARK_GRAY', 'BLUE', 'GREEN', 'AQUA', 'RED', 'LIGHT_PURPLE', 'YELLOW', 'WHITE', 'OBFUSCATED', 'BOLD', 'STRIKETHROUGH', 'UNDERLINE', 'ITALIC', 'RESET'], 
        ['§0', '§1', '§2', '§3', '§4', '§5', '§6', '§7', '§8', '§9', '§a', '§b', '§c', '§d', '§e', '§f', '§k', '§l', '§m', '§n', '§o', '§r'], $this->string);
    }
}
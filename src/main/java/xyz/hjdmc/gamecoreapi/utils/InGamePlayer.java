package xyz.hjdmc.gamecoreapi.utils;

import cn.nukkit.Player;
import xyz.hjdmc.gamecoreapi.GameCoreAPI;

public class InGamePlayer {

    private final Player player;
    private ChatChannel chatChannel;
    private String InGame = "";
    private int sessionId = 0;

    public InGamePlayer(GameCoreAPI plugin, Player player) {
        this.player = player;
    }

    public Player getPlayer() {
        return this.player;
    }

    public ChatChannel getChatChannel() {
        return this.chatChannel;
    }

    public void setChatChannel(ChatChannel chatChannel) {
        this.chatChannel = chatChannel;
    }

    public void removeChatChannel() {
        this.chatChannel = null;
    }

    public void setInGame(String inGame) {
        this.InGame = inGame;
    }

    public String getInGame() {
        return this.InGame;
    }

    public void setSessionId(int sessionId) {
        this.sessionId = sessionId;
    }

    public int getSessionId() {
        return this.sessionId;
    }
}

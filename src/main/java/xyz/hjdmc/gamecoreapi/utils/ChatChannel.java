package xyz.hjdmc.gamecoreapi.utils;

import cn.nukkit.Player;
import xyz.hjdmc.gamecoreapi.GameCoreAPI;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class ChatChannel {

    private final GameCoreAPI plugin;
    private final String gameid;

    private HashMap<String, Player> players = new HashMap<String, Player>();
    private String format = "";
    private Boolean mute = false;

    public ChatChannel(GameCoreAPI plugin, String gameid) {
        this.plugin = plugin;
        this.gameid = gameid;
    }

    public void addPlayer(Player player) {
        if(!players.containsKey(player.getName())) {
            players.put(player.getName(), player);
            if(player.isOnline()) {
                this.plugin.getInGamePlayer(this.plugin, player).setChatChannel(this);
            }
        }
    }

    public void removePlayer(Player player) {
        if(players.containsKey(player.getName())) {
            players.remove(player.getName());
            if(player.isOnline()) {
                this.plugin.getInGamePlayer(this.plugin, player).removeChatChannel();
            }
        }
    }

    public void removeAllPlayers() {
        for(Map.Entry<String, Player> entry : players.entrySet()) {
            removePlayer(entry.getValue());
        }
    }

    public Player getPlayer(Player player) {
        return players.get(player.getName());
    }

    public Player[] getAllPlayer() {
        ArrayList<Player> playerList = new ArrayList<Player>();
        for(Map.Entry<String, Player> entry : players.entrySet()) {
            playerList.add(entry.getValue());
        }
        Player[] playerArray = new Player[playerList.size()];
        playerArray = playerList.toArray(playerArray);
        return playerArray;
    }

    public String getFormat() {
        return this.format;
    }

    public void setFormat(String format) {
        this.format = format;
    }

    public void setMute(Boolean mute) {
        this.mute = mute;
    }

    public String getGameid() {
        return this.gameid;
    }
}

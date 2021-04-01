package xyz.hjdmc.gamecoreapi;

import cn.nukkit.Player;
import cn.nukkit.plugin.PluginBase;
import cn.nukkit.utils.TextFormat;
import xyz.hjdmc.gamecoreapi.api.API;
import xyz.hjdmc.gamecoreapi.event.EventListener;
import xyz.hjdmc.gamecoreapi.utils.InGamePlayer;

import java.util.HashMap;

public class GameCoreAPI extends PluginBase{

    private static GameCoreAPI instance;

    private HashMap<String, InGamePlayer> inGamePlayers = new HashMap<String, InGamePlayer>();

    private API api;
    @Override
    public void onEnable() {
        this.getLogger().info(TextFormat.GREEN + "GameCoreAPI Enabled! version: " + TextFormat.WHITE + this.getDescription().getVersion());
        this.api = new API(this);
        this.getServer().getPluginManager().registerEvents(new EventListener(this), this);
    }

    @Override
    public void onLoad() {
        instance = this;
    }

    @Override
    public void onDisable() {
        this.getLogger().info(TextFormat.YELLOW + "GameCoreAPI Disabled!");
    }

    public static GameCoreAPI getInstance() {
        return instance;
    }

    public API getApi() {
        return this.api;
    }

    public void addInGamePlayer(GameCoreAPI plugin, InGamePlayer player) {
        if(!inGamePlayers.containsKey(player.getPlayer().getName())) {
            inGamePlayers.put(player.getPlayer().getName(), player);
        }
    }

    public void removeInGamePlayer(GameCoreAPI plugin, Player player) {
        inGamePlayers.remove(player.getPlayer().getName());
    }

    public InGamePlayer getInGamePlayer(GameCoreAPI plugin, Player player) {
        return inGamePlayers.get(player.getName());
    }

    public boolean setPlayerInGame(String id, Player player, String inGame) {
        boolean isGameRegistered = this.getApi().getGameCoreAPI().isGameRegistered(this, id);
        if(isGameRegistered) {
            InGamePlayer inGamePlayer = this.getInGamePlayer(this, player);
            if(inGamePlayer != null) {
                inGamePlayer.setInGame(inGame);
                return true;
            }
            return false;
        }
        return false;
    }

    public String getPlayerInGame(String id, Player player) {
        boolean isGameRegistered = this.getApi().getGameCoreAPI().isGameRegistered(this, id);
        if(isGameRegistered) {
            InGamePlayer inGamePlayer = this.getInGamePlayer(this, player);
            if(inGamePlayer != null) {
                return inGamePlayer.getInGame();
            }
            return "null";
        }
        return "null";
    }

    public void setPlayerSessionId(String id, Player player, int sessionId) {
        boolean isGameRegistered = this.getApi().getGameCoreAPI().isGameRegistered(this, id);
        if(isGameRegistered) {
            InGamePlayer inGamePlayer = this.getInGamePlayer(this, player);
            if(inGamePlayer != null) {
                inGamePlayer.setSessionId(sessionId);
            }
        }
    }

    public int getPlayerSessionId(String id, Player player) {
        boolean isGameRegistered = this.getApi().getGameCoreAPI().isGameRegistered(this, id);
        if(isGameRegistered) {
            InGamePlayer inGamePlayer = this.getInGamePlayer(this, player);
            if(inGamePlayer != null) {
                return inGamePlayer.getSessionId();
            }
            return 0;
        }
        return 0;
    }
}

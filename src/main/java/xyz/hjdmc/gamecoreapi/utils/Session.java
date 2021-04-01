package xyz.hjdmc.gamecoreapi.utils;

import cn.nukkit.Player;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class Session {

    public enum Status {
        WAITING,
        READY,
        STARTED
    };

    private Status status = Status.WAITING;
    private HashMap<String, Player> players = new HashMap<String, Player>();

    private int sessionId = 0;
    private String levelName = "world";
    private double[] waitingLocation = new double[3];
    private double[] playingLocation = new double[3];
    private Map<String, Object> settings = null;

    public Session(int sessionId, String levelName, double[] waitingLocation, double[] playingLocation, Map<String, Object> settings) {
        this.sessionId = sessionId;
        this.levelName = levelName;
        this.waitingLocation = waitingLocation;
        this.playingLocation = playingLocation;
        this.settings = settings;
    }

    public int getSessionId() {
        return this.sessionId;
    }

    public String getLevelName() {
        return this.levelName;
    }

    public double[] getWaitingLocation() {
        return this.waitingLocation;
    }

    public double[] getPlayingLocation() {
        return this.playingLocation;
    }

    public int getMinPlayer() {
        return (int) this.settings.get("minPlayer");
    }

    public int getMaxPlayer() {
        return (int) this.settings.get("maxPlayer");
    }

    public int getGameTime() {
        return (int) this.settings.get("gameTime");
    }

    public int getWinMoney() {
        return (int) this.settings.get("money");
    }

    public Map<String, Object> getSettings() {
        return this.settings;
    }

    public boolean addPlayer(Player player) {
        if(!players.containsKey(player.getName())) {
            players.put(player.getName(), player);
            return true;
        }
        return false;
    }

    public boolean removePlayer(Player player) {
        if(players.containsKey(player.getName())) {
            players.remove(player.getName());
            return true;
        }
        return false;
    }

    public void removeAllPlayers() {
        for(Map.Entry<String, Player> entry : players.entrySet()) {
            removePlayer(entry.getValue());
        }
    }

    public Player getPlayer(Player player) {
        return players.get(player.getName());
    }

    public Player[] getAllPlayers() {
        ArrayList<Player> playerList = new ArrayList<Player>();
        for(Map.Entry<String, Player> entry : players.entrySet()) {
            playerList.add(entry.getValue());
        }
        Player[] playerArrayList = new Player[playerList.size()];
        playerArrayList = playerList.toArray(playerArrayList);
        return playerArrayList;
    }

    public Status getStatus() {
        return this.status;
    }

    public void setStatus(Status status) {
        this.status = status;
    }
}

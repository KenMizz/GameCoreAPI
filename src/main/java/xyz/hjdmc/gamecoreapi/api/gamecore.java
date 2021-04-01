package xyz.hjdmc.gamecoreapi.api;

import xyz.hjdmc.gamecoreapi.GameCoreAPI;

import java.util.HashMap;
import java.util.Map;
import java.util.UUID;

public class gamecore {

    private final GameCoreAPI plugin;

    private HashMap<String, HashMap<String, String>> registerGames = new HashMap<String, HashMap<String, String>>();

    public gamecore(GameCoreAPI plugin) {
        this.plugin = plugin;
    }

    public String registerGame(String name) {
        if(!registerGames.containsKey("name")) {
            HashMap<String, String> inner = new HashMap<String, String>();
            String gameid = UUID.randomUUID().toString();
            inner.put("id", gameid);
            registerGames.put(name, inner);
            return gameid;
        }
        return "0";
    }

    public Boolean isGameRegistered(GameCoreAPI plugin, String id) {
        for(Map.Entry<String, HashMap<String, String>> entry : registerGames.entrySet()) {
            HashMap<String, String> innerMap = entry.getValue();
            if(innerMap.containsValue(id)) {
                return true;
            }
        }
        return false;
    }

    public String getNameById(GameCoreAPI plugin, String id) {
        for(Map.Entry<String, HashMap<String, String>> entry : registerGames.entrySet()) {
            HashMap<String, String> innerMap = entry.getValue();
            if(innerMap.containsValue(id)) {
                return entry.getKey();
            }
        }
        return "unknown";
    }

}

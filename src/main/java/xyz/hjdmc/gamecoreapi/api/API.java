package xyz.hjdmc.gamecoreapi.api;

import xyz.hjdmc.gamecoreapi.GameCoreAPI;

import java.util.HashMap;
import java.util.Map;

public class API {

    private final GameCoreAPI plugin;
    private final gamecore gamecore;
    private final chatchannel chatchannel;
    private final session session;

    public API(GameCoreAPI plugin) {
        this.plugin = plugin;
        this.gamecore = new gamecore(plugin);
        this.chatchannel = new chatchannel(plugin);
        this.session = new session(plugin);
    }

    public gamecore getGameCoreAPI() {
        return this.gamecore;
    }

    public chatchannel getChatChannelAPI() {
        return this.chatchannel;
    }

    public session getSessionAPI() {
        return this.session;
    }
}

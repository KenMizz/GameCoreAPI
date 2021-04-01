package xyz.hjdmc.gamecoreapi.api;

import cn.nukkit.utils.TextFormat;
import xyz.hjdmc.gamecoreapi.GameCoreAPI;
import xyz.hjdmc.gamecoreapi.exception.ChatChannelNotFoundException;
import xyz.hjdmc.gamecoreapi.utils.ChatChannel;

import java.util.ArrayList;
import java.util.HashMap;

public class chatchannel {

    private final GameCoreAPI plugin;

    private HashMap<String, HashMap<String, ChatChannel>> channels = new HashMap<String, HashMap<String, ChatChannel>>();

    public chatchannel(GameCoreAPI plugin) {
        this.plugin = plugin;
    }

    public Boolean create(String id, String name) {
        Boolean isGameRegistered = this.plugin.getApi().getGameCoreAPI().isGameRegistered(this.plugin, id);
        if(isGameRegistered) {
            if(channels.containsKey(id)) {
                HashMap<String, ChatChannel> channelList = channels.get(id);
                if(!channelList.containsKey(name)) {
                    channelList.put(name, new ChatChannel(this.plugin, id));
                    this.plugin.getLogger().debug("[createChannel]All ChatChannel exists: 1" + channels.toString());
                    return true;
                } else {
                    this.plugin.getLogger().debug("[createChannel]All ChatChannel exists: 2" + channels.toString());
                    return false;
                }
            } else {
                HashMap<String, ChatChannel> channel = new HashMap<String, ChatChannel>();
                channel.put(name, new ChatChannel(this.plugin, id));
                channels.put(id, channel);
                this.plugin.getLogger().debug("[createChannel]All ChatChannel exists: 3" + channels.toString());
                return true;
            }
        }
        return false;
    }

    public Boolean remove(String id, String name) {
        Boolean isGameRegistered = this.plugin.getApi().getGameCoreAPI().isGameRegistered(this.plugin, id);
        if(isGameRegistered) {
            if(channels.containsKey(id)) {
                HashMap<String, ChatChannel> channelList = channels.get(id);
                if(channelList.containsKey(name)) {
                    channelList.get(name).removeAllPlayers();
                    channelList.remove(name);
                    this.plugin.getLogger().debug("[removeChannel]All ChatChannel exists: 1" + channels.toString());
                    return true;
                }
                this.plugin.getLogger().debug("[removeChannel]All ChatChannel exists: 2" + channels.toString());
                return false;
            }
            this.plugin.getLogger().debug("[removeChannel]All ChatChannel exists: 3" + channels.toString());
            return false;
        }
        return false;
    }

    public ChatChannel get(String id, String name) throws ChatChannelNotFoundException {
        Boolean isGameRegistered = this.plugin.getApi().getGameCoreAPI().isGameRegistered(this.plugin, id);
        if(isGameRegistered) {
            if(channels.containsKey(id)) {
                HashMap<String, ChatChannel> channelList = channels.get(id);
                if(!(channelList.get(name) == null)) {
                    return channelList.get(name);
                }
                throw new ChatChannelNotFoundException("channel not found: " + name);
            }
            throw new ChatChannelNotFoundException("channel not found: " + name);
        }
        throw new ChatChannelNotFoundException("channel not found: " + name);
    }
}

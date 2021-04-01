package xyz.hjdmc.gamecoreapi.event;

import cn.nukkit.Player;
import cn.nukkit.event.EventHandler;
import cn.nukkit.event.EventPriority;
import cn.nukkit.event.Listener;
import cn.nukkit.event.player.PlayerChatEvent;
import cn.nukkit.event.player.PlayerJoinEvent;
import cn.nukkit.event.player.PlayerQuitEvent;
import xyz.hjdmc.gamecoreapi.GameCoreAPI;
import xyz.hjdmc.gamecoreapi.utils.ChatChannel;
import xyz.hjdmc.gamecoreapi.utils.InGamePlayer;

import java.util.ArrayList;

public class EventListener implements Listener {

    private final GameCoreAPI plugin;

    public EventListener(GameCoreAPI plugin) {
        this.plugin = plugin;
    }

    @EventHandler(priority = EventPriority.NORMAL, ignoreCancelled = false)
    public void onPlayerJoin(PlayerJoinEvent event) {
        this.plugin.addInGamePlayer(this.plugin, new InGamePlayer(this.plugin, event.getPlayer()));
    }

    @EventHandler(priority = EventPriority.NORMAL, ignoreCancelled = false)
    public void onPlayerQuit(PlayerQuitEvent event) {
        this.plugin.removeInGamePlayer(this.plugin, event.getPlayer());
    }

    @EventHandler(priority = EventPriority.NORMAL, ignoreCancelled = false)
    public void onPlayerChat(PlayerChatEvent event) {
        event.setCancelled();
        if(event.getPlayer().isOp()) {
            this.plugin.getServer().broadcastMessage("[" + event.getPlayer().getName() + "] " + event.getMessage());
        }
        ChatChannel chatChannel = this.plugin.getInGamePlayer(this.plugin, event.getPlayer()).getChatChannel();
        if(chatChannel != null) {
            Player[] players = chatChannel.getAllPlayer();
            String format = chatChannel.getFormat();
            if(!format.equals("")) {
                format = format.replace("MESSAGE", event.getMessage());
                format = format.replace("PLAYERNAME", event.getPlayer().getName());
                this.plugin.getServer().broadcastMessage(format, players);
            } else {
                this.plugin.getServer().broadcastMessage(event.getPlayer().getName() + ": " + event.getMessage());
            }
        }
    }
}

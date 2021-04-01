package xyz.hjdmc.gamecoreapi.api;

import xyz.hjdmc.gamecoreapi.GameCoreAPI;
import xyz.hjdmc.gamecoreapi.exception.SessionNotFoundException;
import xyz.hjdmc.gamecoreapi.utils.Session;

import java.util.HashMap;

public class session {

    private final GameCoreAPI plugin;

    private HashMap<String, HashMap<Integer, Session>> sessions = new HashMap<>();

    public session(GameCoreAPI plugin) {
        this.plugin = plugin;
    }

    public boolean addSession(String id, Session session) {
        Boolean isGameRegistered = this.plugin.getApi().getGameCoreAPI().isGameRegistered(this.plugin, id);
        if(isGameRegistered) {
            if(!sessions.containsKey(id)) {
                sessions.put(id, new HashMap<Integer, Session>(){{
                    put(session.getSessionId(), session);
                }});
                this.plugin.getLogger().debug("[addSession]All Session exists 1: " + sessions.toString());
                return true;
            }
            HashMap<Integer, Session> inner = sessions.get(id);
            if(!inner.containsKey(session.getSessionId())) {
                inner.put(session.getSessionId(), session);
                this.plugin.getLogger().debug("[addSession]All Session exists 2: " + sessions.toString());
                return true;
            }
            this.plugin.getLogger().debug("[addSession]All Session exists 3: " + sessions.toString());
            return false;
        }
        return false;
    }

    public boolean removeSession(String id, Session session) {
        Boolean isGameRegistered = this.plugin.getApi().getGameCoreAPI().isGameRegistered(this.plugin, id);
        if(isGameRegistered) {
            if(sessions.containsKey(id)) {
                HashMap<Integer, Session> inner = sessions.get(id);
                if(inner.containsKey(session.getSessionId())) {
                    inner.remove(session.getSessionId());
                    this.plugin.getLogger().debug("[removeSession]All Session exists 1: " + sessions.toString());
                    return true;
                }
                this.plugin.getLogger().debug("[removeSession]All Session exists 2: " + sessions.toString());
                return false;
            }
            this.plugin.getLogger().debug("[removeSession]All Session exists 3: " + sessions.toString());
            return false;
        }
        return false;
    }

    public Session getSession(String id, int sessionId) throws SessionNotFoundException {
        Boolean isGameRegistered = this.plugin.getApi().getGameCoreAPI().isGameRegistered(this.plugin, id);
        if(isGameRegistered) {
            if(sessions.containsKey(id)) {
                HashMap<Integer, Session> inner = sessions.get(id);
                return inner.get(sessionId);
            }
            throw new SessionNotFoundException("Can't find id " + sessionId + " 's Session");
        }
        throw new SessionNotFoundException("Can't find id " + sessionId + " 's Session");
    }
}

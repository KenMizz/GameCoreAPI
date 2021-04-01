package xyz.hjdmc.gamecoreapi.exception;

public class SessionNotFoundException extends Exception {

    public SessionNotFoundException(String errorMessage) {
        super(errorMessage);
    }
}

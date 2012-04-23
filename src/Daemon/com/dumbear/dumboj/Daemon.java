package com.dumbear.dumboj;

import java.net.ServerSocket;
import java.net.Socket;

public class Daemon {
    /**
     * @param args
     */
    public static void main(String[] args) {
        R.logger.info("Daemon start");
        Config.load("config.xml");
        //TODO wipe all the source code cache

        try {
            ServerSocket server = new ServerSocket(Config.listeningPort);
            if (!server.isBound()) {
                R.logger.severe("Bind server socket to port " + Config.listeningPort + " error");
                System.exit(1);
            }
            while (true) {
                Socket client = server.accept();
                Processor processor = new Processor(client);
                Thread thread = new Thread(processor);
                thread.start();
            }
        } catch (Exception e) {
            R.logger.severe("Server error: " + e);
            System.exit(1);
        }
    }
}

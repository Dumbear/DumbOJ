package com.dumbear.dumboj;

import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.net.InetAddress;
import java.net.Socket;

//^_^
public class Processor implements Runnable {
    private Socket client;
    private InputStreamReader reader;
    private OutputStreamWriter writer;
    private StringBuffer command;

    public Processor(Socket client) {
        this.client = client;
    }

    @Override
    public void run() {
        InetAddress ip = client.getInetAddress();
        if (ip == null) {
            return;
        }
        R.logger.info("Connection accept: " + ip);
        //TODO Should have an authorized address pool in case of a Daemon running on another server.

        //Check IP
        if (!ip.isLoopbackAddress()) {
            R.logger.warning("Unauthorized address: " + ip);
            return;
        }

        command = new StringBuffer();
        try {
            reader = new InputStreamReader(client.getInputStream());
            writer = new OutputStreamWriter(client.getOutputStream());

            //Fetch command
            int c;
            while ((c = reader.read()) != '\n') {
                if (c == -1 || command.length() >= Config.requestCommandMaxLength) {
                    R.logger.warning("Unknown or too long command");
                    return;
                }
                command.append((char)c);
            }

            try {
                writer.write("Accepted\n");
                writer.flush();
                reader.close();
                writer.close();
                client.close();
            } catch (Exception e) {
            }
        } catch (IOException e) {
            R.logger.warning("Fetch command failed for IOException: " + e);
            return;
        } catch (Exception e) {
            R.logger.warning("Fetch command failed for Exception: " + e);
            return;
        }

        //Add command
        Request.add(command.toString());
    }
}

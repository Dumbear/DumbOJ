package com.dumbear.dumboj;

import java.io.File;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;

//^_^
public class Config {
    public static int listeningPort;
    public static String key;
    public static String cachePath;
    public static String charset;

    public static int requestThreadsNumber;
    public static int problemAdderThreadsNumber;
    public static int problemJudgerThreadsNumber;

    public static int requestCommandMaxLength;
    public static int requestCapacity;

    public static int problemAdderCapacity;
    public static String addProblemUrl;

    public static int problemJudgerCapacity;
    public static String updateSubmissionUrl;

    public static void load(String filename) {
        DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
        try {
            DocumentBuilder builder = factory.newDocumentBuilder();
            Document document = builder.parse(new File(filename));
            Element root = document.getDocumentElement();

            listeningPort = Integer.parseInt(parseValue(root, "ListeningPort"));
            key = parseValue(root, "Key");
            cachePath = parseValue(root, "CachePath");
            charset = parseValue(root, "Charset");

            requestThreadsNumber = Integer.parseInt(parseValue(root, "RequestThreadsNumber"));
            problemAdderThreadsNumber = Integer.parseInt(parseValue(root, "ProblemAdderThreadsNumber"));
            problemJudgerThreadsNumber = Integer.parseInt(parseValue(root, "ProblemJudgerThreadsNumber"));

            requestCommandMaxLength = Integer.parseInt(parseValue(root, "RequestCommandMaxLength"));
            requestCapacity = Integer.parseInt(parseValue(root, "RequestCapacity"));

            problemAdderCapacity = Integer.parseInt(parseValue(root, "ProblemAdderCapacity"));
            addProblemUrl = parseValue(root, "AddProblemUrl");

            problemJudgerCapacity = Integer.parseInt(parseValue(root, "ProblemJudgerCapacity"));
            updateSubmissionUrl = parseValue(root, "UpdateSubmissionUrl");
        } catch (Exception e) {
            R.logger.severe("Load config " + filename + " error: " + e);
            System.exit(1);
        }
    }

    private static String parseValue(Element element, String name) {
        Element child = (Element)element.getElementsByTagName(name).item(0);
        return child.getAttribute("value");
    }
}

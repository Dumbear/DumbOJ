package com.dumbear.dumboj.submitter;

import java.io.File;
import java.net.URLEncoder;
import java.util.Date;
import java.util.concurrent.BlockingQueue;
import java.util.concurrent.LinkedBlockingQueue;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import com.dumbear.dumboj.R;
import com.dumbear.dumboj.util.Utility;

public class POJSubmitter extends Submitter {
    public static final String SITE = "POJ";
    public static final String DEFAULT_CHARSET = "UTF-8";

    private static BlockingQueue<Integer> accountIds = new LinkedBlockingQueue<Integer>();
    private static Account[] accounts;

    static {
        DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
        try {
            DocumentBuilder builder = factory.newDocumentBuilder();
            Document document = builder.parse(new File(SITE + "Accounts.xml"));
            NodeList nodes = document.getElementsByTagName("Account");
            accounts = new Account[nodes.getLength()];
            for (int i = 0; i < nodes.getLength(); ++i) {
                Element element = (Element)nodes.item(i);
                accounts[i] = new Account(element.getAttribute("username"), element.getAttribute("password"));
            }
            for (int i = 0; i < accounts.length; ++i) {
                accountIds.put(i);
            }
        } catch (Exception e) {
            R.logger.severe("Load " + SITE + " accounts error: " + e);
            System.exit(1);
        }
    }

    public static final String homeUrl = "http://poj.org";
    public static final String loginUrl = "http://poj.org/login";
    public static final String statusUrl = "http://poj.org/status?user_id=";
    public static final String submitUrl = "http://poj.org/submit";
    public static final String additionalUrl = "http://poj.org/showcompileinfo?solution_id=";

    private int accountId;
    private Account account;
    private StringBuffer cookie;

    private void login() throws Exception {
        StringBuffer buffer = new StringBuffer();
        buffer.append("user_id1=");
        buffer.append(URLEncoder.encode(account.username, DEFAULT_CHARSET));
        buffer.append("&password1=");
        buffer.append(URLEncoder.encode(account.password, DEFAULT_CHARSET));
        buffer.append("&B1=");
        buffer.append(URLEncoder.encode("login", DEFAULT_CHARSET));
        buffer.append("&url=");
        buffer.append(URLEncoder.encode("/", DEFAULT_CHARSET));
        try {
            byte[] bytes = buffer.toString().getBytes(DEFAULT_CHARSET);
            for (int i = 3; i > 0; --i) {
                cookie = new StringBuffer();
                Utility.getHtmlSourceByPost(loginUrl, DEFAULT_CHARSET, bytes, cookie);
                if (!checkLogin()) {
                    if (i == 1) {
                        throw new Exception("Cannot login");
                    }
                    continue;
                }
                break;
            }
        } catch (Exception e) {
            R.logger.warning("Login failed: " + e);
            throw new Exception("Login failed");
        }
    }

    private boolean checkLogin() throws Exception {
        String source = Utility.getHtmlSourceByGet(homeUrl, DEFAULT_CHARSET, cookie);
        return source.contains(account.username);
    }

    private int fetchLastId() throws Exception {
        String id = "";
        try {
            for (int i = 3; i > 0; --i) {
                String source = Utility.getHtmlSourceByGet(statusUrl + account.username, DEFAULT_CHARSET, null);
                id = Utility.getMatcherString(source, "<tr align=center><td>(\\d+)", 1);
                if (id.isEmpty() && !source.contains("<title>Problem Status List</title>")) {
                    if (i == 1) {
                        throw new Exception("Cannot fetch last id");
                    }
                    Thread.sleep(4096);
                    continue;
                }
                break;
            }
        } catch (Exception e) {
            R.logger.warning("Fetch last id failed: " + e);
            throw new Exception("Fetch last id failed");
        }
        return id.isEmpty() ? 0 : Integer.parseInt(id);
    }

    private void submit() throws Exception {
        StringBuffer buffer = new StringBuffer();
        buffer.append("problem_id=");
        buffer.append(URLEncoder.encode(submission.problemId, DEFAULT_CHARSET));
        buffer.append("&language=");
        buffer.append(URLEncoder.encode(submission.language, DEFAULT_CHARSET));
        buffer.append("&source=");
        buffer.append(URLEncoder.encode(submission.sourceCode, DEFAULT_CHARSET));
        buffer.append("&submit=");
        buffer.append(URLEncoder.encode("Submit", DEFAULT_CHARSET));
        try {
            byte[] bytes = buffer.toString().getBytes(DEFAULT_CHARSET);
            String source = Utility.getHtmlSourceByPost(submitUrl, DEFAULT_CHARSET, bytes, cookie);
            if (!source.trim().isEmpty()) {
                throw new Exception("Submit rejected");
            }
        } catch (Exception e) {
            R.logger.warning("Submit failed: " + e);
            throw new Exception("Submit failed");
        }
    }

    private void fetchResult(int lastId) throws Exception {
        String regex = "<td>(\\d+)</td>" +
                       "<td>[\\s\\S]*?</td>" +
                       "<td>[\\s\\S]*?</td>" +
                       "<td>[\\s\\S]*?<font[\\s\\S]*?>([\\s\\S]*?)</font>[\\s\\S]*?</td>" +
                       "<td>([\\s\\S]*?)(?:K)??</td>" +
                       "<td>([\\s\\S]*?)(?:MS)??</td>";
        Pattern pattern = Pattern.compile(regex);
        try {
            long now = new Date().getTime();
            while (new Date().getTime() - now < 10 * 60 * 1000) {
                String source = Utility.getHtmlSourceByGet(statusUrl + account.username, DEFAULT_CHARSET, null);
                Matcher matcher = pattern.matcher(source);
                if (matcher.find() && Integer.parseInt(matcher.group(1)) > lastId) {
                    submission.originalId = matcher.group(1);
                    submission.result = matcher.group(2).trim();
                    if (!submission.result.contains("ing")) {
                        if (submission.result.equals("Accepted")) {
                            submission.memory = Integer.parseInt(matcher.group(3));
                            submission.time = Integer.parseInt(matcher.group(4));
                        } else if (submission.result.equals("Compile Error")) {
                            fetchAdditionalInfo();
                        }
                        return;
                    }
                    R.updateSubmission(submission);
                }
                Thread.sleep(2048);
            }
            throw new Exception("Cannot fetch result");
        } catch (Exception e) {
            R.logger.warning("Fetch result failed: " + e);
            throw new Exception("Fetch result failed");
        }
    }

    private void fetchAdditionalInfo() throws Exception {
        try {
            String source = Utility.getHtmlSourceByGet(additionalUrl + submission.originalId, DEFAULT_CHARSET, null);
            submission.additionalInfo = Utility.getMatcherString(source, "<pre>[\\s\\S]*?</pre>", 0);
        } catch (Exception e) {
            R.logger.warning("Fetch additional info failed: " + e);
            throw new Exception("Fetch additional info failed");
        }
    }

    @Override
    public void start() throws Exception {
        accountId = accountIds.take();
        try {
            account = accounts[accountId];
            R.updateSubmission(submission.id, "Sending to " + SITE);
            login();
            int lastId = fetchLastId();
            submit();
            fetchResult(lastId);
            R.updateSubmission(submission);
        } catch (Exception e) {
            R.updateSubmission(submission.id, "DumbJudge Error");
            throw e;
        } finally {
            accountIds.put(accountId);
        }
    }
}

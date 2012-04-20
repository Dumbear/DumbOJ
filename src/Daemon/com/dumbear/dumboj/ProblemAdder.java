package com.dumbear.dumboj;

import java.net.URLEncoder;
import java.util.concurrent.BlockingQueue;
import java.util.concurrent.LinkedBlockingQueue;

import com.dumbear.dumboj.spider.Spider;
import com.dumbear.dumboj.util.Utility;

public class ProblemAdder implements Runnable {
    private static BlockingQueue<String> parameters = new LinkedBlockingQueue<String>(Config.problemAdderCapacity);
    private static ProblemAdder[] adders;

    static {
        adders = new ProblemAdder[Config.problemAdderThreadsNumber];
        for (int i = 0; i < adders.length; ++i) {
            adders[i] = new ProblemAdder(i);
            Thread thread = new Thread(adders[i]);
            thread.start();
        }
    }

    public static void add(String parameter) {
        try {
            parameters.put(parameter);
        } catch (Exception e) {
            R.logger.warning("Add site and id \"" + parameter + "\" failed: " + e);
        }
    }

    private int threadId;

    public ProblemAdder(int threadId) {
        this.threadId = threadId;
    }

    @Override
    public void run() {
        R.logger.info("ProblemAdder thread " + threadId + " started");
        while (true) {
            String site = null;
            String id = null;
            try {
                String[] parameter = parameters.take().split(" ");
                if (parameter.length != 2) {
                    throw new Exception("Unknown site or id");
                }
                site = parameter[0];
                id = parameter[1];
            } catch (Exception e) {
                R.logger.warning("Take site and id failed: " + e);
                continue;
            }
            Spider spider = null;
            try {
                spider = (Spider)Class.forName("com.dumbear.dumboj.spider." + site + "Spider").newInstance();
                spider.start(id);
            } catch (Exception e) {
                R.logger.warning("Fetch problem \"" + site + " " + id + "\" failed: " + e);
                continue;
            }
            try {
                StringBuffer buffer = new StringBuffer();
                buffer.append("key=");
                buffer.append(URLEncoder.encode(Config.key, Config.charset));
                buffer.append("&title=");
                buffer.append(URLEncoder.encode(spider.problem.getTitle(), Config.charset));
                buffer.append("&source=");
                buffer.append(URLEncoder.encode(spider.problem.getSource(), Config.charset));
                buffer.append("&original_url=");
                buffer.append(URLEncoder.encode(spider.problem.getOriginalUrl(), Config.charset));
                buffer.append("&original_site=");
                buffer.append(URLEncoder.encode(spider.problem.getOriginalSite(), Config.charset));
                buffer.append("&original_id=");
                buffer.append(URLEncoder.encode(spider.problem.getOriginalId(), Config.charset));
                buffer.append("&memory_limit=");
                buffer.append(URLEncoder.encode(spider.problem.getMemoryLimit().toString(), Config.charset));
                buffer.append("&time_limit=");
                buffer.append(URLEncoder.encode(spider.problem.getTimeLimit().toString(), Config.charset));
                buffer.append("&user_id=");
                buffer.append(URLEncoder.encode(spider.problemContent.getUserId().toString(), Config.charset));
                buffer.append("&description=");
                buffer.append(URLEncoder.encode(spider.problemContent.getDescription(), Config.charset));
                buffer.append("&input=");
                buffer.append(URLEncoder.encode(spider.problemContent.getInput(), Config.charset));
                buffer.append("&output=");
                buffer.append(URLEncoder.encode(spider.problemContent.getOutput(), Config.charset));
                buffer.append("&sample_input=");
                buffer.append(URLEncoder.encode(spider.problemContent.getSampleInput(), Config.charset));
                buffer.append("&sample_output=");
                buffer.append(URLEncoder.encode(spider.problemContent.getSampleOutput(), Config.charset));
                buffer.append("&hint=");
                buffer.append(URLEncoder.encode(spider.problemContent.getHint(), Config.charset));
                buffer.append("&remark=");
                buffer.append(URLEncoder.encode(spider.problemContent.getRemark(), Config.charset));
                byte[] bytes = buffer.toString().getBytes(Config.charset);
                String source = Utility.getHtmlSourceByPost(Config.addProblemUrl, Config.charset, bytes, null);
                if (!source.equals("Accepted")) {
                    throw new Exception("Add problem rejected");
                }
            } catch (Exception e) {
                R.logger.warning("Add problem \"" + site + " " + id + "\" failed: " + e);
            }
        }
    }
}

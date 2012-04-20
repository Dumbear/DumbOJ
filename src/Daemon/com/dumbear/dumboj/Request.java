package com.dumbear.dumboj;

import java.util.concurrent.BlockingQueue;
import java.util.concurrent.LinkedBlockingQueue;

import com.dumbear.dumboj.submitter.Submission;

//^_^
public class Request implements Runnable {
    private static BlockingQueue<String> commands = new LinkedBlockingQueue<String>(Config.requestCapacity);
    private static Request[] requests;

    static {
        requests = new Request[Config.requestThreadsNumber];
        for (int i = 0; i < requests.length; ++i) {
            requests[i] = new Request(i);
            Thread thread = new Thread(requests[i]);
            thread.start();
        }
    }

    public static void add(String command) {
        try {
            commands.put(command);
            R.logger.info("Add request command successfully: " + command);
        } catch (Exception e) {
            R.logger.warning("Add request command " + command + " failed: " + e);
        }
    }

    private int threadId;

    public Request(int threadId) {
        this.threadId = threadId;
    }

    @Override
    public void run() {
        R.logger.info("Request thread " + threadId + " start");
        while (true) {
            String command = "";
            try {
                command = commands.take();
                R.logger.info("Request " + threadId + " take command successfully: " + command);
            } catch (Exception e) {
                R.logger.warning("Request " + threadId + " take command failed: " + e);
                continue;
            }
            String[] parts = command.split("\t");
            try {
                if (parts.length == 0) {
                    throw new Exception("Unknown command");
                }
                //TODO Add more commands
                if (parts[0].equals("AddProblem")) {
                    if (parts.length != 4) {
                        throw new Exception("Unknown command");
                    }
                    addProblem(parts[1], parts[2], parts[3]);
                } else if (parts[0].equals("JudgeSubmission")) {
                    if (parts.length != 5) {
                        throw new Exception("Unknown command");
                    }
                    judgeSubmission(parts[1], parts[2], parts[3], parts[4]);
                } else {
                    throw new Exception("Unknown command");
                }
            } catch (Exception e) {
                R.logger.warning("Request " + threadId + " executed command " + command + " failed: " + e);
            }
        }
    }

    private void addProblem(String site, String originalIdFrom, String originalIdTo) throws Exception {
        originalIdFrom = originalIdFrom.replaceAll("\\W", "");
        originalIdTo = originalIdTo.replaceAll("\\W", "");
        if (originalIdFrom.isEmpty() && originalIdTo.isEmpty()) {
            throw new Exception("AddProblem command requires at least one original id");
        }
        if (!originalIdFrom.isEmpty()) {
            ProblemAdder.add(site + " " + originalIdFrom);
        }
        if (!originalIdTo.isEmpty() && !originalIdTo.equals(originalIdFrom)) {
            ProblemAdder.add(site + " " + originalIdTo);
        }
        try {
            int from = Integer.parseInt(originalIdFrom), to = Integer.parseInt(originalIdTo);
            if (from > to) {
                int tmp = from;
                from = to;
                to = tmp;
            }
            if (to - from + 1 > 16) {
                throw new Exception("Too many problems to add");
            }
            for (++from; from < to; ++from) {
                ProblemAdder.add(site + " " + Integer.toString(from));
            }
        } catch (NumberFormatException e) {
        }
    }

    private void judgeSubmission(String id, String site, String problem_id, String language) throws Exception {
        Submission submission = new Submission();
        submission.id = Integer.parseInt(id);
        submission.site = site;
        submission.problemId = problem_id;
        submission.language = language;
        ProblemJudger.add(submission);
    }
}

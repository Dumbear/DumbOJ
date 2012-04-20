package com.dumbear.dumboj;

import java.io.File;
import java.io.FileInputStream;
import java.util.concurrent.BlockingQueue;
import java.util.concurrent.LinkedBlockingQueue;

import com.dumbear.dumboj.submitter.Submission;
import com.dumbear.dumboj.submitter.Submitter;
import com.dumbear.dumboj.util.Utility;

//^_^
public class ProblemJudger implements Runnable {
    private static BlockingQueue<Submission> submissions = new LinkedBlockingQueue<Submission>(Config.problemJudgerCapacity);
    private static ProblemJudger[] judgers;

    static {
        judgers = new ProblemJudger[Config.problemJudgerThreadsNumber];
        for (int i = 0; i < judgers.length; ++i) {
            judgers[i] = new ProblemJudger(i);
            Thread thread = new Thread(judgers[i]);
            thread.start();
        }
    }

    public static void add(Submission submission) {
        try {
            submissions.put(submission);
            R.logger.info("Add submission successfully: " + submission.id);
        } catch (Exception e) {
            R.logger.warning("Add submission " + submission.id + " failed: " + e);
        }
    }

    private int threadId;

    public ProblemJudger(int threadId) {
        this.threadId = threadId;
    }

    @Override
    public void run() {
        R.logger.info("ProblemJudger thread " + threadId + " start");
        while (true) {
            Submission submission = null;
            try {
                submission = submissions.take();
                R.logger.info("Judger " + threadId + " take submission successfully: " + submission.id);
            } catch (Exception e) {
                R.logger.warning("Judger " + threadId + " take submission failed: " + e);
                continue;
            }
            Submitter submitter = null;
            try {
                FileInputStream is = new FileInputStream(Config.cachePath + "/source_code_" + submission.id);
                submission.sourceCode = new String(Utility.readToEnd(is), Config.charset);
                is.close();
                submitter = (Submitter)Class.forName("com.dumbear.dumboj.submitter." + submission.site + "Submitter").newInstance();
                submitter.submission = submission;
                submitter.start();
                R.logger.info("Judger " + threadId + " submit problem successfully: " + submission.id);
            } catch (Exception e) {
                R.logger.warning("Judger " + threadId + " submit problem " + submission.id + " failed: " + e);
                continue;
            } finally {
                try {
                    File sourceCodeFile = new File(Config.cachePath + "/source_code_" + submission.id);
                    sourceCodeFile.delete();
                } catch (Exception e) {
                    R.logger.warning("Judger " + threadId + " delete source code file " + submission.id + " failed: " + e);
                }
            }
        }
    }
}

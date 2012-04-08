package com.dumbear.dumboj;

import java.net.URLEncoder;
import java.util.logging.Level;
import java.util.logging.Logger;

import com.dumbear.dumboj.submitter.Submission;
import com.dumbear.dumboj.util.Utility;

//^_^
public class R {
    public static final Logger logger;

    static {
        logger = Logger.getLogger("daemon");
        logger.setLevel(Level.INFO);
    }

    public static void updateSubmission(Submission submission) throws Exception {
        StringBuffer buffer = new StringBuffer();
        buffer.append("id=");
        buffer.append(URLEncoder.encode(submission.id.toString(), Config.charset));
        if (submission.originalId != null) {
            buffer.append("&original_id=");
            buffer.append(URLEncoder.encode(submission.originalId, Config.charset));
        }
        buffer.append("&result=");
        buffer.append(URLEncoder.encode(submission.result, Config.charset));
        if (submission.time != null) {
            buffer.append("&time=");
            buffer.append(URLEncoder.encode(submission.time.toString(), Config.charset));
        }
        if (submission.memory != null) {
            buffer.append("&memory=");
            buffer.append(URLEncoder.encode(submission.memory.toString(), Config.charset));
        }
        if (submission.additionalInfo != null) {
            buffer.append("&additional_info=");
            buffer.append(URLEncoder.encode(submission.additionalInfo.toString(), Config.charset));
        }
        try {
            byte[] bytes = buffer.toString().getBytes(Config.charset);
            String source = Utility.getHtmlSourceByPost(Config.updateSubmissionUrl, Config.charset, bytes, null);
            if (!source.equals("Accepted")) {
                throw new Exception("Update rejected");
            }
        } catch (Exception e) {
            R.logger.warning("Update submission failed: " + e);
            throw new Exception("Update submission failed");
        }
    }

    public static void updateSubmission(Integer id, String result) throws Exception {
        Submission submission = new Submission();
        submission.id = id;
        submission.result = result;
        updateSubmission(submission);
    }
}

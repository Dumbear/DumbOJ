package com.dumbear.dumboj.spider;

import com.dumbear.dumboj.util.Utility;

public class WOJSpider extends Spider {
    public static final String SITE = "WOJ";
    public static final String DEFAULT_CHARSET = "UTF-8";

    @Override
    public void start(String id) throws Exception {
        id = Integer.toString(Integer.parseInt(id));
        String source = Utility.getHtmlSourceByGet("http://acm.whu.edu.cn/land/problem/detail?problem_id=" + id, DEFAULT_CHARSET, null);
        if (source.contains("<div id=\"tt\">Ooooops!</div>")) {
            throw new Exception("Problem not available");
        }
        source = Utility.resolveHtmlLinks(source, "http://acm.whu.edu.cn/land/problem/", "http://acm.whu.edu.cn/");
        problem = new Problem();
        problemContent = new ProblemContent();
        problem.setTitle(Utility.getMatcherString(source, "<div id=\"tt\"> Problem[\\s\\S]*?-([\\s\\S]*?)</div>", 1).trim());
        if (problem.getTitle().isEmpty()) {
            throw new Exception("Problem not available");
        }
        problem.setSource(Utility.getMatcherString(source, "<div class=\"ptt\">Source</div>\\s*<div class=\"ptx\">([\\s\\S]*?)</div>\\s*<br />", 1));
        problem.setOriginalUrl("http://acm.whu.edu.cn/land/problem/detail?problem_id=" + id);
        problem.setOriginalSite(SITE);
        problem.setOriginalId(id);
        problem.setTimeLimit(Integer.parseInt(Utility.getMatcherString(source, "<strong>Time Limit</strong>: (\\d*)MS", 1)));
        problem.setMemoryLimit(Integer.parseInt(Utility.getMatcherString(source, "<strong>Memory Limit</strong>: (\\d*)KB", 1)));
        problemContent.setUserId(1);
        problemContent.setDescription(Utility.getMatcherString(source, "<div class=\"ptt\">Description</div>\\s*<div class=\"ptx\">([\\s\\S]*?)</div>\\s*<div class=\"ptt\">", 1));
        problemContent.setInput(Utility.getMatcherString(source, "<div class=\"ptt\">Input</div>\\s*<div class=\"ptx\">([\\s\\S]*?)</div>\\s*<div class=\"ptt\">", 1));
        problemContent.setOutput(Utility.getMatcherString(source, "<div class=\"ptt\">Output</div>\\s*<div class=\"ptx\">([\\s\\S]*?)</div>\\s*<div class=\"ptt\">", 1));
        problemContent.setSampleInput(Utility.getMatcherString(source, "<div class=\"ptt\">Sample Input</div>\\s*<div class=\"ptx\">([\\s\\S]*?)</div>\\s*<div class=\"ptt\">", 1));
        problemContent.setSampleOutput(Utility.getMatcherString(source, "<div class=\"ptt\">Sample Output</div>\\s*<div class=\"ptx\">([\\s\\S]*?)</div>\\s*<div class=\"ptt\">", 1));
        problemContent.setHint(Utility.getMatcherString(source, "<div class=\"ptt\">Hint</div>\\s*<div class=\"ptx\">([\\s\\S]*?)</div>\\s*<div class=\"ptt\">", 1));
        problemContent.setRemark("Created by " + SITE + "Spider.");
    }
}

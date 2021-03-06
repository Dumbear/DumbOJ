package com.dumbear.dumboj.spider;

import com.dumbear.dumboj.util.Utility;

public class POJSpider extends Spider {
    public static final String SITE = "POJ";
    public static final String DEFAULT_CHARSET = "UTF-8";

    @Override
    public void start(String id) throws Exception {
        id = Integer.toString(Integer.parseInt(id));
        String source = Utility.getHtmlSourceByGet("http://poj.org/problem?id=" + id, DEFAULT_CHARSET, null);
        if (source.contains("<font size=\"4\">Error Occurred</font>")) {
            throw new Exception("Problem not available");
        }
        source = Utility.resolveHtmlLinks(source, "http://poj.org/");
        problem = new Problem();
        problemContent = new ProblemContent();
        problem.setTitle(Utility.getMatcherString(source, "<title>\\d*? -- ([\\s\\S]*?)</title>", 1).trim());
        if (problem.getTitle().isEmpty()) {
            throw new Exception("Problem not available");
        }
        problem.setSource(Utility.getMatcherString(source, "<p class=\"pst\">Source</p>([\\s\\S]*?)</td></tr>", 1));
        problem.setOriginalUrl("http://poj.org/problem?id=" + id);
        problem.setOriginalSite(SITE);
        problem.setOriginalId(id);
        problem.setTimeLimit(Integer.parseInt(Utility.getMatcherString(source, "<td><b>Time Limit:</b> (\\d*?)MS</td>", 1)));
        problem.setMemoryLimit(Integer.parseInt(Utility.getMatcherString(source, "<td><b>Memory Limit:</b> (\\d*?)K</td>", 1)));
        problemContent.setUserId(1);
        problemContent.setDescription(Utility.getMatcherString(source, "<p class=\"pst\">Description</p>([\\s\\S]*?)<p class=\"pst\">", 1));
        problemContent.setInput(Utility.getMatcherString(source, "<p class=\"pst\">Input</p>([\\s\\S]*?)<p class=\"pst\">", 1));
        problemContent.setOutput(Utility.getMatcherString(source, "<p class=\"pst\">Output</p>([\\s\\S]*?)<p class=\"pst\">", 1));
        problemContent.setSampleInput(Utility.getMatcherString(source, "<p class=\"pst\">Sample Input</p>([\\s\\S]*?)<p class=\"pst\">", 1));
        problemContent.setSampleOutput(Utility.getMatcherString(source, "<p class=\"pst\">Sample Output</p>([\\s\\S]*?)<p class=\"pst\">", 1));
        problemContent.setHint(Utility.getMatcherString(source, "<p class=\"pst\">Hint</p>([\\s\\S]*?)<p class=\"pst\">", 1));
        problemContent.setRemark("Created by " + SITE + "Spider.");
    }
}

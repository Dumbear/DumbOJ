package com.dumbear.dumboj.spider;

public class Problem {
    private String title;
    private String source;
    private String originalUrl;
    private String originalSite;
    private String originalId;
    private Integer memoryLimit;
    private Integer timeLimit;

    public String getTitle() {
        return title;
    }
    public void setTitle(String title) {
        this.title = title;
    }
    public String getSource() {
        return source;
    }
    public void setSource(String source) {
        this.source = source;
    }
    public String getOriginalUrl() {
        return originalUrl;
    }
    public void setOriginalUrl(String originalUrl) {
        this.originalUrl = originalUrl;
    }
    public String getOriginalSite() {
        return originalSite;
    }
    public void setOriginalSite(String originalSite) {
        this.originalSite = originalSite;
    }
    public String getOriginalId() {
        return originalId;
    }
    public void setOriginalId(String originalId) {
        this.originalId = originalId;
    }
    public Integer getMemoryLimit() {
        return memoryLimit;
    }
    public void setMemoryLimit(Integer memoryLimit) {
        this.memoryLimit = memoryLimit;
    }
    public Integer getTimeLimit() {
        return timeLimit;
    }
    public void setTimeLimit(Integer timeLimit) {
        this.timeLimit = timeLimit;
    }
}

package com.dumbear.dumboj.spider;

public abstract class Spider {
    public Problem problem;
    public ProblemContent problemContent;

    public abstract void start(String id) throws Exception;
}

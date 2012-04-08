package com.dumbear.dumboj.spider;

public class ProblemContent {
    private Integer userId;
    private String description;
    private String input;
    private String output;
    private String sampleInput;
    private String sampleOutput;
    private String hint;
    private String remark;

    public Integer getUserId() {
        return userId;
    }
    public void setUserId(Integer userId) {
        this.userId = userId;
    }
    public String getDescription() {
        return description;
    }
    public void setDescription(String description) {
        this.description = description;
    }
    public String getInput() {
        return input;
    }
    public void setInput(String input) {
        this.input = input;
    }
    public String getOutput() {
        return output;
    }
    public void setOutput(String output) {
        this.output = output;
    }
    public String getSampleInput() {
        return sampleInput;
    }
    public void setSampleInput(String sampleInput) {
        this.sampleInput = sampleInput;
    }
    public String getSampleOutput() {
        return sampleOutput;
    }
    public void setSampleOutput(String sampleOutput) {
        this.sampleOutput = sampleOutput;
    }
    public String getHint() {
        return hint;
    }
    public void setHint(String hint) {
        this.hint = hint;
    }
    public String getRemark() {
        return remark;
    }
    public void setRemark(String remark) {
        this.remark = remark;
    }
}

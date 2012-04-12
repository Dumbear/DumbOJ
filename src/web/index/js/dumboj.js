function DumbOJ() {
    this.formatTimeSpan = function(seconds) {
        seconds = Math.floor(seconds);
        var s = seconds % 60;
        seconds = (seconds - s) / 60;
        var i = seconds % 60;
        seconds = (seconds - i) / 60;
        var h = seconds % 24;
        seconds = (seconds - h) / 24;
    }
}

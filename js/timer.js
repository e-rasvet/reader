var readyStateCheckInterval = setInterval(function() {
    if (document.readyState === "complete") {
        var timer = setInterval( showDiv, 1000);
        clearInterval(readyStateCheckInterval);
    }
}, 10);

var timeminuse = 0;

function showDiv() {
    timeminuse = timeminuse + 1;
    var timer = totaltime - timeminuse;
    if (timer >= 0) 
        UpdateTimer(timer);
}

function UpdateTimer(Seconds) {
    var Days = Math.floor(Seconds / 86400);
    Seconds -= Days * 86400;

    var Hours = Math.floor(Seconds / 3600);
    Seconds -= Hours * (3600);

    var Minutes = Math.floor(Seconds / 60);
    Seconds -= Minutes * (60);

    var TimeStr = ((Days > 0) ? Days + " days " : "") + ((Hours > 0) ? LeadingZero(Hours) + ":" : "") + LeadingZero(Minutes) + ":" + LeadingZero(Seconds);
    
    document.getElementById("fixededit").innerHTML = TimeStr;
}


function LeadingZero(Time) {
    return (Time < 10) ? "0" + Time : + Time;
}
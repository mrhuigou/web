/**
 * Created by MAC on 2017/5/12
 */
var trace_second = 0;
window.setInterval(function () {
    trace_second ++;
}, 1000);
window.onbeforeunload = function() {
    var dataArr = {
        'user_identity':$.cookie('PHPSESSID'),
        'pf':navigator.platform,
        'ua':navigator.userAgent,
        'language':navigator.language || navigator.browserLanguage || navigator.systemLanguage || navigator.userLanguage || "",
        'screen':(window.screen.width || 0) + "x" + (window.screen.height || 0),
        'color_depth':window.screen.colorDepth || 0,
        'url' : location.href,
        'title':document.title,
        'time' : trace_second,
        'refer' : getReferrer(),
        'time_in' : Date.parse(new Date()),
        'time_out' : Date.parse(new Date()) + (trace_second * 1000)
    };
    $.post("/trace/index",{trace_data:dataArr},function () {});
};
function getReferrer() {
    var referrer = '';
    try {
        referrer = window.top.document.referrer;
    } catch(e) {
        if(window.parent) {
            try {
                referrer = window.parent.document.referrer;
            } catch(e2) {
                referrer = '';
            }
        }
    }
    if(referrer === '') {
        referrer = document.referrer;
    }
    return referrer;
}
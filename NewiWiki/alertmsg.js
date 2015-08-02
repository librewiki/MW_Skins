$('#alertmsg').on('closed.bs.alert', function () {
    var msgcheck = $.cookie("alertcheck");
    if (msgcheck == undefined) {
        $.cookie("alertcheck",'yes', { expires: 1, path: '/', domain: 'librewiki.net', secure: false });
    }
});
$(function(){
    var msgcheck = $.cookie("alertcheck");
    if (msgcheck == undefined) {
        $("div").each(function(i) {
            if ($(this).is("#alertmsg")) {
                $(this).css("display", "block");
            }
        });
    } else {
        $("div").each(function(i) {
            if ($(this).is("#alertmsg")) {
                $(this).css("display", "none");
            }
        });
    }
});

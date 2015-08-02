jQuery(document).ready(function($) {
	$("#toc ul li > a").click(function(){
		if ($(this).attr('href') [0] == '#') {
			var id = $(this).attr('href') + "";
			if(id.indexOf(".") != -1) {
				id = document.getElementById(id.replace("#",""));
			}
			$('html,body').animate({
				scrollTop: ($(id).offset().top - 60 )
			}, 400);
			return false;
		}
	});
	$( window ).scroll( function() {
        if ( $( this ).scrollTop() > 200 ) {
            $('.top_scroll').fadeIn();
        } else {
            $('.top_scroll').fadeOut();
        }
    });
	$('.top_scroll').click( function() {
		$('html, body').animate({ scrollTop : 0 }, 400);
		return false;
	});
	$(".mw-cite-backlink > a").click(function(){
		if ($(this).attr('href') [0] == '#') {
            var id = $(this).attr('href') + "";
            if(id.indexOf(".") != -1) {
                id = document.getElementById(id.replace("#",""));
            }
            $('html,body').animate({
                scrollTop: ($(id).offset().top - 60 )
            }, 400);
            return false;
        }
    });
});

$(function () { $("[data-toggle='reference']").tooltip(); });


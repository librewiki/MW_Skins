$(document).ready(function($) {
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

	var hash = window.location.hash;

	if (hash.indexOf(".") != -1) {
		hash = hash + "";
		hash = document.getElementById(hash.replace("#",""));
	}

	if (hash) {
		console.log($(hash).offset().top);
		$('html, body').animate({ scrollTop : $(hash).offset().top - 60 }, 400);
	}
});


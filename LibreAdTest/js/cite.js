jQuery(document).ready(function($) {
	$("#toc ul li > a").click(function(){
		if ($(this).attr('href') [0] == '#') {
			var id = $(this).attr('href') + "";
			console.log(id);
			if(id.indexOf(".") != -1) {
				id = document.getElementById(id.replace("#",""));
			}
			$('html,body').animate({
				scrollTop: ($(id).offset().top - 60 )
			}, 400);
			return false;
		}
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
	$(".reference > a").click(function(){
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

$(function () { $("[data-toggle='reference']").tooltip({
}); });

/*$(function () { $("[data-toggle='reference']").on('shown.bs.tooltip', function () {
	setTimeout(function () {
		$("[data-toggle='reference']").tooltip('hide');
		}, 8000);
	});
});*/

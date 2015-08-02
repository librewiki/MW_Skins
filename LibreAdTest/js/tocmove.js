$(document).ready(function() {
	var toc_html = $("#toc").html();
	if (toc_html) {
		$("#toc").clone().prependTo("#libre_right_toc");
		$("#libre_right_toc").attr('style', 'margin-top: 10px;');
	}
});

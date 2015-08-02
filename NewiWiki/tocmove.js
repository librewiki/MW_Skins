var toc = document.getElementById('toc');
var toc_holder = document.getElementById('right_toc');
if(toc && toc_holder){
	var toc_clone = toc.cloneNode(true);
	toc_holder.appendChild(toc_clone);
}

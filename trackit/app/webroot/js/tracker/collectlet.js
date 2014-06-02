(function(){

	// the minimum version of jQuery we want
	var v = "1.10.2";

	// check prior inclusion and version
	if (window.jQuery === undefined || window.jQuery.fn.jquery < v) {
		var done = false;
		var script = document.createElement("script");
		script.src = "http://ajax.googleapis.com/ajax/libs/jquery/" + v + "/jquery.min.js";
		script.onload = script.onreadystatechange = function(){
			if (!done && (!this.readyState || this.readyState == "loaded" || this.readyState == "complete")) {
				done = true;
				initMyBookmarklet();
			}
		};
		document.getElementsByTagName("head")[0].appendChild(script);
	} else {
		initMyBookmarklet();
	}
	
	function initMyBookmarklet() {
		(window.myBookmarklet = function() {
			$collecturi = "http://www.couponistalking.com/socials/collectlet/?uri="+encodeURIComponent(window.top.location.href);
			console.log($collecturi);
			$cit_r = $('iframe#couponistalking_remote');
			if(!$cit_r||0==$cit_r.length){
				$(document).append(""+
				"<div id='couponistalking_iframe_veil' style='z-index: 900;display:none;position:fixed;width:100%;height:100%;top:0px;left:0px;background-color:white;background-color:rgba(255,255,255,0.3);border:none;margin:none;padding:none;'>"+
				" <iframe id='couponistalking_remote' width='600' height='600' marginheight='0' marginwidth='0' scrolling='auto' frameBorder='0' allowTransparency='true' src='"+$collecturi+"' style='position:fixed;width:100%;height:100%;top:0px;left:0px;'></iframe>"+
				"</div>");
				$cit_r = $('iframe#couponistalking_remote');
				$("#couponistalking_iframe_veil").fadeIn(750);
			}else{
				$("#couponistalking_iframe_veil").click(function(event){
				$("#couponistalking_iframe_veil").fadeOut(750);
				$("#couponistalking_iframe_veil iframe").slideUp(500);
				setTimeout("$('#couponistalking_iframe_veil').remove()", 750);
			}
			/*
			$("#couponistalking_iframe_veil").click(function(event){
				$("#couponistalking_iframe_veil").fadeOut(750);
				$("#couponistalking_iframe_veil iframe").slideUp(500);
				setTimeout("$('#couponistalking_iframe_veil').remove()", 750);
				*/
			});
			
			// your JavaScript code goes here!
		})();
	}

})();
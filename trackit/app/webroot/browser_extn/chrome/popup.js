(function(){

	// the minimum version of jQuery we want
	var v = "1.10.2";

	// check prior inclusion and version
	if (false && (window.jQuery === undefined || window.jQuery.fn.jquery < v)) {
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
		window.onload = initMyBookmarklet;//();
	}
	
	function initMyBookmarklet() {
		(window.myBookmarklet = function() {
			
		 chrome.tabs.query({'active': true, 'lastFocusedWindow': true}, function (tabs) {
			var url = tabs[0].url;
			var title = tabs[0].title;
			var $collecturi = "http://alpha.couponistalking.com/socials/collectlet/?u="+encodeURIComponent(url)+"&t="+encodeURIComponent(title);
			//var $collecturi = "http://localhost/trackit/socials/collectlet/?u="+encodeURIComponent(url)+"&t="+encodeURIComponent(title);
			//console.log($collecturi);
			$cit_r = $('iframe#couponistalking_remote');
			//console.log($cit_r);
			//console.log($cit_r.length);
			if(!$cit_r||0==$cit_r.length){
				$(document).find('body').append(""+
				"<div id='couponistalking_iframe_veil' style='z-index: 900;display:block;position:fixed;width:100%;height:100%;top:0px;left:0px;background-color:white;background-color:rgba(255,255,255,0.3);border:none;margin:none;padding:none;'>"+
				" <iframe id='couponistalking_remote' width='600' height='600' marginheight='0' marginwidth='0' scrolling='auto' frameBorder='0' allowTransparency='true' src=\""+$collecturi+"\" style='position:fixed;width:100%;height:100%;top:0px;left:0px;'></iframe>"+
				"</div>");
				$cit_r = $('iframe#couponistalking_remote');
				$(document).find('body').find("#couponistalking_iframe_veil").fadeIn(750);
			}else{
				$("#couponistalking_iframe_veil").click(function(event){
				$("#couponistalking_iframe_veil").fadeOut(750);
				$("#couponistalking_iframe_veil iframe").slideUp(500);
				setTimeout("$('#couponistalking_iframe_veil').remove()", 750);
				});
			}
		 });	
			
			// your JavaScript code goes here!
		})();
	}

})();
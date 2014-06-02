// call with your url (with parameters) 
// 2nd param is your callback function (which will be passed the json DATA back)

function crossDomainGetAjax (url, successCallback) {
var $ecbp = true; //enable cdh bypass
var $ujsonp = true; // use jsonp

if (!$ecbp || !is_ie()){$.ajax({url: url,type:'GET',success:function(data){successCallback(data);}});}
else if ($ujsonp){if (url.indexOf('?') > 0){ url = url + "&callback=?"; }else {url = url + "?callback=?";}
	$.getJSON(url, function(response){successCallback(response);});
}
// IE8 & 9 only Cross domain JSON GET request
else if ('XDomainRequest' in window && window.XDomainRequest !== null) {
	var xdr = new XDomainRequest(); // Use Microsoft XDR
	xdr.open('get', url);
	xdr.onload=function(){var dom = new ActiveXObject('Microsoft.XMLDOM'),
		JSON=$.parseJSON(xdr.responseText);dom.async = false;
		if (JSON == null || typeof (JSON) == 'undefined') {JSON = $.parseJSON(data.firstChild.textContent);}
		successCallback(JSON);
	};
	xdr.onerror=function(){_result = false;};xdr.send();
}
// IE7 n lower can't do cross domain
else if (navigator.userAgent.indexOf('MSIE') != -1 && parseInt(navigator.userAgent.match(/MSIE ([\d.]+)/)[1], 10) < 8){return false;}
// Do normal AJAX for everything else
else{ $.ajax({url: url,type: 'GET',success: function(data){successCallback(data);}
	//cache: false, dataType: 'json', async: false, // must be set to false
	});
}

}
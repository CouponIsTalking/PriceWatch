function extnInstallPrompt(){
	this.isExtnInstalled = function(){
		var $i = $("#is-cgdgohngjjknfpckhoclbcimpmebjcdf-extn-installed");
		if((undefined != $i) && $i.length!=0){return true;}return false;
	};
	this.installChromeExtn=function(){chrome.webstore.install();};
	this.showAddButtonDiv=function($p){
		var $noshow_if_installed = $p['noshow_if_installed'];
		var $is_installed = this.isExtnInstalled();
		if($noshow_if_installed && $is_installed){return;}
		$.ajax({
			type:'GET',url:$S_N+"socials/add_collectit_btn/1",
			success:function(data){var $d=IsJsonStr(data);$("addExtnBtnPDiv").html($d);},
			error:function(data){console && console.log && console.log("error showing collect it button.")}
		})
	}
}
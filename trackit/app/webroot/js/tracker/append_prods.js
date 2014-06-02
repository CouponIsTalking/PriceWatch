function checkAndAttachMore($am_selector) {
    
	if($(window).scrollTop() == $(document).height() - $(window).height()){       
	   if(!attachMoreOnHome.set_up){setupAttachMoreOnHome($am_selector);}
	   attachMoreOnHome();
    }
};

function setupAttachMoreOnHome($am_selector){
	attachMoreOnHome.am_selector = $am_selector;
	attachMoreOnHome.pub = 0;
	attachMoreOnHome.set_up = 1;
}

function attachMoreOnHome(){
	
	if (attachMoreOnHome.nomore && (attachMoreOnHome.nomore == 1)){ return; }
	
	$am_selector = attachMoreOnHome.am_selector;
	var $pub = attachMoreOnHome.pub;
	
	formLoaderIcon(1,1,0);
	$.ajax({
		type:'POST',
		data:{forhmpg:1,type:1,pub:$pub,pua:0,adb:0,ada:0,cid:0,lmt:0,mhi:1},
		url:$S_N + "collections/ajax_get_prod_list",
		success:function(data){ $d = IsJsonString(data);
			var m = 0;
			if($d&&$d['s']){
				if(1==$d['new'] || '1'==$d['new']){
					$prodlist = $d['prodlist'];
					$($am_selector).append($prodlist);
					attachMoreOnHome.pub = $d['nk'];
				}else{
					//pass
					m = "No more items.";
					attachMoreOnHome.nomore = 1;
				}
			}else{
				m = "A network error occured.";
				attachMoreOnHome.nomore = 1;
			}
			if(m){
				formLoaderIcon(1,0,m);
			}else{
				formLoaderIcon(0,0,0);
			}
		},
		error:function(data){
			attachMoreOnHome.nomore = 1;
			formLoaderIcon(1,0,"A network error occured.");
		}
	});
}

function formLoaderIcon($s,$si,$m){
	var $lmp=$(".loading_more_pics");
	var $lmpd=$lmp.find('div');
	var $lmpi=$lmp.find('img');
	
	if (!$s){
		$lmp.fadeOut('fast', 0,
		  function(){$lmpd.text("");$lmpd.hide();$lmpi.hide();}
		);
	}else if($si){
		$lmpd.text("");$lmpd.hide();$lmpi.show();
		$lmp.fadeTo('fast', 1);
	}
	else if($m){
		$lmpd.text($m);$lmpd.show();$lmpi.hide();
		$lmp.fadeTo('fast', 1);
	}
}
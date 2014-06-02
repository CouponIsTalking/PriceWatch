function get_extra_perms(ptoget){var $p=[];$p['params']=[];$p['params']["permissions"]=ptoget;$p['params']['get_all_perms']=true;
$p['scn']=0;$p['sp']=0;$p['ecn']=0;$p['ep']=0;fb_get_perms_cb_arg($p);
}

function fb_get_perms_cb_arg($p){scn=$p['scn'];sp=$p['sp'];ecn=$p['ecn'];ep=$p['ep'];permissions=$p["params"]["permissions"];
FB.login(function(response){},{scope: permissions});	
}
function test_vid_post(){FB.ui({method: 'feed',link: 'http://www.youtube.com/watch?v=7GbeHQdN1hM',caption: 'Senorita from JMND',},function(response){});}

function vidptgdc($p){$fourl = $p['params'][0];$fot = $p['params'][1];
scn = $p['scn'];sp = $p['sp'];ecn = $p['ecn'];ep = $p['ep'];nlcn = $p['nl_call_name'];nlcp = $p['nl_call_param'];
FB.ui({method: 'feed',link: $fourl,picture:$fot},function(cpr){if(!cpr||cpr.error){}else{if(scn){sc=window[scn];sp['resp']=cpr;sc(sp);}} });
}
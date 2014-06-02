function load_and_comp_show_ocrs($p){
$oc = $p['oc'];$comp = $p['comp'];$t = $p['this'];
$.ajax({type:"GET",	url:$S_N+"oc_responses/campaign_promotions/"+oc+"/"+comp,
 success : function(data) {
  var $d = IsJsonString(data);				
  if ($d && (0 == $d['success'])){s_s_m($d['msg'], 0, 0);}
  else {$html = "<div class='_occomp_"+$oc+"_"+$comp+"'>"+$d+"</div>"; $t.append($($html));}
 },
 error : function(data) { s_e_m("Hmmm... oops, something didn\'t go right, but we are here to help you. Try again, if it persists, drop us an email.", 0, 0);}
});
	
}
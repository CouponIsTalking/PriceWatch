<?php
//parameters
// $products
// $user_votes
// $logged_in_user_id
?>

<?php
echo $this->Html->css('pchart');
echo $this->Html->script('custom/manage_collection');
echo $this->Html->script('pdisp/pchart');
echo $this->Html->script('underscore/underscore-min');
echo $this->Html->script('charts/d3.v3');

?>
<style type='text/css'>
* .collection_name {margin: 10px;padding: 5px;background: rgb(7, 7, 7);color: white;
font-family: Helvetica sans-serif;font-size: 18px;cursor:pointer;
}
* .collection_name:hover{ 
color: rgb(7,7,7); background:rgb(220,220,220);
-webkit-transition: background-color 300ms linear;
-moz-transition: background-color 300ms linear;
-o-transition: background-color 300ms linear;
-ms-transition: background-color 300ms linear;
transition: background-color 300ms linear;
}

*.selected_collection_name{margin: 10px;padding: 5px;background:rgb(170,70,70);color: white;
font-family: Helvetica sans-serif;font-size: 18px;cursor:pointer;	
}

* ._like_q_tag{
padding: 2px 2px 2px 2px;position: relative;top: 30px;font-size: 12px;width: auto;margin: 1px;float: right;
background-color: transparent;color: rgba(68, 46, 46, 0.91);font-weight: bold;cursor:pointer;border-bottom: 2px dotted gray;
transition-duration: 3s;-webkit-transition-duration: 3s;
}
* ._like_q_tag:hover{border-bottom: 2px solid gray;}

* ._like_str{
font-size:12px;margin:1px;float:right;position:relative;
}

</style>

<script type='text/javascript'>
function __call_trackit_frame($prodid){
	params = [];
	params['script'] = 'tracker/trackprod';
	params['call_to_load'] = 'track_product_by_id';
	params['scn'] = 'track_product_by_id';
	params['scp'] = $prodid;
	params['ecn'] = 's_s_m';
	params['ecp'] = "A network error occured.";
	
	script_load_and_call(params);
}

function __call_vote_frame($btn,$prodid){
	$ve=$("._p"+$prodid+"_prod_votes");
	$lq=$ve.find('.like').val();$wq=$ve.find('.want').val();$oq=$ve.find('.own').val();
	params = [];
	params['script'] = 'tracker/voteprod';
	params['call_to_load'] = 'vote_product_by_id';
	params['scn'] = 'vote_product_by_id';
	params['scp'] = {'pid':$prodid,'lq':$lq,'wq':$wq,'oq':$oq};
	params['ecn'] = 's_s_m';
	params['ecp'] = "A network error occured.";
	
	$t=$btn.closest('.collection_item').offset().top;
	$st=$(window).scrollTop();
	if($t<$st+50){
		$('body, html').animate({scrollTop:$t-50},1000);
		$t=$st+50;
	}else{	
		//$t=$t-$st;
	}
	$l=$btn.closest('.collection_item').offset().left;
	$('._prodvote_frame').show();
	$('._prodvote_frame').find("#prodvote").offset({top:$t,left:$l});
	$('#prodvote_msg').text("LOADING...");
	script_load_and_call(params);
}

function _call_draw_pchart($this){
	$prodid=$this.parents('.collection_item').find('._pid').text().trim();
	draw_price_chart(parseInt($prodid));
}
</script>

<?php

$element_out = $this->element('product/prod_list_php', 
	array('products' => $products, 
		'prod_votes' => $prod_votes,
		'loggedin_user_id'=>$preset_var_logged_in_user_id, 
		'edit_options' => false,
		'track_options' => true,
		'like_want_own_options'=>true
	));

echo $element_out;

?>

<div class='graph_container' style="z-index:5">
<div id='chart'></div>
<div class='price_chart_close' onclick='clear_price_chart();'>X</div>
</div>
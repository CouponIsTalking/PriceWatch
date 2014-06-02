<?php
echo $this->Html->script('tracker/append_prods');
?>

<style type='text/css'>
* ._get_coupon_btn_cls{
	color:rgba(0,0,0,0.75);
	border-bottom:2px solid transparent;
	cursor:pointer;
}
* ._get_coupon_btn_cls:hover{
	border-bottom:2px solid rgba(0,0,0,0.5);
}

* ._main_box{
	margin: 1% 1%;
	padding-top: 40px;
	-box-shadow:0 0px 10px 10px rgba(170, 170, 170, 0.57);
	-webkit-box-shadow:0 0px 10px 10px rgba(170, 170, 170, 0.57);
	-moz-box-shadow:0 0px 10px 10px rgba(170, 170, 170, 0.57);
	border-radius: 10px;
	width:auto;
	float:left;
}
</style>

<script type='text/javascript'>
function RunOnLoad()
{
	//$('#content').css('background', '#A28989');
	<?php
	if (!empty($layout_var) && 'popup' == $layout_var)
	{   echo "
		var sl = $('.stack_info_running_campaign').offset().top;
		$('body').animate({ scrollTop: sl}, 300, function(){});
		";
	}
	?>
	
	$(window).scroll(function (){checkAndAttachMore('._prod_list_div'); });
}
</script>

<?php
echo $this->Html->script('custom/update_resps');
?>

<div class='_main_box'>
<?php
$element_out = $this->element('product/prod_list', 
	array('products' => $products, 
		'prod_votes' => $prod_votes,
		'loggedin_user_id'=>$preset_var_logged_in_user_id, 
		'edit_options' => false,
		'track_options' => true,
		'like_want_own_options'=>true
	));
	
echo "<div class='_prod_list_div' style='max-width:90%;margin:auto;'>";
echo $element_out;
echo "</div>";

$loading_img = SITE_NAME ."img/loading_async_big.gif";
$white_fabric = SITE_NAME . "img/white_fabric.jpg";

echo "<div class='loading_more_pics' style=\"display:none;text-align:center;text-transform:uppercase;font-size:16px;font-family:Helvetica sans-serif;margin:auto;position:fixed;bottom:100px;z-index:100;\">
<div style=\"color:black;border:2px solid #333;border-left:none;border-right:none;min-height:30px;background-image:url('{$white_fabric}');background-repeat:repeat;padding:5px 2px 5px 2px;margin:auto;text-align:center;\">
Loading more pics ...
</div>
<img style='width:50px;height:50px;' src=\"{$loading_img}\"></img>
</div>";

//$bgtex3_image = SITE_NAME.'img/bgtex3.jpg';
echo "<div class='_tracker_frame' style=\"display:none;position:fixed;width:100%;height:100%;top:0px;left:0px;background-color:rgba(0,0,0,0.6);\">";
echo $this->element('product/trackprod_frame', array(
	'loggedin_user_id' => $preset_var_logged_in_user_id, 
	'list_supported_stores' => false,
	'show_close_button' => true
	));
echo "</div>";

//echo "</div>";

echo "<div class='_prodvote_frame' onclick=\"$(this).find('._prodvote_popup_x').click();\" style=\"display:none;position:fixed;width:100%;height:100%;top:0px;left:0px;background-color:transparent\">";
echo $this->element('product/voteprod_frame', array(
	'loggedin_user_id' => $preset_var_logged_in_user_id,
	'show_close_button' => true
	));
echo "</div>";
?>

</div>
<script type="text/javascript">

function RunOnLoad(){
	//$("#trackit1234567890A").find("#form1").css('display','block');
	var $title = "<?php echo addslashes($title);?>";
	var $prodlink = "<?php echo addslashes($prodlink);?>";
	$("#trackit1234567890title").val($title);
	$("#trackit1234567890pageurihidden").val($prodlink);
	//$("#trackit1234567890email").val($uemail);
	$("#trackit1234567890email").prop('disabled', true);
	$("#form2").show();
	$('._tracker_frame').find('#msg').text("");
}

</script>


<div style='position:fixed;background-image:none !important;background-color:transparent !important;background:transparent !important;width:100%;height:100%;top:0px;left:0px;'>

<div style='clear:both;'></div>

<?php

$bgtex_image = SITE_NAME.'img/bgtex3.jpg';
echo "<div class='_tracker_frame' style=\"position:relative;padding-bottom:50px;width:100%;height:100%;top:0px;left:0px;\">";

echo $this->element('product/trackprod_frame', array(
	'loggedin_user_id' => $preset_var_logged_in_user_id, 
	'list_supported_stores' => false,
	'show_close_button' => false
	));

?>
<div style='clear:both;'></div>
</div>
</div>
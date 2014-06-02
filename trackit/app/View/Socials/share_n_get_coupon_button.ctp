<?php
//echo $this->Html->css('remote_coupon_btn');
?>
<!--img onclick="etu = encodeURIComponent(window.top.location.href); url = 'http://alpha.usemenot.com/trackit/contents/getcoupon_button_click?&id={$unix_timestamp_and_id;}&url='+etu;
	window.open(url, 'Coupons via usemenot.com','_blank','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=300,left = 10,top = 10');" src="http://alpha.usemenot.com/trackit/img/get-coupons.png" style='width:60px;height:19px; display:block; cursor:pointer; padding:0px; margin: 0px; border:0px;' onmouseover="this.src='http://alpha.usemenot.com/trackit/img/get-coupons-hover.png'" onmouseout="this.src='http://alpha.usemenot.com/trackit/img/get-coupons.png'">
</img-->

<script type='text/javascript'>

function popitup(window_name) {
	var url = "<?php echo SITE_NAME; ?>contents/getcoupon_button_click";///<?php echo $unix_timestamp_and_id; ?>/";
    var etu = ''; //encodeURIComponent(window.top.location.href);
	url = url + "?&id=<?php echo $unix_timestamp_and_id; ?>&url="+etu
	//newwindow=window.open(url,window_name,'height=300,width=600');
	newwindow = window.open(url,'_blank','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=300,left=10,top=10');
	
	/*
	if (-1 != navigator.userAgent.toLowerCase().indexOf('msie'))
	{
		newwindow = window.open(url,'_blank','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=300,left=10,top=10');
	}
	else
	{
		newwindow=window.open(url,'_blank','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1');
	}
	*/
	
    if (window.focus) {newwindow.focus()}
       return false;
}

	
</script>

<style type='text/css'>

</style>

<?php
/*
echo "
<div class='get_coupon_button' onclick=popitup('{$unix_timestamp_and_id}')>Coupons</div>
";
*/
?>

<?php
$background_img = SITE_NAME . "img/get-coupons.png";
$hover_background_img = SITE_NAME . "img/get-coupons-hover.png";
$onmouseover = "document.getElementById('regular_img').style.display = 'none'; document.getElementById('hover_img').style.display = 'block'; ";
$onmouseout = "document.getElementById('regular_img').style.display = 'block'; document.getElementById('hover_img').style.display = 'none'; ";
$window_title = 'Coupons via '. GENERIC_APPNAME; //$unix_timestamp_and_id;

echo "
<div onclick=\"popitup('{$window_title}')\">
	<a style=\"width:60px;height:19px; display:block; cursor:pointer;\" onmouseover=\"{$onmouseover}\" onmouseout=\"{$onmouseout}\">
		<img id='regular_img' style='display:block' src=\"{$background_img}\">
		</img>
		<img id='hover_img' style='display:none;' src=\"{$hover_background_img}\">
		</img>
	</a>
</div>
";
?>
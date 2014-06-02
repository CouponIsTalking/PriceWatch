
<script type='text/javascript'>

function popitup(window_name) {
	var url = "<?php echo SITE_NAME; ?>open_campaigns/running_campaigns?c=<?php echo $cid; ?>&layout=popup";
    var etu = ''; //encodeURIComponent(window.top.location.href);
	newwindow = window.open(url,'_blank','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=620,height=400,left=10,top=10');
	
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

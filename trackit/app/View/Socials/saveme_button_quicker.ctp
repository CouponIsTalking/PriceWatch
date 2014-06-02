<script src="<?php echo SITE_NAME; ?>js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="<?php echo SITE_NAME; ?>js/jquery-ui.js" type="text/javascript"></script>
<script type="text/javascript">
function trackit1234567890showiframe($this)
{
	$a = $this.parents("#trackit1234567890A");
	$iframe_div = $('#trackit1234567890iframe');
	if ($iframe_div.length == 0)
	{	
		$top_of_iframe = $a.offset().top + $a.height();
		$left_of_iframe = Math.max($a.offset().left - 500, 10);
		$body = $("body");
		$trackit_iframe_url = "<?php echo SITE_NAME; ?>socials/trackit_mainpage_quicker";
		$iframe_div = $("<div id='trackit1234567890iframe' style=\"opacity:0; display:block; width:550px; position:absolute; left:"+$left_of_iframe+";top:"+$top_of_iframe+"; z-index:1000;\"> <iframe src=\""+$trackit_iframe_url+"\"id='trackit1234567890iframe2' frameborder='0' width='550' style='width:550px; height:300px; max-height:400px;'></iframe></div>");
		$body.append($iframe_div);
		$iframe_div.fadeTo('slow', 1.0);
		//$iframe = $a.find('iframe');
		//$iframe.attr('src', $trackit_iframe_url);
	}
	else
	{
		$iframe_div.fadeTo('slow', 0, function(){$(this).remove();});
	}
	
}
</script>

<script type="text/javascript">
function make_button_draggable()
{

	$( "#trackit1234567890A" ).draggable();
} 
 
 
$( document ).ready(function() {
		make_button_draggable();
	});

</script>

<a id="trackit1234567890A">
<img onclick="trackit1234567890showiframe( $(this) );" alt="track this item" src="<?php echo SITE_NAME; ?>img/saveme_button.png" style="height:15px; cursor:pointer;"></img>
</a>
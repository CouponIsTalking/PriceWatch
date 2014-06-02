<?php
$SITE_NAME = "http://www.savethisitem.com/trackit/";
?>

<script src="<?php echo $SITE_NAME; ?>js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
function trackit1234567890($this)
{
	$a = $this.parents("#trackit1234567890A");
	$form = $a.find('form');
	$email = $form.find("#trackit1234567890email").text()
	$price = $form.find("#trackit1234567890price").text()
	$TRACKER_SITE_NAME = $SITE_NAME; //"http://localhost/trackit/";
	$.ajax({
		type:"POST",
		data:{url: window.location.pathname, email: $email, price: $price},
		url: $TRACKER_SITE_NAME  + "trackers/add",
		success : function(data) {
			if (data == 1)
			{
				console.log("traking");
				ToggleTrackitform($a);
			}
			else
			{
				console.log("couldnt track");
			}

		},
		error : function() {
			console.log("call failed");
			ToggleTrackitform($form);
		}
	});
}
 
function ToggleTrackitform($this)
{
	$a = $this.parents("#trackit1234567890A");
	$form = $a.find('form');
	if($form.css('display') == 'none')
	{
		$form.css('z-index', 1000);
		$form.css('opacity',0);
		$form.css('display','block');
		$form.fadeTo('slow', 1.0);			
	}
	else
	{
		$form.fadeTo('slow', 0, 
					function()
						{
							$(this).css('display', 'none');
						}
				);
	}
}

</script>
<style>
#trackit1234567890A form
{
    background-color: #333333;
    border: 1px solid #FFFFFF;
    box-shadow: 0 1px 10px 0 rgba(0, 0, 0, 0.33);
    color: #FFFFFF;
    font-family: Arial,'Liberation Sans','DejaVu Sans',sans-serif;
    font-size: 16px;
    height: auto;
    margin: 10px 10px 5px;
    opacity: 0.95;
    padding: 15px;
    position: relative;
    text-align: center;
    width: 450px;
}
#trackit1234567890A track_this_item_button {
    background-color: #333333;
    border: 1px solid #000000 !important;
    border-radius: 3px 3px 3px 3px;
    box-shadow: 0 2px 1px rgba(0, 0, 0, 0.3), 0 1px 0 rgba(255, 255, 255, 0.4) inset;
    color: #FFFFFF;
    cursor: pointer;
    margin: 5px;
    padding: 0.3em 0.6em;
    text-decoration: none;
}
#trackit1234567890A track_this_item_button:hover {
    color: #808080;
}
</style>

<a id="trackit1234567890A">
<form style='display:block;'>
<!--input type='checkbox'>email when price drop to my</input-->
<?php 
	$title = $_GET["title"]; 
	$encoded_url = $_GET["url"];
?>
<input id='trackit1234567890pageuri' type='hidden' value="<?php echo $encoded_url; ?>"></input>
<div style='clear:both'></div>
<label for='trackit1234567890title' style='float:left; font-size:12px;'>what would you call this item ?</label>
<input id='trackit1234567890title' name='trackit1234567890title' class='trackit1234567890title' style='float:left;' value="<?php echo $title;?>"></input>
<div style='clear:both'></div>
<input type='checkbox' for='trackit1234567890price_notify_checkbox' style='float:left;'></input>
<label for='trackit1234567890price' style='float:left; font-size:12px;'>email when price drop to my price</label>
<input id='trackit1234567890price' type='number' maxlength='10' name='trackit1234567890price' class='trackit1234567890price' style='float:left;'></input>
<div style='clear:both'></div>
<label for='trackit1234567890email' style='float:left; font-size:12px; font-weight:500;'>email </label>
<input id='trackit1234567890email' maxlength='400' name='trackit1234567890email' class='trackit1234567890email' style='width:300; float:left;'></input>
<br/>
<br/>
<track_this_item_button onclick="trackit1234567890( $(this) );">Track this item at trackthisitem.com</track_this_item_button>
</form>
</a>
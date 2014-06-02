<?php
$SITE_NAME = "http://www.savethisitem.com/trackit/";
?>
<script src="<?php echo $SITE_NAME; ?>js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
function trackit1234567890_part1($this)
{
	$a = $this.parents("#trackit1234567890A");
	$pageurl = $a.find("#form1").find('#trackit1234567890pageuri').val();
	$TRACKER_SITE_NAME = $SITE_NAME; //"http://localhost/trackit/";
	$a.find("#msg").text("Please Wait ... ");
	$.ajax({
		type:"POST",
		data:{url: $pageurl},
		url: $TRACKER_SITE_NAME  + "products/add_item_part1",
		success : function(data) {
			info_for_second_part = eval('(' + data + ')');
			//title = data;
			title = info_for_second_part.title;
			user_email = info_for_second_part.user_email;
			$form2 = $a.find('#form2');
			$form2.find('#trackit1234567890title').val(title);
			$form2.find('#trackit1234567890pageurihidden').val($pageurl);
			
			if (user_email != "")
			{
				$form2.find('#trackit1234567890email').val(user_email);
				$form2.find('#trackit1234567890email').attr('disabled', true);
			}
			
			$a.find('#form1').css('display', 'none');
			
			$form2.css('opacity', '0');
			$form2.css('display', 'block');
			$form2.fadeTo('slow', 1);
			
			$a.find("#msg").text("Fill up the above form and click 'track this item' ");

		},
		error : function() {
			console.log("call failed");
			//ToggleTrackitform($form);
		}
	});
}

function trackit1234567890_part2($this)
{
	$a = $this.parents("#trackit1234567890A");
	$form2 = $a.find('#form2');
	$pageurl = $form2.find('#trackit1234567890pageurihidden').val();
	$name = $form2.find('#trackit1234567890title').val();
	$email = $form2.find("#trackit1234567890email").val()
	$price = $form2.find("#trackit1234567890price").val()
	
	$a.find("#msg").text("Please Wait ..... This may take a while.");
	
	$TRACKER_SITE_NAME = $SITE_NAME; //"http://localhost/trackit/";
	$.ajax({
		type:"POST",
		data:{url: $pageurl, name: $name, email: $email, wait_price: $price},
		url: $TRACKER_SITE_NAME  + "products/add_item_part2",
		success : function(data) {
			var result = $.parseJSON($.parseJSON(data));
			//alert(result.success);
			if (result.success)
			{
				console.log("item successfully added");
				$a = $("#trackit1234567890A");
				$form1 = $a.find('#form1');
				$form2 = $a.find('#form2');
				$form1.fadeTo('slow', 0, function(){ $(this).css('display','none');} );
				$form2.fadeTo('slow', 0, function(){ $(this).css('display','none');} );
				$a.find("#msg").text("Congrats. We have added this item to your collection at trackthisitem.com . We'll alert you if we find a drop in the price of this item.");
				
				/*
				$form3 = $a.find('#form3')
				$form3.css('opacity', '0');
				$form3.css('display', 'block');
				$form3.fadeTo('slow', 1);
				
				b = result.groups
				for (var i =0;i<b.length;i++)
				{
					$('#group_name_select_box').append($('<option>', { 
						value: result.groups[i],
						text : result.groups[i] 
					}));
					$('#form3').find('#itemid').val(result.itemid);
				}
				*/
			}
			else
			{
				console.log("item adding failed");
				console.log(result.msg);
				$a.find("#msg").text(result.msg);
			}

		},
		error : function() {
			console.log("call failed");
			$a.find("#msg").text(result.msg);
		}
	});
}
 
 
function trackit1234567890_part3($this)
{
	$form3 = $this.parents('#form3')
	if ($this.parent().find("#is_creating_new_collection").val() == 1)
	{
		$group_name = $form3.find('#new_group_name').val()
	}
	else
	{
		$group_name = $form3.find('#group_name_select_box').val()
	}
	$itemid = $form3.find('#itemid').val()

	$TRACKER_SITE_NAME = $SITE_NAME; //"http://localhost/trackit/";
	$.ajax({
		type:"POST",
		data:{itemid: $itemid, group_name: $group_name},
		url: $TRACKER_SITE_NAME  + "products/update_group",
		success : function(data) {
			var result = $.parseJSON($.parseJSON(data));
			//alert(result.success);
			if (result.success)
			{
				console.log("item group successfully added");
				$a = $("#trackit1234567890A");
				$form1 = $a.find('#form1');
				$form2 = $a.find('#form2');
				$form1.fadeTo('slow', 0, function(){ $(this).css('display','none');} );
				$form2.fadeTo('slow', 0, function(){ $(this).css('display','none');} );
				$form3.fadeTo('slow', 0, function(){ $(this).css('display','none');} );
				$a.find("#msg").text("Congrats. We have saved this item to our tracker. You can browse and retrieve your saved items by going to trackthisitem.com");
				
			}
			else
			{
				console.log("item group update failed");
				console.log(result.msg);
				$a.find("#msg").text(result.msg);
			}

		},
		error : function() {
			console.log("call failed");
			$a.find("#msg").text(result.msg);
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

function show_new_collection_form($this)
{
	$form = $this.parent().find("#add_new_collection_name");
	$form.css('opacity', 0);
	$form.css('display', 'block');
	$form.fadeTo('slow', 1.0);
}

</script>
<style>
#msg
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
<form id='form1' style='display:block;'>
<!--input type='checkbox'>email when price drop to my</input-->

<label for='trackit1234567890pageuri' style='float:left; font-size:12px;'>item page url :</label>
<input id='trackit1234567890pageuri' name='trackit1234567890pageuri' style='width:300; float:left;'></input>
<br/>
<br/>
<div style='clear:both'></div>
<track_this_item_button onclick="trackit1234567890_part1( $(this) );">Track this item at trackthisitem.com</track_this_item_button>
</form>

<form id='form2' style='display:none;'>
<div style='clear:both'></div>
<input id='trackit1234567890pageurihidden' style='width:300; float:left;' type="hidden"></input>
<div style='clear:both'></div>
<input id='trackit1234567890title' name='trackit1234567890title' class='trackit1234567890title' style='float:left;' type="hidden"></input>
<div style='clear:both'></div>
<label for='trackit1234567890email' style='float:left; font-size:12px; font-weight:500;'>email </label>
<input id='trackit1234567890email' maxlength='400' name='trackit1234567890email' class='trackit1234567890email' style='width:300; float:left;'></input>
<br/>
<br/>
<track_this_item_button onclick="trackit1234567890_part2( $(this) );">Track this item at trackthisitem.com</track_this_item_button>
</form>
<form id='form3' style='display:none'>
	<input type='hidden' val='Newly Added' id='itemid'></input>		
	<div id='pick_existing_collection'>
		<select name='group_name' id='group_name_select_box' required='required'>
		</select>
		<input type='hidden' value='0' id='is_creating_new_collection'></input>
		<track_this_item_button onclick="trackit1234567890_part3( $(this) );">Add To Collection</track_this_item_button>
	</div>
	<track_this_item_button onclick="show_new_collection_form($(this));">+</track_this_item_button>
	<div id='add_new_collection_name' style='display:none'>
		<input name='new_group_name' id='new_group_name' required='required' maxlength='20'>
		</input>
		<input type='hidden' value='1' id='is_creating_new_collection'></input>
		<track_this_item_button onclick="trackit1234567890_part3( $(this) );">Add To Collection</track_this_item_button>
	</div>
</form>
<div id="msg">Enter URL of the Item to track</div>
</a>
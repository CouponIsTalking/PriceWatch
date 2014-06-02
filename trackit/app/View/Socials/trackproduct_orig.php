<?php
echo $this->Html->script('tracker/trackprod');
?>
<script type="text/javascript">

function RunOnLoad(){
	
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
    margin: 10px auto 5px auto;
    opacity: 0.95;
    padding: 15px;
    position: relative;
    text-align: center;
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

* ._how_to_track{
	font-size:24px;
	font-style:Helvetica sans-serif;
	color:rgba(255,255,255,0.8);
}
*._how_to_track_num_and_desc{
	float:left;
	max-width:30%;
}
* ._how_to_track_num{
	background:rgba(0,0,0,0.4);
	border-radius:50%;
	-web-border-radius:50%;
	-moz-border-radius:50%;
	width:100px;
	height:100px;
	text-align:center;
}
*._how_to_track_num_round{
	position:relative;
	top:10px;
	left:13px;
}
* ._how_to_track_desc{
}
</style>


<div id="trackit1234567890A" style="padding-top:50px;padding-bottom:50px;background-image:url('<?php echo SITE_NAME.'img/bgtex3.jpg'; ?>');background-repeat:repeat;">

<!--div class='_how_to_track'>
<div class='_how_to_track_num_and_desc'>
<div class='_how_to_track_num'>1</div>
<div class='_how_to_track_desc'>Enter Product Detail Page URL.</div>
</div>
<div class='_how_to_track_num_and_desc'>
<div class='_how_to_track_num'>2</div>
<div class='_how_to_track_desc'>Enter the price you would want to pay for it.</div>
</div>
<div class='_how_to_track_num_and_desc'>
<div class='_how_to_track_num'>3</div>
<div class='_how_to_track_desc'>Track it and we will let you know of price drops.</div>
</div>
</div-->

<form id='form1' style='width:650px;display:block;'>
<!--input type='checkbox'>email when price drop to my</input-->

<label for='trackit1234567890pageuri' style='float:left; font-size:12px;'>Item page url :</label>
<input id='trackit1234567890pageuri' name='trackit1234567890pageuri' style='width:300; float:left;'></input>
<br/>
<br/>
<div style='clear:both'></div>
<?php
if ($user_logged_in){
echo "
	<track_this_item_button onclick='trackit1234567890_part1( $(this) );'>Track this item</track_this_item_button>
	";	
}else{
echo "
	<track_this_item_button onclick='show_ulf();'>SignIn to track this item</track_this_item_button>
	";	
}
?>
</form>

<form id='form2' style='width:650px;display:none;'>
<div style='clear:both'></div>
<input type='hidden' id='trackit1234567890pageurihidden' style='width:300; float:left;'></input>
<label for='trackit1234567890title' style='font-size:14px;'>what would you call this item ?</label>
<input id='trackit1234567890title' name='trackit1234567890title' class='trackit1234567890title' style='float:left;'></input>

<div style='clear:both'></div>
<label style='font-size:14px;'>How much would you pay for this item?</label>
<input id='trackit1234567890price' type='number' maxlength='10' name='trackit1234567890price' class='trackit1234567890price' style='width:300px;float:left;'></input>

<div style='clear:both'></div>
<input type='checkbox' for='trackit1234567890price_notify_checkbox' style='float:left;'></input>
<label for='trackit1234567890price' style='float:left; font-size:14px;'>email when price drop to my price</label>

<div style='clear:both'></div>
<label for='trackit1234567890email' style='float:left; font-size:14px; font-weight:500;'>email </label>
<input id='trackit1234567890email' maxlength='400' name='trackit1234567890email' class='trackit1234567890email' style='width:300; float:left;'></input>
<br/>
<br/>
<track_this_item_button onclick="trackit1234567890_part2( $(this) );">Track this item</track_this_item_button>
</form>
<form id='form3' style='width:650px;display:none'>
	<input type='hidden' val='Newly Added' id='itemid'></input>		
	<div id='pick_existing_collection'>
		<select name='group_name' id='group_name_select_box' required='required'>
		</select>
		<input type='hidden' value='0' id='is_creating_new_collection'></input>
		<track_this_item_button onclick="trackit1234567890_part3( $(this) );">Add To Collection</track_this_item_button>
	</div>
	<track_this_item_button onclick="show_new_collection_form($(this));">+</track_this_item_button>
	<div id='add_new_collection_name' style='display:none'>
		<label style='font-size:14px;'>Collection name</label>
		<input name='new_group_name' id='new_group_name' required='required' maxlength='20'>
		</input>
		<input type='hidden' value='1' id='is_creating_new_collection'></input>
		<track_this_item_button onclick="trackit1234567890_part3( $(this) );">Add To Collection</track_this_item_button>
	</div>
</form>
<div id="msg" style="width:650px;margin-left:auto;margin-right:auto;">Enter URL of the Item to track</div>


<!-- list supported stores -->
<div style='clear:both;'></div>

<?php

echo "
<div style='color:rgba(255, 255, 255,0.69);font-size:24px;font-family:Helvetica sans-serif;padding:15px;margin:auto;margin-top:0px;'>
Supported Stores
</div>
";

?>

<?php
if (!empty($companies)){
	echo "<div style='margin:auto;'><ul>";
	$i = 0;
	foreach ($companies as $company_id => $company)
	{
		$i = $i+1;
		//$encoded_url = urlencode($company['Company']['website']);
		$encoded_url = urlencode($company['website']);
		echo "<div class='tag' style='float:left; width:400px; margin:10px;' onclick=\"OpenInNewTab(addhttp('{$company['website']}'));\">";
			//echo $company['Company']['name'];
			echo $company['name'];
		echo "</div>";
		
	}
	echo "</ul></div>";
}
?>

<div style='clear:both;'></div>
</div>
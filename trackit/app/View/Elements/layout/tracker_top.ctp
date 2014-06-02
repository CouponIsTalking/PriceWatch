<?php echo $this->Html->script('custom/viewcollection_button_click'); ?>

<!--ul id="toolbar">
	<ul id="toolbar-center">
		<a id="trackit1234567890AButton">
		<img onclick="trackit1234567890showiframe( $(this) );" alt="track this item" src="http://localhost/AB/img/trackit.gif" style="width:30px; height:30px; cursor:pointer;"></img>
		</a>
		<red_button>TrackIt</red_button>
	</ul>					
</ul-->					
<div class='tracker_top'>
<div id="trackit1234567890AButton">
<!--img onclick="trackit1234567890showiframe( $(this) );" alt="track this item" src="http://localhost/AB/img/trackit.gif" style="width:30px; height:30px; cursor:pointer;"></img-->
<purple_button onclick="trackit1234567890showiframe( $(this) );" alt="track this item" style="font-size:20px;">TrackPrice</purple_button>
</div>
<div id="viewcollection1234567890AButton">
<?php
if (!empty($user_email) && $user_email != "")
{
	$my_collection_link = SITE_NAME . "/user_products/my_collection";
	echo "<green_button onclick=\"OpenInNewTab('{$my_collection_link}');\" alt=\"view your collection\" style=\"font-size:20px;\">ViewYourCollection</green_button>";
}
else {

	//<!--img onclick="trackit1234567890showiframe( $(this) );" alt="track this item" src="http://localhost/AB/img/trackit.gif" style="width:30px; height:30px; cursor:pointer;"></img-->
	echo "<green_button onclick=\"viewcollection1234567890showform($(this));\" alt=\"view your collection\" style=\"font-size:20px;\">ViewYourCollection</green_button>";

}
?>
</div>

</div>

<div id="viewcollection_part1_form" style='display:none;'>
<div class='email_input_for_collection'>
	<form>
	<!--input type='checkbox'>email when price drop to my</input-->

	<label for='view_collect_input_email' style='float:left; font-size:12px;'>email :</label>
	<input id='view_collect_input_email' name='view_collect_input_email' style='font-size: 15px; width:300; float:left;'></input>
	<br/>
	<br/>
	<div style='clear:both'></div>
	<track_this_item_button onclick="viewcollect1234567890_part1( $(this) );">Get My Collections</track_this_item_button>
	</form>
</div>
<div class='collection_list' style='display:none'>
	<form>
		<track_this_item_button onclick="viewcollect1234567890_part2( $(this) );">Show Selected Collections</track_this_item_button>
	</form>
</div>
<div id="msg">We're asking for email, so that we can look up your collection by your email.</div>

</div>
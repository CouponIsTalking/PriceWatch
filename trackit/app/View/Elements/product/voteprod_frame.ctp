<?php
// $loggedin_user_id
// $show_close_button
?>

<?php
//echo $this->Html->script('tracker/voteprod');
?>

<style>
* .prodvote_msg
{
    background-color: rgba(236, 236, 236, 0.66);
    border: 1px solid #FFFFFF;
    box-shadow: 0 1px 10px 0 rgba(0, 0, 0, 0.33);
    color: black;
    font-family: Arial,'Liberation Sans','DejaVu Sans',sans-serif;
    font-size: 16px;
    height: auto;
    margin-top: 10px;
    opacity: 0.95;
    padding: 15px;
    position: relative;
    text-align: center;
    width: 300px;
}
* #prodvote .__prodvote_form
{
    background-color: rgba(236, 236, 236, 0.66);
    border: 1px solid #FFFFFF;
    box-shadow: 0 1px 10px 0 rgba(0, 0, 0, 0.33);
    color: #333;
    font-family: Arial,'Liberation Sans','DejaVu Sans',sans-serif;
    font-size: 16px;
    height: auto;
    opacity: 0.95;
    padding: 15px;
    position: relative;
    text-align: center;
}
* #prodvote .__vote_this_item_button {
    background-color: rgba(156, 154, 154, 0.66);
    border: 1px solid #000000 !important;
    border-radius: 3px 3px 3px 3px;
	-moz-border-radius: 3px 3px 3px 3px;
	-webkit-border-radius: 3px 3px 3px 3px;
	box-shadow: 0 2px 1px rgba(0, 0, 0, 0.3);
	-webkit-box-shadow: 0 2px 1px rgba(0, 0, 0, 0.3);
    -moz-box-shadow: 0 2px 1px rgba(0, 0, 0, 0.3);
    color: black;
    cursor: pointer;
    margin: 5px;
    padding: 0.3em 0.6em;
    text-decoration: none;
}

#prodvote .__vote_this_item_button:hover {
    /*color: #808080;*/
	font-weight:bold;
}

* #prodvote .__vote_this_item_button.pressed{
	background-color:#C26575;
	border: 1px solid pink !important;
	color:pink;
	font-weight:bold;
}
* #prodvote .__vote_this_item_button.pressed:hover{
	font-weight:normal;
}

* ._prodvote_popup_x{
	background-color: rgba(236, 236, 236, 0.66);
    border: 1px solid #FFFFFF;
    box-shadow: 0 1px 10px 0 rgba(0, 0, 0, 0.33);
    color: black;
    font-family: Arial,'Liberation Sans','DejaVu Sans',sans-serif;
    font-size: 20px;
    height: auto;
    height: auto;
    opacity: 0.95;
    padding: 15px;
    position: relative;
    text-align: center;
    width: 220px;
	margin-bottom:10px;
	/*transition-duration:2s;
	-moz-transition-duration:2s;
	-webkit-transition-duration:2s;*/
	cursor:pointer;
}
* ._prodvote_popup_x:hover{
	font-size: 30px;
	padding-top: 10px;
	padding-bottom: 10px;
}
</style>

<?php
if (!empty($loggedin_user_id)){
	$user_logged_in = true;
}else{
	$user_logged_in = false;
}
?>

<div id='prodvote' style="position:fixed;" onclick="var event=arguments[0]||window.event;event.stopPropagation();event.cancelBubble=true;">

<?php
if ($show_close_button){
	echo "<div onclick=\"$(this).parent('#prodvote').parent().css('display','none');\" class='_prodvote_popup_x'>X</div>";
}
?>

<form class='__prodvote_form' style='width:220px;display:none;'>
<input class='__prod_id' type='hidden' value=''/>
<div style='clear:both'></div>

<?php


echo "<span class='__votebox'><span class='__likevote'>";
if ($user_logged_in) {
echo "<div class='__vote_this_item_button' onclick=\"prodvote($(this),'like');\">I Like It</div>";
}else{
echo "<div class='__vote_this_item_button' onclick='show_ulf();'>I Like It</div>";
}
echo "</span></span>";

echo "<span class='__votebox'><span class='__wantvote'>";
if ($user_logged_in) {
echo "<div class='__vote_this_item_button' onclick=\"prodvote($(this),'want');\">I Want It</div>";
}else{
echo "<div class='__vote_this_item_button' onclick='show_ulf();'>I Want It</div>";
}
echo "</span></span>";

echo "<span class='__votebox'><span class='__ownvote'>";
if ($user_logged_in) {
echo "<div class='__vote_this_item_button' onclick=\"prodvote($(this),'own');\">I Own It</div>";
}else{
echo "<div class='__vote_this_item_button' onclick='show_ulf();'>I Own It</div>";
}
echo "</span></span>";
?>

</form>
<div class="prodvote_msg" style="width:220px;"></div>
</div>
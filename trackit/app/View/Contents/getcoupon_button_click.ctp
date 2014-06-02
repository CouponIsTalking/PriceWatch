<?php 
echo $this->Html->script('custom/coupons'); 
echo $this->Html->script('twinf'); 
echo $this->Html->script('custom/update_resps'); 
?>

<script type='text/javascript'>
	from_url_from_php = "<?php echo $from_url; ?>";
</script>

<?php
$content_unix_timestamp_and_id = $content['Content']['unix_timestamp_and_id'];
$content_id = $content['Content']['id'];
$title = $content['Content']['title'];
$desc = $content['Content']['desc'];
$image_link = $content['Content']['link'];
$fb_coupon_code = $content['Content']['fb_coupon_code'];
$tw_coupon_code = $content['Content']['tw_coupon_code'];
$fb_offer = $content['Content']['fb_offer'];
$tw_offer = $content['Content']['tw_offer'];

echo "<div class='share_tile_body>'";

echo "
<h3>
";

if (!empty($content['Content']))
{
echo "Get even more coupons by spreading the word.";
}
else
{

echo "The offer is not active any more :(. <a href=\"".SITE_NAME."\">Signup</a> to get more such offers.";
}

echo "
</h3>
";

echo "<div style='clear:both'></div>";
echo "<div style='height:30px;'></div>";
echo "<div style='clear:both'></div>";

echo "
<div class='h_100_tile'>
	<share_item_details style='display:none;'>
		<share_title>{$title}</share_title>
		<share_desc>{$desc}</share_desc>
		<share_image>{$image_link}</share_image>
		<content_id>{$content_id}</content_id>
		<ctid>{$content_unix_timestamp_and_id}</ctid>
	</share_item_details>
	<div class='small_title'>
	{$fb_offer}
	</div>
";
if ($coupon_is_open['fb'])
{
echo "
	<div class='coupon_code'>
		{$fb_coupon_code}
	</div>
	<div class='full_title'>
		Facebook Share Not Needed.
	</div>
	";
}
else
{
echo "
	<div class='coupon_code' style='display:none;'>
		{$fb_coupon_code}
	</div>
	<div class='full_title' onclick=\"call_fb_post_to_get_coupon($(this));\">
		Facebook Share to Get Coupon
	</div>
	";
}

echo "
</div>
";

echo "
<div class='h_100_tile'>
	<share_item_details style='display:none;'>
		<share_title>{$title}</share_title>
		<share_desc>{$desc}</share_desc>
		<share_image>{$image_link}</share_image>
		<content_id>{$content_id}</content_id>
	</share_item_details>
	<div class='small_title'>
	{$tw_offer}
	</div>
";

if ($coupon_is_open['tw'])
{
echo "
	<div class='coupon_code'>
		{$tw_coupon_code}
	</div>
	<div class='full_title'>
		Tweet Not Needed.
	</div>
	";
}
else
{
echo "
	<div class='coupon_code' style='display:none;'>
		{$tw_coupon_code}
	</div>
	<div class='full_title' onclick=\"call_tw_tweet_to_get_coupon($(this), {$is_user_logged_in});\">
		Tweet It to Get Coupon
	</div>
	";

$tw_login_url = SITE_NAME . "twitter/twitter/tw_login";

//debug($this->CommonFunc->get_full_current_url());

echo "
	<div style='display:none'>
	<a id='tw_login_link' href=\"{$tw_login_url}\"></a>
	</div>
	";
echo "<div style='display:none'>";
	echo $this->Form->create('user_actions', array('action' => 'tweetit_for_coupon'));
	echo $this->Form->input('tw_content_id', array('type'=> 'text', 'default' => $content_unix_timestamp_and_id, 'label'=>'', 'maxlength' => 30, 'style'=>'width:600px; height:10px;'));
	echo $this->Form->input('post_action_redirect', array('type'=>'text', 'default'=> $this->CommonFunc->get_full_current_url()));
	echo "
	<!--form style='display:none'-->
		<!--input type='hidden' id='tw_title' value={$title}></input>
		<input type='hidden' id='tw_desc' value={$desc}></input>
		<input type='hidden' id='tw_image' value={$image_link}></input>
		<input type='hidden' id='tw_content_id' value={$content_id}></input-->
		<input type='submit' id='tw_button'>submit</input>
	";
	echo $this->Form->end();
echo "</div>";

}

echo "
</div>
";

if (!empty($from_url))
{
	echo "
	<div class='h_100_tile'>
		<share_item_details style='display:none;'>
			<share_title>{$title}</share_title>
			<share_desc>{$desc}</share_desc>
			<share_image>{$image_link}</share_image>
			<content_id>{$content_id}</content_id>
		</share_item_details>
		<div class='small_title'>
		{$fb_offer}
		</div>
	";
	if ($coupon_is_open['fb'])
	{
		echo "
		<div class='coupon_code'>
			{$fb_coupon_code}
		</div>
		<div class='full_title'>
			TrackIt Not Needed.
		</div>
		";
	}
	else
	{
		$slashed_from_url = urlencode($from_url);//addslashes($from_url);

		echo "
		<div class='coupon_code' style='display:none;'>
			{$fb_coupon_code}
		</div>
		<div class='full_title' onclick=\"initiate_saveit_share_for_coupon($(this), from_url_from_php, {$is_user_logged_in});\">
			TrackIt to Get Coupon
		</div>
		";
	}

	echo "
	</div>
	";
}

echo "</div>";

$loading_image_path = SITE_NAME . "/img/loadin.gif";

echo "
<img id='loading_image' style=\"display:none; position:fixed; top:48%; left:48%;\" src=\"{$loading_image_path}\"></img>
";

?>
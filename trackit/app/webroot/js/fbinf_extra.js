function BuildSocialProfileButton()
{
	
	response = getme(BuildSocialProfileButton);
	if (response)
	{
		
		$('.wait_on_fb').children('.fb_login_button').hide(); // style="display:none"
		$('.wait_on_fb').children('.fb_welcome').show();
		$('.wait_on_fb').children('.fb_welcome').first().text(response.name);
		return 0;
	}
	else
	{
		fb_login(BuildSocialProfileButton);
	}
	
	response = getme(BuildSocialProfileButton);
	if (response)
	{
		
		$('.wait_on_fb').children('.fb_login_button').hide();
		$('.wait_on_fb').children('.fb_welcome').show();
		$('.wait_on_fb').children('.fb_welcome').first().text(response.name);
		return 0;
	}
	else
	{
		$('.wait_on_fb').children('.fb_login_button').show();
		$('.wait_on_fb').children('.fb_welcome').hide();
		return 0;
	}

}

function initiate_fb_post_to_get_coupon_fbapi($params)
{
$title = $params['params'][0];
$desc = $params['params'][1];
$image_link = $params['params'][2];
$news_title = $params['params'][3];
$news_link = $params['params'][4];
scn = params['scn'];sp = params['sp'];ecn = params['ecn'];ep = params['ep'];

FB.api('/me/permissions', function(response){perms_response = response;
if (perms_response && perms_response.data && perms_response.data.length){
 var permissions = perms_response.data.shift();
 if ((typeof permissions.publish_stream != 'undefined') && permissions.publish_stream) {
  FB.api('/me/feed', 'post', { message: $title, name: $news_title, link: $news_link, picture: $image_link, description: $desc }, function(create_post_response) {
	if (!create_post_response || create_post_response.error) {
		ec = window[ecn];ec(ep);
	} else {sc = window[scn];sc(sp);}				  
  });}else{ec = window[ecn];ec(ep);}
}else{ec = window[ecn];ec(ep);}
});
}
function test_fb_resp_update_and_show_coupon_one()
{
	$this = $('.test_div');
	
	$params = {}
	$params['objtype'] = 'oc';
	$params['objid'] = '51';
	$params['obj'] = $this;
	$params['promo_method'] = 'fb_post';
	$params['resp'] = new Object();
	$params['resp'].post_id = "100001666415739_617201678345374";
	$params['share_info'] = ['Loft is so Cool', 'See what designers at LOFT have been workingon', 'http://alpha.usemenot.com/trackit/img/contents/6788272014_02_24_14_03_35___27_0__teen-vogue-cover-gir.jpg', 'Loft in news', 'http://newslink'];
	$params['scn'] = 'show_success_message';
	$params['sp'] = 'Success!';
	
	fb_resp_update_and_show_coupon($params);
}

function test_fb_resp_update_and_show_coupon_two()
{
	$this = $('.test_div');
	
	$params = {}
	$params['objtype'] = 'content';
	$params['objid'] = 1;
	$params['obj'] = $this;
	$params['promo_method'] = 'fb_post';
	$params['resp'] = new Object();
	$params['resp'].post_id = "100001666415739_617201678345374";
	$params['share_info'] = ['Loft is so Cool', 'See what designers at LOFT have been workingon', 'http://alpha.usemenot.com/trackit/img/contents/6788272014_02_24_14_03_35___27_0__teen-vogue-cover-gir.jpg', 'Loft in news', 'http://newslink'];
	$params['scn'] = 'show_success_message';
	$params['sp'] = 'Success!';
	
	fb_resp_update_and_show_coupon($params);
}
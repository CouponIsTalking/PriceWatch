<script type='text/javascript'>
function RunOnLoad()
{
	_show_next_slide.present = 0;
	_show_next_slide.working = 0;
	setInterval(_show_next_slide, 5000);
	setInterval(_brightup_first_slide, 2000);
	setInterval(_brightup_second_slide, 3000);
	setInterval(_brightup_third_slide, 4000);
	setInterval(_brightup_fifth_slide, 5000);
	$('._main_slide_top').css('width', $('._main_slide').width());
	//$(window).one('scroll', _scroll_check);
	//setInterval(_shake_explore, 300);
}
function _show_next_slide()
{
	if (_show_next_slide.working){return;}
	
	var $to_show = _show_next_slide.present + 1;
	if ($("._slide").length==$to_show){$to_show = 0;}
	var $to_hide = _show_next_slide.present;
	
	_show_next_slide.working = 1;
	$("._slide:eq("+ $to_hide + ")").fadeOut('slow',function(){
		$("._slide:eq("+ $to_show + ")").fadeIn('fast');
		_show_next_slide.present = $to_show;
		_show_next_slide.working = 0;
		}
	);
	
}

function _shift($e, $p, $by){
	$e.offset({ left: $p+$by });
	return $e;
}

function _shake_explore(){
	if (undefined == _shake_explore.times){_shake_explore.times = 0;}
	else {_shake_explore.times=_shake_explore.times+1;}
	
	if (10==_shake_explore.times || 11==_shake_explore.times){
		var $showing = _show_next_slide.present;
		var $left = parseInt($("._explorebtn:eq("+ $showing + ")").offset().left);
	}
	if (10==_shake_explore.times){
		$("._explorebtn:eq("+ $showing + ")").offset({left:$left+20});
	}else if (11==_shake_explore.times){
		$("._explorebtn:eq("+ $showing + ")").offset({left:$left-20});
		_shake_explore.times = 0;
	}
	
}

function _brightup_first_slide(){
	
	if (undefined == _brightup_first_slide.next){
		_brightup_first_slide.next = 0;
	}
	
	if (3!=_brightup_first_slide.next){
		$('._main_slide').eq(0).find('._increase_fan_base').eq(_brightup_first_slide.next).css('color', 'white');
	}
	$('._main_slide').eq(0).find('._increase_fan_base').eq(_brightup_first_slide.next).animate({borderBottomWidth: "5px"}, 500 );
	//css('border-bottom', '3px solid white');
	if (undefined != _brightup_first_slide.prev){
		if (3!=_brightup_first_slide.prev){
			$('._main_slide').eq(0).find('._increase_fan_base').eq(_brightup_first_slide.prev).css('color', 'rgba(255,255,255,0.8);');
		}
		$('._main_slide').eq(0).find('._increase_fan_base').eq(_brightup_first_slide.prev).animate({borderBottomWidth: "0px"}, 300 );
	}
	
	_brightup_first_slide.prev = _brightup_first_slide.next;
	if (3==_brightup_first_slide.next){_brightup_first_slide.next=0;}
	else{_brightup_first_slide.next=_brightup_first_slide.next+1;}	
	
}

function _brightup_second_slide(){
	
	if (undefined == _brightup_second_slide.next){
		_brightup_second_slide.next = 1;
	}
	
	/*
	if (3!=_brightup_second_slide.next){
		$('._main_slide').eq(0).find('._increase_fan_base').eq(_brightup_second_slide.next).css('color', 'white');
	}
	*/
	$('._main_slide').eq(1).find('._increase_fan_base').eq(_brightup_second_slide.next).animate({borderBottomWidth: "5px"}, 500 );
	//css('border-bottom', '3px solid white');
	if (undefined != _brightup_second_slide.prev){
		/*if (3!=_brightup_second_slide.prev){
			$('._main_slide').eq(0).find('._increase_fan_base').eq(_brightup_second_slide.prev).css('color', 'rgba(255,255,255,0.8);');
		}*/
		$('._main_slide').eq(1).find('._increase_fan_base').eq(_brightup_second_slide.prev).animate({borderBottomWidth: "0px"}, 300 );
	}
	
	_brightup_second_slide.prev = _brightup_second_slide.next;
	if (4==_brightup_second_slide.next){_brightup_second_slide.next=1;}
	else{_brightup_second_slide.next=_brightup_second_slide.next+1;}	
	
}

function _brightup_third_slide(){
	
	if (undefined == _brightup_third_slide.next){
		_brightup_third_slide.next = 0;
	}
	
	if (3!=_brightup_third_slide.next){
		$('._main_slide').eq(2).find('._increase_fan_base').eq(_brightup_third_slide.next).css('color', 'white');
	}
	
	$('._main_slide').eq(2).find('._increase_fan_base').eq(_brightup_third_slide.next).animate({borderBottomWidth: "5px"}, 500 );
	//css('border-bottom', '3px solid white');
	if (undefined != _brightup_third_slide.prev){
		if (3!=_brightup_third_slide.prev){
			$('._main_slide').eq(2).find('._increase_fan_base').eq(_brightup_third_slide.prev).css('color', 'rgba(255,255,255,0.8);');
		}
		$('._main_slide').eq(2).find('._increase_fan_base').eq(_brightup_third_slide.prev).animate({borderBottomWidth: "0px"}, 300 );
	}
	
	_brightup_third_slide.prev = _brightup_third_slide.next;
	if (7==_brightup_third_slide.next){_brightup_third_slide.next=0;}
	else{_brightup_third_slide.next=_brightup_third_slide.next+1;}	
	
}

function _brightup_fifth_slide(){
	
	if (undefined == _brightup_fifth_slide.next){
		_brightup_fifth_slide.next = 0;
	}
	
	/*
	if (3!=_brightup_fifth_slide.next){
		$('._main_slide').eq(0).find('._increase_fan_base').eq(_brightup_fifth_slide.next).css('color', 'white');
	}
	*/
	$('._main_slide').eq(4).find('._increase_fan_base').eq(_brightup_fifth_slide.next).animate({borderBottomWidth: "5px"}, 500 );
	//css('border-bottom', '3px solid white');
	if (undefined != _brightup_fifth_slide.prev){
		/*if (3!=_brightup_fifth_slide.prev){
			$('._main_slide').eq(0).find('._increase_fan_base').eq(_brightup_fifth_slide.prev).css('color', 'rgba(255,255,255,0.8);');
		}*/
		$('._main_slide').eq(4).find('._increase_fan_base').eq(_brightup_fifth_slide.prev).animate({borderBottomWidth: "0px"}, 300 );
	}
	
	_brightup_fifth_slide.prev = _brightup_fifth_slide.next;
	if (3==_brightup_fifth_slide.next){_brightup_fifth_slide.next=0;}
	else{_brightup_fifth_slide.next=_brightup_fifth_slide.next+1;}	
	
}

function _scroll_check(){
}

</script>
<?php $homepage = SITE_NAME . "open_campaigns/running_campaigns"; ?>

<!--div class='_main_slide_top' style="position:fixed;top:0px;padding:3%;padding-bottom:5px;padding-top:2px;background-image:url('<?php echo SITE_NAME; ?>img/bgtex3.jpg');background-repeat:repeat;text-align:center;">
<div style='padding:5px 5px 5px 5px;font-size:16px;color:white;'>
<div style='display:none;float:left;width:20%;text-align:left;' class='_main_slide_top_logo'>CouponIsTalking</div>
<div style='display:none;float:left;width:20%;text-align:center;' class='_increase_fan_tag'>Increase Your Fan Base.</div>
<div style='display:none;float:left;width:20%;text-align:center;' class='_social_media_important'>Social Media Is Important</div>
<div style='display:none;float:left;width:20%;text-align:center;' class='_what_is_social_coupon'>What Is Social Coupon?</div>
<div style='display:none;float:left;width:20%;text-align:center;' class='_pricing'>Pricing</div>
</div>
</div-->

<div class='_main_slide' style="padding:3%;padding-bottom:300px;background-image:url('<?php echo SITE_NAME; ?>img/bgtex3.jpg');background-repeat:repeat;text-align:center;">
<div class='_increase_base' style="padding-top:100px;font-family:Helvetica sans-serif;font-size:44px;color:rgba(255,255,255,0.8);">
<span class='_increase_fan_base_title' style='line-height:100%;font-size:36px;padding-left:0%;text-align:center;'>Increase Your Fan Base.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid white;line-height:100%;font-size:20px;'>Grow social traffic.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid white;line-height:100%;font-size:20px;'>Generate more email leads.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid white;line-height:100%;font-size:20px;'>Grow your facebook likes, tweet, and increasing your social foot prints in many more ways.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid orange;line-height:100%;font-size:20px;color:orange;'>All while giving the same regular coupon that you have been giving, but making it a little <span style='font-style:italic;'>social</span>, called <span style='font-style:italic;'>SocialCoupon</span>.</span><br/>
<span class='_explorebtn' style='font-size:36px;cursor:pointer;color:orange; border:2px solid orange; margin:0px; padding:5px;background-color:transparent' 
onmouseover="$(this).css('background-color', 'orange');$(this).css('color', 'white');"
onmouseout="$(this).css('background-color', 'transparent');$(this).css('color', 'orange');"
onclick="moveTo('<?php echo $homepage;?>')"
>Explore.</span>
</div>
</div>


<div class='_main_slide' style="padding:3%;padding-bottom:100px;background-image:url('<?php echo SITE_NAME; ?>img/white_tex2_small.jpg');background-repeat:repeat;">
<!--div class='_main_slide' style="padding:3%;padding-bottom:300px;background:rgba(255,255,255,0.6);text-align:left;"-->
<div class='_inspire' style="padding-top:100px;font-family:Helvetica sans-serif;font-size:44px;color:rgba(0,0,0,0.7);">
<span class='_increase_fan_base_title' style='line-height:100%;font-size:36px;padding-left:0%;text-align:center;'>What is a social coupon?</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(0,0,0,0.5);line-height:100%;font-size:20px;'>A way for people to earn coupon based on their social actions. With this, you can-</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(0,0,0,0.5);line-height:100%;font-size:20px;'>Distribute your photos, videos, increase fans and followers on Facebook using facebook coupon.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(0,0,0,0.5);line-height:100%;font-size:20px;'>Increase your twitter footprint using twitter coupon.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(0,0,0,0.5);line-height:100%;font-size:20px;'>Drive email signups with Newsletter coupon.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid orange;line-height:100%;font-size:20px;color:orange;'><span style='font-style:italic;'>No social coupon? No worries</span>, simply create a regular coupon with us to have more people discover you.</span><br/>
<span class='_explorebtn' style='font-size:36px;cursor:pointer;color:orange; border:2px solid orange; margin:0px; padding:5px;background-color:transparent' 
onmouseover="$(this).css('background-color', 'orange');$(this).css('color', 'white');"
onmouseout="$(this).css('background-color', 'transparent');$(this).css('color', 'orange');"
onclick="moveTo('<?php echo $homepage;?>')"
>Explore.</span>
</div>
</div>

<div class='_main_slide' style="padding:3%;padding-bottom:100px;background-image:url('<?php echo SITE_NAME; ?>img/bgtex3.jpg');background-repeat:repeat;text-align:center;">
<div class='_increase_base' style="padding-top:100px;font-family:Helvetica sans-serif;font-size:44px;color:rgba(255,255,255,0.8);"><div style='line-height:100%;font-size:36px;padding-left:0%;text-align:center;'>Social Media Is Important.</div>
<span class='_increase_fan_base' style='border-bottom:0px solid white;line-height:100%;font-size:20px;'>When people engage with businesses over social media, they spend 20-40% more.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid white;line-height:100%;font-size:20px;'>83% of the people are likely to visit a business that they read or hear about on social media.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid white;line-height:100%;font-size:20px;'>People trust reviews on Yelp and TripAdvisor, more than TV, newspaper or banner ads.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid orange;line-height:100%;font-size:20px;color:orange;'>Engaging people socially is never a bad idea.</span><br/>
</div>
<div class='_increase_base' style="padding-top:50px;font-family:Helvetica sans-serif;font-size:44px;color:rgba(255,255,255,0.8);"><div style='line-height:100%;font-size:36px;padding-left:0%;text-align:center;'>... And So Are Coupons.</div>
<span class='_increase_fan_base' style='border-bottom:0px solid white;line-height:100%;font-size:20px;'>86% of consumers say that a coupon affects their purchase decision.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid white;line-height:100%;font-size:20px;'>Coupons Increase Customer Satisfaction and Loyalty And Make Repeat Customers.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid white;line-height:100%;font-size:20px;'>People Who Use Coupons Spend More With The Businesses They Have Used Coupons Of.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid orange;line-height:100%;font-size:20px;color:orange;'>Engaging people with coupons is never a bad idea.</span><br/>
<span class='_explorebtn' style='font-size:36px;cursor:pointer;color:orange; border:2px solid orange; margin:0px; padding:5px;background-color:transparent' 
onmouseover="$(this).css('background-color', 'orange');$(this).css('color', 'white');"
onmouseout="$(this).css('background-color', 'transparent');$(this).css('color', 'orange');"
onclick="moveTo('<?php echo $homepage;?>')"
>Explore.</span>
</div>
</div>

<div class='_gap_slide' style="font-family:Helvetica sans-serif;font-size:36px;padding:3%;padding-bottom:30px;background:rgba(255,255,255,0.6);text-align:center;color:rgba(0,0,0,0.8);">
And So ... 
</div>

<div class='_main_slide' style="padding:3%;padding-bottom:300px;background-image:url('<?php echo SITE_NAME; ?>img/bgtex3.jpg');background-repeat:repeat;">
<div style='padding-top:100px;line-height:200%;font-family:Helvetica sans-serif;font-size:40px;padding-left:0%;text-align:center;color:orange;'>Put your social coupons and ...</div>
<div class='_slide' style="font-family:Helvetica sans-serif;font-size:44px;color:rgba(255,255,255,0.8);">
<div style='line-height:300%;font-size:36px;'>Discover that when your customers talk about you, it says a lot about you.<br/></div>
<div style='line-height:200%;font-size:30px;'>Let your customers do the talking.<br/></div>
<div style='line-height:200%;font-size:24px;'>And let yourself take care of other essentials.</div>
<span class='_explorebtn' style='font-size:36px;cursor:pointer;color:orange; border:2px solid orange; margin:0px; padding:5px;background-color:transparent' 
onmouseover="$(this).css('background-color', 'orange');$(this).css('color', 'white');"
onmouseout="$(this).css('background-color', 'transparent');$(this).css('color', 'orange');"
onclick="moveTo('<?php echo $homepage;?>')"
>Explore.</span>
</div>
<div class='_slide' style="display:none;font-family:Helvetica sans-serif;font-size:44px;color:rgba(255,255,255,0.8);">
<div style='line-height:300%;font-size:36px;'>Unleash the network of your loyal customers.<br/></div>
<div style='line-height:200%;font-size:30px;'>Help your network do the talking.<br/></div>
<div style='line-height:200%;font-size:24px;'>And let yourself take care of other essentials.</div>
<span class='_explorebtn' style='font-size:36px;cursor:pointer;color:orange; border:2px solid orange; margin:0px; padding:5px;background-color:transparent' 
onmouseover="$(this).css('background-color', 'orange');$(this).css('color', 'white');"
onmouseout="$(this).css('background-color', 'transparent');$(this).css('color', 'orange');"
onclick="moveTo('<?php echo $homepage;?>')"
>Explore.</span>
</div>
<div class='_slide' style="display:none;font-family:Helvetica sans-serif;font-size:44px;color:rgba(255,255,255,0.8);">
<div style='line-height:300%;font-size:36px;'>Build your business organically.<br/></div>
<div style='line-height:200%;font-size:30px;'>Let organic conversations do the talking.<br/></div>
<div style='line-height:200%;font-size:24px;'>And let yourself take care of other essentials.</div>
<span class='_explorebtn' style='font-size:36px;cursor:pointer;color:orange; border:2px solid orange; margin:0px; padding:5px;background-color:transparent' 
onmouseover="$(this).css('background-color', 'orange');$(this).css('color', 'white');"
onmouseout="$(this).css('background-color', 'transparent');$(this).css('color', 'orange');"
onclick="moveTo('<?php echo $homepage;?>')"
>Explore.</span>
</div>
<span style="position:absolute;margin-left:50%;width:100px;font-weight:bold;font-size:30px;color:white;">
<span></span>
<span></span>
<span></span>
</span>

</div>

<div class='_main_slide' style="padding:3%;padding-bottom:100px;background-image:url('<?php echo SITE_NAME; ?>img/white_tex2_small.jpg');background-repeat:repeat;">
<!--div class='_main_slide' style="padding:3%;padding-bottom:300px;background:rgba(255,255,255,0.6);text-align:left;"-->
<div class='_inspire' style="padding-top:100px;font-family:Helvetica sans-serif;font-size:44px;color:rgba(0,0,0,0.7);">
<div style='line-height:100%;font-size:36px;padding-left:0%;text-align:center;'>Pricing.</div>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(0,0,0,0.5);line-height:100%;font-size:20px;'>It is free to use and we are trying our best to stay free of cost.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(0,0,0,0.5);line-height:100%;font-size:20px;'>The more businesses we have onboard, the more it supports us to stay free of cost.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(0,0,0,0.5);line-height:100%;font-size:20px;'>Our goal is to stay free for all versions of the product, for all businesses that sign up in next 3 months.</span><br/>
<span class='_increase_fan_base' style='border-bottom:0px solid orange;line-height:100%;font-size:20px;color:orange;'>Support us to stay free for the local community by putting your social coupons with us.</span><br/>
<span class='_explorebtn' style='font-size:36px;cursor:pointer;color:orange; border:2px solid orange; margin:0px; padding:5px;background-color:transparent' 
onmouseover="$(this).css('background-color', 'orange');$(this).css('color', 'white');"
onmouseout="$(this).css('background-color', 'transparent');$(this).css('color', 'orange');"
onclick="moveTo('<?php echo $homepage;?>')"
>Explore.</span>
</div>
</div>


<div class='_main_slide' style="padding:3%;padding-bottom:100px;background-image:url('<?php echo SITE_NAME; ?>img/bgtex3.jpg');background-repeat:repeat;">
<!--div class='_main_slide' style="padding:3%;padding-bottom:100px;background:rgba(255,255,255,0.6);"-->
<div class='_inspire' style="font-family:Helvetica sans-serif;font-size:44px;color:rgba(255,255,255,0.7);">
<div style='line-height:100%;font-size:36px;padding-left:40%;'></div>
<div style='float:left;margin-left:1%;margin-right:1%;margin-top:15px;margin-bottom:30px;width:30%;'>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(255,255,255,0.7);line-height:100%;font-size:24px;'>Support 24x7</span><br/>
<a style='margin:0px;padding:0px;font-weight:normal;font-style:normal;font-size:16px;color:rgba(255,255,255,0.7);' href="<?php echo SITE_NAME ."contacts/speak_with_us"; ?>">Speak with us</a>
<div style='margin:0px;padding:0px;clear:both;'></div>
<a style='margin:0px;padding:0px;font-weight:normal;font-style:normal;font-size:16px;color:rgba(255,255,255,0.7);' onclick="$(this).find('span').show();">Call us <span style='display:none;'>3O3 249 3251</span></a>
<div style='margin:0px;padding:0px;clear:both;'></div>
<a style='margin:0px;padding:0px;font-weight:normal;font-style:normal;font-size:16px;color:rgba(255,255,255,0.7);' href="mailto:hemant@couponistalking.com" onclick="$(this).find('span').show();">Email us <span style='display:none;'>hemant@couponistalking.com</span></a>
</div>

<div style='float:left;margin-left:1%;margin-right:1%;margin-top:15px;margin-bottom:30px;width:30%;'>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(255,255,255,0.7);line-height:100%;font-size:24px;'>Social Media Studies
</span><br/>
<a style='margin:0px;padding:0px;font-weight:normal;font-style:normal;font-size:16px;color:rgba(255,255,255,0.7);' target='_blank' href="http://www.bain.com/publications/articles/putting-social-media-to-work.aspx">Social media research by Bain & Co</a>
<div style='margin:0px;padding:0px;clear:both;'></div>
<a style='margin:0px;padding:0px;font-weight:normal;font-style:normal;font-size:16px;color:rgba(255,255,255,0.7);' target='_blank' href="http://blog.bufferapp.com/10-surprising-social-media-statistics-that-will-make-you-rethink-your-strategy">Facts about social</a>
<div style='margin:0px;padding:0px;clear:both;'></div>
<a style='margin:0px;padding:0px;font-weight:normal;font-style:normal;font-size:16px;color:rgba(255,255,255,0.7);' target='_blank' href="http://www.contentfac.com/9-reasons-social-media-marketing-should-top-your-to-do-list/">Why should you go social</a>
<div style='margin:0px;padding:0px;clear:both;'></div>
<a style='margin:0px;padding:0px;font-weight:normal;font-style:normal;font-size:16px;color:rgba(255,255,255,0.7);' target='_blank' href="http://www.marketingcharts.com/wp/online/online-coupons-impact-the-bottom-line-13169/">Impact of coupons</a>
</div>
<div style='float:left;margin-left:1%;margin-right:1%;margin-top:15px;margin-bottom:30px;width:30%;'>
<span class='_increase_fan_base' style='border-bottom:0px solid rgba(255,255,255,0.7);line-height:100%;font-size:24px;'>About us</span><br/>
<a style='margin:0px;padding:0px;font-weight:normal;font-style:normal;font-size:16px;color:rgba(255,255,255,0.7);' target='_blank' href="<?php echo SITE_NAME . 'contacts/inspiration'; ?>">Inspiration</a>
<div style='margin:0px;padding:0px;clear:both;'></div>
<a style='margin:0px;padding:0px;font-weight:normal;font-style:normal;font-size:16px;color:rgba(255,255,255,0.7);' target='_blank' href="<?php echo SITE_NAME . 'contacts/about_us'; ?>">Team</a>
</div>
</div>

<div style='clear:both;'></div>
<div>
<span class='_increase_fan_base' style='border-bottom:0px solid orange;line-height:100%;font-size:16px;color:rgba(255,255,255,0.7);'>&copy; CouponIsTalking.com 2010-2014 All rights reserved.</span><br/>
</div>

</div>
</div>

<?php

?>
function MakeDragAndDrop(draggableClassName, droppableClassName){var $drag_ele = $("."+draggableClassName);
$drag_ele.css('width', function(){return $(this).css('max-width');});
$drag_ele.parent().css('overflow', 'visible');
$drag_ele.css('z-index', '2');
$drag_ele.draggable({revert: "invalid",helper: 'clone',containment: '#container',scroll: false,
start: function(){},drag: function(){},stop: function(){$(this).draggable('option','revert','invalid');}});

$( "."+droppableClassName ).droppable({accept: "."+draggableClassName,activeClass: "ui-state-hover",hoverClass: "ui-state-active",
	drop: function(event, ui){var draggedObj = ui.draggable;draggedObj.draggable('option','revert',true);
	title_element = draggedObj.find('heading');heading = title_element.text();
	html = title_element.html();start_index = html.indexOf("http");end_index = html.indexOf("')", start_index);
	title_link = html.substring(start_index, end_index);
	
	$draggedObj_parent_box_div = draggedObj.closest('.content_news_box_sidebar');
	$content_image_div = $draggedObj_parent_box_div.find('content_image');
	if (typeof $content_image_div.html() !== 'undefined'){
		img_element = $content_image_div.find('img');image_link = img_element.attr('src');
	}
	$button = $(this).siblings('button');$button_text = $button.text().toLowerCase();
	
	$platform = ""; update_image = 0;
	
	if ($button_text.indexOf('facebook') > 0){$platform == 'facebook';$classname = $(this).attr('class');			
		if ($classname.indexOf('adv_news_item_drop')>=0){$this_div_is = 'news_item';$news_item_drop_div = $(this);
			$image_item_drop_div = $(this).siblings('div.adv_image_item_drop');
		}else if ($classname.indexOf('adv_image_item_drop')>=0){$this_div_is = 'image_item';
			$news_item_drop_div = $(this).siblings('div.adv_news_item_drop');$image_item_drop_div = $(this);
		}		
		if ('news_item' == $this_div_is){$news_item_drop_div.empty();
			$news_item_drop_div.append('<adv_box></adv_box>');
			$news_item_drop_div.find('adv_box').append("<div class='adv_title_customize' onclick=\"OpenInNewTab('"+title_link+"');\">"+ heading +"</div>"); 
			if (typeof image_link != 'undefined'){update_image = 1;}
		}		
		if (update_image == 1 || 'image_item' == $this_div_is){
			if (typeof image_link != 'undefined'){$image_item_drop_div.empty();$image_item_drop_div.append('<adv_box></adv_box>');				
				$image_item_drop_div.find('adv_box').append("<p><img src='"+image_link+"'></img></p>");
		}}		
		if ($news_item_drop_div.find('adv_box').find('black_button').length != 1){
			$news_item_drop_div.find('adv_box').append("<black_button onclick=\"initiate_facebook_share([this]);\">Share On Facebook</black_button>");
			$news_item_drop_div.find('adv_box').append("<div class='customize_title'><label>Type below to tell why do you like this product.</label><textarea onkeypress='update_title(this);'/></div>");
		}
	}else if ($button_text.indexOf('reddit') > 0){$platform == 'reddit';
		$(this).empty();$(this).append('<adv_box></adv_box>');
		$(this).find('adv_box').append("<div class='adv_title_customize' onclick=\"OpenInNewTab('"+title_link+"');\">"+ heading +"</div>");
		$(this).find('adv_box').append("<black_button onclick=\"initiate_reddit_share([this]);\">Share On Reddit</black_button>");
		$(this).find('adv_box').append("<div class='customize_title'><label>Type below to update title of the sharing link.</label><textarea onkeypress='update_title(this);'/></div>");
		$(this).find('adv_box').append("<div class='customize_subreddit'><label>Pick a subreddit topic below.</label>"+
			"<select><option value='TECHNOLOGY' selected>technology</option><option value='SCIENCE'>science</option>"+
				"<option value='PICS'>pics</option><option value='FUNNY'>funny</option><option value='GAMING'>gaming</option>"+
				"<option value='ASKREDDIT'>askreddit</option><option value='WORLDNEWS'>worldnews</option>"+
				"<option value='NEWS'>news</option><option value='VIDEOS'>videos</option></select></div>"
		);
	}else if ($button_text.indexOf('imgur') > 0){$platform == 'imgur';
	 if (typeof image_link != 'undefined'){$(this).empty();$(this).append('<adv_box></adv_box>');$(this).find('adv_box').append("<p><img src='"+image_link+"'></img></p>");} 
	 else{s_e_m("Imgur is a platform to share images. Drag something that has an image that you would like to share.");}
	}
	$(this).addClass( "ui-state-highlight" );
  }
});

}

function update_video_in_ad(image_link, $this){var $image_item_drop_div=$('div.adv_image_item_drop');var $oct=$('div._hidden_octype').text().trim();
var $play_button_url=$S_N+"img/video_play_button.png";
if($this && ('fb_like_video'==$oct)){var $fou = $this.parent().find('div.__fou').text();
var $foid = $this.parent().find('div.__foid').text();
$image_item_drop_div.html("<adv_box><p><img style=\"cursor:pointer;background: url('"+image_link+"') center no-repeat;\" alt='see on facebook' src=\""+$play_button_url+"\" onclick=\"OpenInNewTab('"+$fou+"');\"></img></p></adv_box>");
$('div.adv_foinfo').find('div._likeobjurl').text($fou);$('div.adv_foinfo').find('div._likeobjid').text($foid);
}else{$image_item_drop_div.html("<adv_box><p><img src='"+image_link+"'></img></p></adv_box>");}

}

function update_image_in_ad(image_link, $this){
var $image_item_drop_div = $('div.adv_image_item_drop');var $oct = $('div._hidden_octype').text().trim();
if($this && ('fb_like_pic' == $oct)){var $fou = $this.parent().find('div.__fou').text();var $foid = $this.parent().find('div.__foid').text();
 $image_item_drop_div.html("<adv_box><p><img style='cursor:pointer;' alt='see on facebook' src='"+image_link+"' onclick=\"OpenInNewTab('"+$fou+"');\"></img></p></adv_box>");
 $('div.adv_foinfo').find('div._likeobjurl').text($fou);$('div.adv_foinfo').find('div._likeobjid').text($foid);
}else{$image_item_drop_div.html("<adv_box><p><img src='"+image_link+"'></img></p></adv_box>");}		

}

function update_news_in_ad(heading, title_link, $button){
$content_news = $button.siblings('content_news');
if (0==$content_news.length){$content_news=$button.find('content_news');}
$desc=$content_news.first().text();
var $news_item_drop_div=$('div.adv_news_item_drop');	
$news_item_drop_div.html("<adv_box><div class='adv_title_customize' onclick=\"OpenInNewTab('"+title_link+"');\">"+ heading +"</div><div class='news_title' style='display:none;'>"+heading+"</div><div class='news_link' style='display:none;'>"+title_link+"</div><div class='news_desc' style='display:none;'>"+$desc+"</div></adv_box>");
$news_item_drop_div.css('display', 'block');	
}

function update_title($input_element){var $brand_promote=$input_element.closest('.brand_promote');
$adv_box=$brand_promote.find('.adv_news_item_drop').find('adv_box');$title_div = $adv_box.find('.adv_title_customize');
$entered_val=$input_element.val();$trimmed_val=$.trim($entered_val);
if ($trimmed_val!=""){$title_div.text($entered_val);}else{$title_div.text("Write oneline about this company.");}	
}


function initiate_reddit_share($myp){ var share_button=$myp[0];$adv_box=$(share_button).closest('adv_box');
$new_val_entered=$.trim($adv_box.find('textarea').val());$title=$new_val_entered;
$oc_id=$adv_box.closest('.brand_promote').find('.hidden_oc_id').text();
$subreddit=$adv_box.find('select').find(":selected").val();
// extract link
html=$adv_box.html();start_index=html.indexOf("http");end_index=html.indexOf("')", start_index);
$link=html.substring(start_index, end_index);
if ($title==""){s_e_m("Type in the inputarea to customize title of the link. This helps us propogate your own thoughts, for e.g. what makes you like this company.", 0 ,0);return;} 
var $p=[];$p['scn']="share_on_reddit_after_confirmed_login";$p['sp']=[];
$p['sp']["title"]=$title;$p['sp']["oc_id"]=$oc_id;$p['sp']["link"]=$link;$p['sp']["subreddit"]=$subreddit;
$p['sp']['scn']="s_s_m";$p['sp']['sp']="Thanks for sharing it on reddit!";
$p['sp']['ecn']="s_e_m";$p['sp']['ep']="Sorry, we encountered an issue while sharing this on Reddit. Please let us know about it, so that we can resolve it for better experience of all. Also, please share the link at reddit.com and then come back here and update the direct link to the 'comments page of your share'.";

$p['ecn']="show_reddit_login_form";$p['ep']=[];
$p['ep']['scn']="initiate_reddit_share";$p['ep']['sp']=myparams;
$p['ep']['ecn']="s_e_m";$p['ep']['ep']="Sorry, we couldn't log you in. Please ensure that your reddit username and password is correct.";
confirm_reddit_login_then_share($p);
}

function share_on_reddit_after_confirmed_login($myp){
var scn = $myp['scn'];var ecn = $myp['ecn'];var sp = $myp['sp'];var ep = $myp['ep'];
var $title = $myp["title"];var $link = $myp["link"];var $oc_id = $myp["oc_id"];var $subreddit = $myp["subreddit"];

$.ajax({type:"POST",data:{title:$title, link:$link, oc_id:$oc_id, subreddit:$subreddit},url: $S_N+"oc_responses/share_on_reddit/",
	success : function(data) {if (data == 1){sc = window[scn];sc(sp);}else{ec = window[ecn];ec(ep);}},
	error : function() {s_e_m("Oops, there was some problem while sharing on reddit. If this problem persists, let us know so that we can resolve it with high priority for better experience of all.");} 
});
	
}

function confirm_reddit_login_then_share($myp){var scn=$myp['scn'];var ecn=$myp['ecn'];var sp=$myp['sp'];var ep=$myp['ep'];	
$.ajax({type:"POST",data:{},url:$S_N+"users/is_user_reddit_loggedin/",
	success:function(data){if (1==data){sc=window[scn];sc(sp);}else{ec=window[ecn];ec(ep);}},
	error:function(){s_e_m("Oops, there was some problem while sharing on reddit. If this problem persists, let us know so that we can resolve it with high priority for better experience of all.");}
});

}
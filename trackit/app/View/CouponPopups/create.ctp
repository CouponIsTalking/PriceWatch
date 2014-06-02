<?php
echo $this->Html->css('coup_popup');
echo $this->Html->css('colpick/colpick');
echo $this->Html->script('colpick/colpick');
echo $this->Html->script('fontpick/fontpick');
?>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

<style type='text/css'>
*._design_block{
	margin:15px;
	background-image:url('<?php echo SITE_NAME.'img/white_tex_small.jpg';?>');
	background-repeat:repeat;
	width:400px;
	min-height:50px;
	height:auto;
	padding:3px 5px 3px 5px;
}

*._design_part_block{
	display:none;
}

*._design_name{
	color: rgba(0,0,0,0.8);
	font-family: Helvetica sans-serif;
	font-size:20px;
	border-bottom:3px solid rgba(0,0,0,0.6);
	padding:3px 5px 3px 5px;
	cursor:pointer;
}

*._design_part_name{
	color: rgba(0,0,0,0.8);
	font-family: Helvetica sans-serif;
	font-size:18px;
	border-bottom:3px solid transparent;
	padding:10px 15px 10px 15px;
	cursor:pointer;
}
*._design_part_name:hover{
	/*border-bottom:3px solid rgba(0,0,0,0.6);*/
	font-style:italic;
}

*._show_sel{
	margin-top:10px;
	margin-bottom:10px;
}

*._signup_btn{
	color:rgba(255,255,255,0.8);
	border-bottom: 5px solid transparent;
	cursor:pointer;
}

*._signup_btn:hover{
	border-bottom: 5px solid rgba(255,255,255,0.8);
	font-style:italic;
}

</style>

<script type='text/javascript'>

function _create_btn_click(){
	$t = $('.ccard_title').find('textarea').val();
	$d = $('.ccard_desc').find('textarea').val();
	$mv = $('select.total_visit_sel').val();
	$.ajax({ type: 'POST', data:{'title':$t,'desc':$d,'max_visit':$mv},
	url: $S_N+'ccards/addin',
	success:function(data){$d=IsJsonString(data);
		if ($d['s']){
			//s_s_m('Your punchcard is created. You can send you punchcard to your customers using their email.');
			moveTo($S_N+"ccards/view/"+$d['id']);
		}
		else{s_s_m($d['m']);}
	},
	error:function(data){s_s_m('Unknown error occured');}
	});
}

function _update_max_visits($this){
	$max_visits = $this.val();
	
	$new_visits_html = "";
	for ($i=0;$i<$max_visits;$i++)
	{
		$circle_class = "";
		if ($i+1 != $max_visits){
			$circle_class = "ccard_eachvisit_circle";
		}else{
			$circle_class = "ccard_lastvisit_circle";
		}
		
		$circle_visited_class = "ccard_visit_circle_noclass";
		
		if (0==$i%5){
		$new_visits_html = $new_visits_html
		+"<div class='fivevisitgrp'>"
		+"";
		}
		
		$new_visits_html = $new_visits_html
		+"<div class='ccard_eachvisit'>"
		+"	<span class='visit_num'>"+($i+1)+"</span>"
		+"	<div class=\""+$circle_class+" "+$circle_visited_class+"\" onclick='_oncircle_click($(this));'></div>"
		+"	<div class='ccard_eachvisit_note' onclick='_note_icon_click($(this));'>"
		+"		<div class='arrow_up'></div>"
		+"		<div class='note_visible'>0</div>"
		+"	</div>"
		+"	<div class='ccard_eachvisit_detail'></div>"
			
		+"</div>"
		+"";
		if (0==($i+1)%5){
			$new_visits_html = $new_visits_html
			+"<textarea class='ccard_notebox' maxlength='45' onfocus='_notebox_click($(this));' disabled>Note box.</textarea>"
			+"<div style='clear:both'></div>"
			+"<span class='ccard_notebox_savebtn'>Save</span>"
			+"";
		}
		
		if (0==($i+1)%5){
		$new_visits_html = $new_visits_html
		+"</div>"
		+"";
		}
	}
	
	$('.ccard_visits').html($new_visits_html);
}

function _oncircle_click($this){
	//ajaxi call
	$visit_num = $this.parent().find('.visit_num').text().trim();
	$visited=$this.hasClass('ccard_visit_circle_visited');
	$visited_now = $this.hasClass('ccard_visit_circle_visitednow');
	if($visited){return;}
	else if ($visited_now){ 
		$this.removeClass('ccard_visit_circle_visitednow')
	}else{
		$this.addClass('ccard_visit_circle_visitednow');
	}
}

function _notebox_click($this){
	$this.parent().find('.ccard_notebox_savebtn').fadeIn('fast');
}

function _note_icon_click($this){
	var $nv = $this.find('.note_visible').text().trim();
	$('.note_visible').text('0');
	$('.arrow_up').css('opacity','0');
	$('.ccard_notebox').css('opacity',0.2);
	$('.ccard_notebox').attr('disabled', 'disabled');
	$('.ccard_notebox_savebtn').fadeOut('fast');
	if ('0'==$nv){
		$this.find('.note_visible').text('1');$this.css('opacity',1);
		$this.find('.arrow_up').css('opacity','1');
		$this.closest('.fivevisitgrp').find('.ccard_notebox').css('opacity',1);
		$this.closest('.fivevisitgrp').find('.ccard_notebox').removeAttr('disabled');
		}
	else if ('1'==$nv){$this.find('.note_visible').text('0');}
}

function RunOnLoad(){

	$('._design_name').click(function(){$(this).parent().find('._design_part_block').slideToggle('slow');});

}

function _update_fb_like_url_in_card($this){
	
	//$('.ccard_holder').find('.fb-like').prop('data-href', $this.val());
}

</script>

<?php
	$card_title = "Buffett Club Card";
	$card_desc = "$1 off of the 10th visit";
	$max_visits = 10;
	$next_visit = 3;
?>
<div>
<div>
<span style='color:rgba(0,0,0,0.5);padding:15px;padding-left:0px;border-bottom:3px solid rgba(0,0,0,0.3);font-size:25px;font-style:Helvetica sans-serif;'>
Design your coupon popup
</span>
<br/><div style='height:20px;'></div>
<span style='padding:0px;font-size:16px; width:600px;color:rgba(0,0,0,0.8);'>
Once you design this popup, you will get a single line of code to embed coupon popup in your website.
</span>
</div>
<br/><br/><br/>
<div class='_card_and_design_holder'>
<div class='ccard_holder' style='float:left;'>
<div class='ccard_margin'>
<div class='ccard_pad'>
<div class='ccard_ccard'>
<span class='ccard_title'><textarea maxlength='40' rows='1' class='trans_edit title_edit'>Coupon Title (e.g. Get $5 off! )</textarea></span>
<div style='clear:both'></div>
<div class='ccard_desc'><textarea maxlength='150' rows='3' class='trans_edit desc_edit'>One or two line of description about this offer</textarea></div>

<div class='ccard_social_links'>
<div class='ccard_btn'>
	<div class='_fb_like_btn' onclick='_replace_social_with_code();'>
	<div class="fb-like" data-href="http://www.couponistalking.com" data-layout="standard" data-action="like" data-show-faces="true" data-share="false"></div>
	</div>
</div>
<div class='ccard_btn'>
	<div class='_tw_like_btn' onclick='_replace_social_with_code();'>
		<iframe allowtransparency="true" frameborder="0" scrolling="no" src="//platform.twitter.com/widgets/follow_button.html?screen_name=hemant456" style="width:300px; height:20px;"></iframe>
	</div>
</div>
<div class='ccard_btn'>
	<div class='_gplus_like_btn' onclick='_replace_social_with_code();'>
		<g:plusone data-href="http://www.couponistalking.com" data-annotation='none'></g:plusone>
	</div>
</div>
<!--div class='ccard_btn'>
	<div class='_fb_like_btn'>
	</div>
</div-->
<div class='ccard_signup_box'>
	<div><span class='_signup_btn' onclick='_replace_social_with_code();'>Signup with your email</span></div>
	<input type='text' class='_email' style='margin-top:5px;font-size:14px;min-width:100px;' value='Email..'>	
</div>

</div>

<div style='clear:both;'></div>

</div>
</div>
</div>
</div>

<div class='_all_design_blocks' style='margin-left:100px;float:left;'>

<div class='_design_block'>
<div class='_popup_border_designer'>
<span class='_design_name'>Design border</span>
<div class='_design_part_block'>
<div class='__size_picker'>
<div class='_font_size' onclick="sizepick($(this),{'onchange':function(){alert('hi');}});">
<div class='_design_part_name'>Change top border size</div>
</div>
<div class='_show_sel'></div>
</div>
</div>
</div>
</div>

<div class='_design_block'>
<div class='_background_picker'>
<span class='_design_name'>Change Background</span>
<div class='_design_part_block'>
<div class='_bg_color_picker' onclick='$(this).colpick();'>
<div class='_design_part_name'>Set color</div>
<div class='_bg_color_val'></div>
</div>

<div class='_bg_opacity_picker'>
<div class='_design_part_name'>Set transparency</div>
<select class='_bg_opacity_select'>
<option value='0'>0</option>
<option value='0.05'>0.05</option>
<option value='0.10'>0.10</option>
<option value='0.15'>0.15</option>
<option value='0.20'>0.20</option>
<option value='0.25'>0.25</option>
<option value='0.30'>0.30</option>
<option value='0.35'>0.35</option>
<option value='0.40'>0.40</option>
<option value='0.45'>0.45</option>
<option value='0.50'>0.50</option>
<option value='0.55'>0.55</option>
<option value='0.60'>0.60</option>
<option value='0.65'>0.65</option>
<option value='0.70'>0.70</option>
<option value='0.75'>0.75</option>
<option value='0.80'>0.80</option>
<option value='0.85'>0.85</option>
<option value='0.90'>0.90</option>
<option value='0.95'>0.95</option>
<option value='1.00'>1.00</option>
</select>
</div>
</div>
</div>
</div>


<div class='_design_block'>
<div class='_title_designer'>
<span class='_design_name'>Change title design</span>
<div class='_design_part_block'>
<div class='_title_font_color' onclick='$(this).colpick();'>
<div class='_title_font_color_val'></div>
</div>
<div class='_font_style_picker'>
<div class='_design_part_name'>Change Font Style</div>
<select class='_font_style_select'>
<option value='normal'>normal</option>
<option value='italic'>italic</option>
<option value='bold'>bold</option>
</select>
</div>

<div class='_size_picker'>
<div class='_font_size' onclick="sizepick($(this),{'onchange':function(){alert('hi');}});">
<div class='_design_part_name'>Change Font Size</div>
</div>
<div class='_show_sel'></div>
</div>

<div class='_font_family_picker'>
<div class='_font_family' onclick="fontfamilypicker($(this),{'onchange':function(){alert('hi');}});">
<div class='_design_part_name'>Change Font</div>
</div>
<div class='_show_sel'></div>
</div>
</div>
</div>
</div>

<div class='_design_block'>
<div class='_desc_designer'>
<span class='_design_name'>Change description design</span>

<div class='_design_part_block'>
<div class='_desc_font_color' onclick='$(this).colpick();'>
<div class='_desc_font_color_val'></div>
</div>
<div class='_font_style_picker'>
<div class='_design_part_name'>Change Font Style</div>
<select class='_font_style_select'>
<option value='normal'>normal</option>
<option value='italic'>italic</option>
<option value='bold'>bold</option>
</select>
</div>

<div class='_size_picker'>
<div class='_font_size' onclick="sizepick($(this),{'onchange':function(){alert('hi');}});">
<div class='_design_part_name'>Change Font Size</div>
</div>
<div class='_show_sel'></div>
</div>

<div class='_font_family_picker'>
<div class='_font_family' onclick="fontfamilypicker($(this),{'onchange':function(){alert('hi');}});">
<div class='_design_part_name'>Change Font</div>
</div>
<div class='_show_sel'></div>
</div>
</div>
</div>
</div>


<div class='_design_block'>
<div class='_design_name'>Select what you want to add to the popup</div>
<div style='font-size:16px;'>
<div style='clear:both;'>
<input style='min-height:0px;margin:2px;padding:0px;' type='checkbox' name='_is_fb_like' value='' onchange="$(this).parent().find('._link').slideToggle('slow');_set_fb_like_btn();">Add facebook like<br/>
<div class='_link' style='margin-bottom:30px;display:none;'>Facebook page to like<input type='text' value='' onkeypress='_update_fb_like_url_in_card($(this));'></input></div>
</div>
<div style='clear:both;'><input style='min-height:0px;margin:2px;padding:0px;' type='checkbox' name='_is_tweet_follow' value='' onchange="$(this).parent().find('._link').slideToggle('slow');_set_tw_follow_btn();">Add twitter follow<br/>
<div class='_link' style='margin-bottom:30px;display:none;'>Twitter page to follow<input type='text' value=''></input></div>
</div>
<div style='clear:both;'><input style='min-height:0px;margin:2px;padding:0px;' type='checkbox' name='_is_gplus_page' value='' onchange="$(this).parent().find('._link').slideToggle('slow');_set_gplus_page_btn();">Add google plus page<br/>
<div class='_link' style='margin-bottom:30px;display:none;'>Google page for google plus one<input type='text' value=''></input></div>
</div>
<div style='clear:both;'><input style='min-height:0px;margin:2px;padding:0px;' type='checkbox' name='_is_signup' value='' onchange="$(this).parent().find('._link').slideToggle('slow');_set_email_signup_btn();">Add email signup<br/>
<div class='_link' style='margin-bottom:30px;display:none;'>Newsletter signup added</div>
</div>
</div>
</div>
</div>

</div><!-- all designs ends-->
</div><!-- design and card holder ends-->

<div style='clear:both'></div><br/><br/>
<span class='create_ccard_btn' onclick='_create_btn_click();'>Create CouponPopup</span>

<?php

?>
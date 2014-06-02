<?php
echo $this->Html->css('pcard');
?>

<script type='text/javascript'>

function _save_note($this){
	
	$note_val = $this.parent().find('textarea.pcard_notebox').val();
	$visit_num = $this.closest('.fivevisitgrp').find('._next_note_on').text().trim();
	
	var $ccard_id = $('#_ori_ccard_id').text().trim();
	$.ajax({type:'POST',data:{ccard_id:$ccard_id , visit_num: $visit_num, note_val: $note_val},
	url:$S_N+"pcards/set_ccard_note",
	success:function(data){$d=IsJsonString(data);
		if($d['s']){
			$('.pcard_eachvisit_note').eq(parseInt($visit_num)-1).find('.note_val').text($note_val);
			$this.fadeOut('fast');
		}
		else{s_s_m($d['m']);}},
	error:function(){}	
	});
	
}

function _oncircle_click($this){
	
	$visit_val = -1;
	$visit_num = $this.parent().find('.visit_num').text().trim();
	$visited=$this.hasClass('pcard_visit_circle_visited');
	$visited_now = $this.hasClass('pcard_visit_circle_visitednow');
	if($visited){return;}
	else if ($visited_now){$visit_val = 0;
		$this.removeClass('pcard_visit_circle_visitednow')
	}else{$visit_val = 1;
		$this.addClass('pcard_visit_circle_visitednow');
	}
	
	if (0==$visit_val || 1==$visit_val){
		$ccard_id = $('#_ori_ccard_id').text().trim();
		$.ajax({type:'POST',data:{ccard_id:$ccard_id , visit_num: $visit_num, visit_val: $visit_val},
		url:$S_N+"pcards/set_ccard_visit",
		success:function(data){$d=IsJsonString(data);if(!$d['s']){s_s_m($d['m']);}},
		error:function(){}	
		});
	}
}

function _notebox_click($this){
	$this.parent().find('.pcard_notebox_savebtn').fadeIn('fast');
}

function _note_icon_click($this){
	var $nv = $this.find('.note_visible').text().trim();
	$('.note_visible').text('0');
	$('.arrow_up').css('opacity','0');
	$('.pcard_notebox').css('opacity',0.2);
	$('.pcard_notebox').attr('disabled', 'disabled');
	$('.pcard_notebox_savebtn').fadeOut('fast');
	if ('0'==$nv){
		
		$note_val = $this.find('.note_val').text();
		if (undefined == $note_val || "" == $note_val.trim()){$note_val = "Write a note about this visit. Your customers will not see this note.";}
		
		$this.closest('.fivevisitgrp').find('._next_note_on').text($this.closest('.pcard_eachvisit').find('.visit_num').text());
		
		$this.closest('.fivevisitgrp').find('.pcard_notebox').val($note_val);
		$this.find('.note_visible').text('1');$this.css('opacity',1);
		$this.find('.arrow_up').css('opacity','1');
		$this.closest('.fivevisitgrp').find('.pcard_notebox').css('opacity',1);
		$this.closest('.fivevisitgrp').find('.pcard_notebox').removeAttr('disabled');
		}
	else if ('1'==$nv){$this.find('.note_visible').text('0');}
}

function _show_send_form(){
	$f = $('form.send_pcard_form');
	$dval = $f.css('display');
	if ('none'==$dval){
		$f.css('opacity',1);
		//$f.css('display','block');
		$f.hide().fadeIn('fast');
	}else{
		$('form.send_pcard_form').find('.msg').html("");
		$f.fadeOut('fast',function(){$f.css('display','none');});		
	}
}

function _send_pcard_click(){
	var $f = $('form.send_pcard_form');
	var $fn = $f.find('.firstname').val();
	var $ln = $f.find('.lastname').val();
	var $email = $f.find('.email').val();
	var $phone = $f.find('.phone').val();
	var $card_id = $f.find('.card_id').val();
	if (undefined==$email || !validateEmail($email)){
		$('form.send_pcard_form').find('.msg').html("Please provide an email.");
		return;
	}
	
	$('form.send_pcard_form').find('.msg').html("Sending your punchcard ... ");
	
	$.ajax({ type:'POST', data:{'fn':$fn,'ln':$ln,'email':$email,'phone':$phone,'card_id':$card_id}, 
	url: $S_N+"pcards/give",
	success:function(data){ $d=IsJsonString(data);
		if ($d['s']){
			$('form.send_pcard_form').find('.msg').html("Nice, your punchcard is sent. You can access it <a style='color:rgba(0,0,0,0.8);' href=\""+$d['link']+"\">here</a>");
		}else{
			$('form.send_pcard_form').find('.msg').html($d['m']);
		}
	},
	error:function(){s_s_m("A network error occured.");}
	});
}

</script>

<?php

if (!empty($card['Pcard'])){
	$card = $card['Pcard'];
	$card_title = $card['title'];
	$card_desc = $card['desc'];
	$max_visits = $card['total_visits'];
	$next_visit = 0;
}else{
	$card_title = "Sample Club Card";
	$card_desc = "One or two line of description telling what this card is about.";
	$max_visits = 10;
	$next_visit = 0;
}

if ($company_watch){
?>

<span onclick='_show_send_form();' style='cursor:pointer;color:rgba(0,0,0,0.6);border-bottom:2px solid rgba(0,0,0,0.6);font-size:25px;font-style:Helvetica sans-serif;'>
Give this punchcard.
</span>
<br/><br/>
<form class='send_pcard_form' style='width:600px;display:none;'>
<div class='msg' style='font-size:25px;font-style:Helvetica sans-serif;color:#e32;'></div>
<input class='card_id' maxlength='20' type='hidden' value='<?php echo "{$card['id']}";?>'></input>
<label>Firstname</label><input class='firstname' maxlength='20'></input>
<br/><br/>
<label>Lastname</label><input class='lastname' maxlength='20'></input>
<br/><br/>
<label>Email</label><input class='email' maxlength='50'></input>
<br/><br/>
<label>Phone (numbers only, max 10 characters)</label><input class='phone' maxlength='10'></input>
<br/><br/>
<span class='create_pcard_btn' onclick='_send_pcard_click();'>Give Punchcard</span>
<br/><br/><br/>
</form>
<?php
}
?>

<?php
$firstname = $cust_card['PcardCust']['firstname'];
$lastname = $cust_card['PcardCust']['lastname'];
$email = $cust_card['PcardCust']['email'];
$phone = $cust_card['PcardCust']['phone'];
if ($member_info_open){
echo "
<div style='width:630px;background:rgba(116, 113, 113, 0.2);color:rgba(0,0,0,0.8);font-size:20px;font-style:Helvetica sans-serif;'>
<span style='border-bottom:2px solid rgba(0,0,0,0.8);'>Card member info</span><br/>
<div style='font-size:16px;'>
	<div style='padding:5px;'>Name  : {$firstname} {$lastname}</div>
	<div style='padding:5px;'>Email :{$email}</div>
	<div style='padding:5px;'>Phone :{$phone}</div>
</div>
<br/><br/>
</div>
";
}

?>

<div class='pcard_holder'>
<div class='pcard_margin'>
<div class='pcard_pad'>
<div class='pcard_pcard'>
<span class='pcard_title'><?php echo $card_title; ?></span>
<div style='clear:both'></div>
<div class='pcard_desc'><?php echo $card_desc; ?></div>
<div class='pcard_visits'>
	
<?php
	
	if ($company_watch){
		echo "<div style='display:none;'>
		<div id='_ori_card_id'>{$card['id']}</div>
		<div id='_ori_ccard_id'>{$cust_card['PcardCust']['id']}</div>
		</div>";
	
	}
	
	$visit_vnum = array();
	if (!empty($visits)){
		foreach ($visits as $index=>$visit){
			$visit_vnum[$visit['PcardCvisit']['vnum']] = $visit;
		}
	}
	
	for ($i=0;$i<$max_visits;$i++)
	{
		$circle_class = "";
		if ($i+1 != $max_visits){
			$circle_class = "pcard_eachvisit_circle";
		}else{
			$circle_class = "pcard_lastvisit_circle";
		}
		
		$vnum = $i+1;
		$circle_visited_class = "pcard_visit_circle_noclass";
		$note = "";
		if (array_key_exists($vnum, $visit_vnum)){
			if (1==$visit_vnum[$vnum]['PcardCvisit']['visited']){
				$circle_visited_class = "pcard_visit_circle_visited";
			}
			$note = $visit_vnum[$vnum]['PcardCvisit']['note'];
		}
		
		if (0==$i%5){
		echo "
		<div class='fivevisitgrp'>
		";
		}
		
		$onclick_circle_evt = "";
		if ($company_watch){
			$onclick_circle_evt = "_oncircle_click($(this));";
		}
		
		echo "
		<div class='pcard_eachvisit'>
			<span class='visit_num'>{$vnum}</span>
			<div class=\"{$circle_class} {$circle_visited_class}\" onclick='{$onclick_circle_evt}'></div>
			";
			
	   if ($company_watch){
		if(!empty($note)){
		echo "<div class='pcard_eachvisit_note' onclick='_note_icon_click($(this));' style='opacity:1;'>";
		}else{
		echo "<div class='pcard_eachvisit_note' onclick='_note_icon_click($(this));'>";
		}
		echo "
				<div class='arrow_up'></div>
				<div class='note_visible'>0</div>
				<div class='note_val' style='display:none;'>{$note}</div>
			</div>
			";
			}
			
		echo "
			<div class='pcard_eachvisit_detail'></div>
			
		</div>
		";
		if (0==($i+1)%5){
			
			echo "<div style='clear:both;'></div>";
			
			if ($company_watch){
			echo "
			<textarea class='pcard_notebox' maxlength='90' onfocus='_notebox_click($(this));' disabled>Note box.</textarea>
			<div style='clear:both'></div>
			<div class='_next_note_on' style='display:none'></div>
			<span class='pcard_notebox_savebtn' onclick=\"_save_note($(this));\">Save</span>
			";
			}
		}
		
		if (0==($i+1)%5){
		echo "
		</div>
		";
		}
	}
	
?>	


</div>
</div>
</div>
</div>
</div>
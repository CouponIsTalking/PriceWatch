<?php
echo $this->Html->css('pcard');
?>

<script type='text/javascript'>

function _oncircle_click($this){
	//ajaxi call
	$visit_num = $this.parent().find('.visit_num').text().trim();
	$visited=$this.hasClass('pcard_visit_circle_visited');
	$visited_now = $this.hasClass('pcard_visit_circle_visitednow');
	if($visited){return;}
	else if ($visited_now){ 
		$this.removeClass('pcard_visit_circle_visitednow')
	}else{
		$this.addClass('pcard_visit_circle_visitednow');
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
	if (!empty($card)){
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
<label>Email *</label><input class='email' maxlength='50'></input>
<br/><br/>
<label>Phone (numbers only, max 10 characters)</label><input class='phone' maxlength='10'></input>
<br/><br/>
<span class='create_pcard_btn' onclick='_send_pcard_click();'>Give Punchcard</span>
<br/><br/><br/>
</form>

<div class='pcard_holder'>
<div class='pcard_margin'>
<div class='pcard_pad'>
<div class='pcard_pcard'>
<span class='pcard_title'><?php echo $card_title; ?></span>
<div style='clear:both'></div>
<div class='pcard_desc'><?php echo $card_desc; ?></div>
<div class='pcard_visits'>
	
<?php
		
	for ($i=0;$i<$max_visits;$i++)
	{
		$circle_class = "";
		if ($i+1 != $max_visits){
			$circle_class = "pcard_eachvisit_circle";
		}else{
			$circle_class = "pcard_lastvisit_circle";
		}
		
		if ($i+1 < $next_visit){
			$circle_visited_class = "pcard_visit_circle_visited";
		}else{
			$circle_visited_class = "pcard_visit_circle_noclass";
		}
		
		if (0==$i%5){
		echo "
		<div class='fivevisitgrp'>
		";
		}
		
		echo "
		<div class='pcard_eachvisit'>
			<span class='visit_num'>1</span>
			<div class=\"{$circle_class} {$circle_visited_class}\" onclick='_oncircle_click($(this));'></div>
			<div class='pcard_eachvisit_note' onclick='_note_icon_click($(this));'>
				<div class='arrow_up'></div>
				<div class='note_visible'>0</div>
			</div>
			<div class='pcard_eachvisit_detail'></div>
			
		</div>
		";
		if (0==($i+1)%5){
			echo"
			<textarea class='pcard_notebox' maxlength='45' onfocus='_notebox_click($(this));' disabled>Note box.</textarea>
			<div style='clear:both'></div>
			<span class='pcard_notebox_savebtn'>Save</span>
			";
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
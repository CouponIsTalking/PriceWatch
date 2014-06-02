<?php
echo $this->Html->css('pcard');
?>

<script type='text/javascript'>

function _create_btn_click(){
	$t = $('.pcard_title').find('textarea').val();
	$d = $('.pcard_desc').find('textarea').val();
	$mv = $('select.total_visit_sel').val();
	$.ajax({ type: 'POST', data:{'title':$t,'desc':$d,'max_visit':$mv},
	url: $S_N+'pcards/addin',
	success:function(data){$d=IsJsonString(data);
		if ($d['s']){
			//s_s_m('Your punchcard is created. You can send you punchcard to your customers using their email.');
			moveTo($S_N+"pcards/view/"+$d['id']);
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
			$circle_class = "pcard_eachvisit_circle";
		}else{
			$circle_class = "pcard_lastvisit_circle";
		}
		
		$circle_visited_class = "pcard_visit_circle_noclass";
		
		if (0==$i%5){
		$new_visits_html = $new_visits_html
		+"<div class='fivevisitgrp'>"
		+"";
		}
		
		$new_visits_html = $new_visits_html
		+"<div class='pcard_eachvisit'>"
		+"	<span class='visit_num'>"+($i+1)+"</span>"
		+"	<div class=\""+$circle_class+" "+$circle_visited_class+"\" onclick='_oncircle_click($(this));'></div>"
		+"	<div class='pcard_eachvisit_note' onclick='_note_icon_click($(this));'>"
		+"		<div class='arrow_up'></div>"
		+"		<div class='note_visible'>0</div>"
		+"	</div>"
		+"	<div class='pcard_eachvisit_detail'></div>"
			
		+"</div>"
		+"";
		if (0==($i+1)%5){
			$new_visits_html = $new_visits_html
			+"<textarea class='pcard_notebox' maxlength='45' onfocus='_notebox_click($(this));' disabled>Note box.</textarea>"
			+"<div style='clear:both'></div>"
			+"<span class='pcard_notebox_savebtn'>Save</span>"
			+"";
		}
		
		if (0==($i+1)%5){
		$new_visits_html = $new_visits_html
		+"</div>"
		+"";
		}
	}
	
	$('.pcard_visits').html($new_visits_html);
}

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
</script>

<?php
	$card_title = "Buffett Club Card";
	$card_desc = "$1 off of the 10th visit";
	$max_visits = 10;
	$next_visit = 3;
?>

<div>
<span style='color:rgba(0,0,0,0.5);padding:15px;padding-left:0px;border-bottom:3px solid rgba(0,0,0,0.3);font-size:25px;font-style:Helvetica sans-serif;'>
Create Punchcard
</span>
<br/><div style='height:20px;'></div>
<span style='padding:0px;font-size:16px; width:600px;color:rgba(0,0,0,0.8);'>
Once you create a punchcard, you can send it to your customers individually. They will see your punchcard only if you send it to them.
</span>
</div>
<br/><br/><br/>
<div class='pcard_holder'>
<div class='pcard_margin'>
<div class='pcard_pad'>
<div class='pcard_pcard'>
<span class='pcard_title'><textarea maxlength='40' rows='1' class='trans_edit title_edit'>Punch Card Title</textarea></span>
<div style='clear:both'></div>
<div class='pcard_desc'><textarea maxlength='150' rows='3' class='trans_edit desc_edit'>One or two line of description about this card</textarea></div>
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
		
		$circle_visited_class = "pcard_visit_circle_noclass";
		
		if (0==$i%5){
		echo "
		<div class='fivevisitgrp'>
		";
		}
		
		$visit_num = $i+1;
		echo "
		<div class='pcard_eachvisit'>
			<span class='visit_num'>{$visit_num}</span>
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

<div style='clear:both;'></div>
<span class='set_visits' style='margin-left:10px;'>Set total visits</span>
<select class='total_visit_sel' onchange="_update_max_visits($(this));">
<option val='5'>5</option>
<option val='5' selected>10</option>
<option val='5'>15</option>
<option val='5'>20</option>
<option val='5'>25</option>
<option val='5'>30</option>
</select>
</div>
</div>
</div>
</div>

<div style='clear:both'></div><br/><br/>
<span class='create_pcard_btn' onclick='_create_btn_click();'>Create Punchcard</span>

<?php

?>
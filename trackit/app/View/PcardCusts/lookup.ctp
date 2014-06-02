<?php
echo $this->Html->css('pcard');
?>

<script type='text/javascript'>

function _validate_search_form(){
	
	var $f = $('form.search_pcard_form');
	var $email = $f.find('.email').val();
	var $phone = $f.find('.phone').val();
	if ( (undefined==$email || !validateEmail($email))
		&&(undefined==$phone || ""==$phone.trim())
	){
		$('form.search_pcard_form').find('.msg').html("Please provide an email or phone number.");
		event.preventDefault();
		return false;
	}else{
		return true;
	}
}

function _search_pcard_click(){
	var $f = $('form.search_pcard_form');
	var $email = $f.find('.email').val();
	var $phone = $f.find('.phone').val();
	if ( (undefined==$email || !validateEmail($email))
		&&(undefined==$phone || ""==$phone.trim())
	){
		$('form.search_pcard_form').find('.msg').html("Please provide an email or phone number.");
		return;
	}
	
	$('form.send_pcard_form').find('.msg').html("Searching punchcards ... ");
	
	$.ajax({ type:'POST', data:{'email':$email,'phone':$phone}, 
	url: $S_N+"pcard_custs/lookup_op",
	success:function(data){ $d=IsJsonString(data);
		if ($d['s']){
			$('form.search_pcard_form').find('.msg').html("Nice, your punchcard is sent. You can access it <a style='color:rgba(0,0,0,0.8);' href=\""+$d['link']+"\">here</a>");
		}else{
			$('form.search_pcard_form').find('.msg').html($d['m']);
		}
	},
	error:function(){s_s_m("A network error occured.");}
	});
}

</script>

<span onclick='_show_send_form();' style='cursor:pointer;color:rgba(0,0,0,0.6);border-bottom:2px solid rgba(0,0,0,0.6);font-size:25px;font-style:Helvetica sans-serif;'>
Lookup a customer's punchcard.
</span>
<br/><br/>
<form class='search_pcard_form' style='width:600px;display:block;' method='POST' onsubmit="_validate_search_form();" action="<?php echo SITE_NAME . "pcard_custs/lookup"; ?>">
<div class='msg' style='font-size:25px;font-style:Helvetica sans-serif;color:#e32;'></div>

<label>Customer's Email</label><input type='text' name='email' class='email' maxlength='50' value=''></input>
<br/><br/>
<label>Phone (numbers only, max 10 characters)</label><input type='number' name='phone' class='phone' maxlength='10' value=''></input>
<br/><br/>
<input type="submit" value="Submit"></input><!--span class='create_pcard_btn' onclick='_search_pcard_click();'>Search Punchcards</span-->
<br/><br/><br/>
</form>

<?php

if ($is_post){

if (empty($cust_cards)){
	echo "
	<span style='rgba(0,0,0,0.8);font-size:25px; font-style:Helvetica sans-serif;padding:15px;'>
		No punchcards found. 
	</span>";
}
else{
?>
<div class="pcard_custs">
	<h2><?php echo __('Search results'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('Title'); ?></th>
			<th><?php echo $this->Paginator->sort('Description'); ?></th>
	</tr>
	<?php foreach ($cust_cards as $cust_card):
		$cust_card_id = $cust_card['PcardCust']['id'];
		$crdid = $cust_card['PcardCust']['card_id'];
		$title = $cards_key_on_id[$crdid]['Pcard']['title'];
		$desc = $cards_key_on_id[$crdid]['Pcard']['desc'];
	?>
	<tr>
		<td><?php
			$view_link = SITE_NAME . "pcards/customer_card/{$cust_card_id}";
			echo "<span onclick=\"moveTo('{$view_link}')\" style='border-bottom:2px solid rgba(0,0,0,0.8);cursor:pointer;'>{$title}</span>"; 
			?>&nbsp;</td>
		<td style='max-width:400px;'><?php echo h($desc); ?>&nbsp;</td>
		
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<?php
}
} // ispost
?>
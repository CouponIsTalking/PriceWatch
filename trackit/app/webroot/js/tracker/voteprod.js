function vote_product_by_id($p){
$pid=$p['pid'];$lq=$p['lq'];$wq=$p['wq'];$oq=$p['oq'];

$('#prodvote').css('z-index',""+(parseInt($('._prodvote_frame').css('z-index'))+1));
$('#prodvote').find('input.__prod_id').val($pid);
$('.__prodvote_form').show();
if(1==$lq || '1'==$lq){$('.__prodvote_form .__likevote .__vote_this_item_button').addClass('pressed');}
else{$('.__prodvote_form .__likevote .__vote_this_item_button').removeClass('pressed');}
if(1==$wq || '1'==$wq){$('.__prodvote_form .__wantvote .__vote_this_item_button').addClass('pressed');}
else{$('.__prodvote_form .__wantvote .__vote_this_item_button').removeClass('pressed');}
if(1==$oq || '1'==$oq){$('.__prodvote_form .__ownvote .__vote_this_item_button').addClass('pressed');}
else{$('.__prodvote_form .__ownvote .__vote_this_item_button').removeClass('pressed');}

}

function prodvote($btn,$vt){
$pid=$btn.closest('#prodvote').find('input.__prod_id').val();
if($btn.hasClass('pressed')){$nv='no';}else{$nv='yes';}

$('._prodvote_frame').find('.prodvote_msg').text("Updating ...");

$.ajax({
	type: 'POST',url: $S_N + "prod_votes/vote",
	data:{vote_type:$vt, new_vote:$nv, p_id: $pid},
	success:function(data){
		var $d = IsJsonString(data);
		if(!$d){$('._prodvote_frame').find('.prodvote_msg').text("A network error occured.");}
		else if (0==$d['ul']){show_ulf();}
		else if (!$d['s']){var m="";if ($d['m']){m=$d['m'];}else{m="A network error occured.";}
			$('._prodvote_frame').find('.prodvote_msg').text(m);
		}else if($d['s']){
			if('yes'==$nv){$btn.addClass('pressed');
				$('span._p'+$pid+'_prod_votes').find('.'+$vt).val('1');
			}else if('no'==$nv){$btn.removeClass('pressed');
				$('span._p'+$pid+'_prod_votes').find('.'+$vt).val('-1');
			}
			$('._prodvote_frame').find('.prodvote_msg').text("Updated!");
			
		}
	},
	error: function(data){
	}
});

}
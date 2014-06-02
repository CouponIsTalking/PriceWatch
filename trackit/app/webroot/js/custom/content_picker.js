function disp_ccp($this)
{
	$ccp_parent = $this.closest('div._selector');
	if (0 == $ccp_parent.length) { $ccp_parent = $this.closest('div._has_ccp'); }
	$ccp_div_html = $ccp_parent.find('div.ccp').html();
	
	$ele = $("<temp></temp>");
	$ele.append($($ccp_div_html));
	
	show_success_message($ele);
	
	fit_to_inner_content('div.success_msg');
	$('div.success_msg').css('top', '10%');
	$('div.success_msg').css('left', '10%');
	$('div.success_msg').css('height', '70%');
	$('div.success_msg').css('width', '70%');
	//$('div.success_msg').draggable('disable');
	
	_init_content_selection();
}

function _chosen_content()
{
	content_map = {};
	temp_map = {};
}

function _select_all($btn)
{
	$select_btns = $btn.closest('div.success_msg').find('._selected');
	$.each($select_btns, function(key, value){ value.click()});
	
	$select_btns = $btn.closest('div.success_msg').find('._to_select');
	$.each($select_btns, function(key, value){ value.click()});
}

function _deselect_all($btn)
{
	$select_btns = $btn.closest('div.success_msg').find('._selection_buttons').find('._to_select');
	$.each($select_btns, function(key, value){ value.click()});
	
	$select_btns = $btn.closest('div.success_msg').find('._selection_buttons').find('._selected');
	$.each($select_btns, function(key, value){ value.click()});
}

function _clear_content_selection()
{
	window['_chosen_content'].temp_map = {};
	window['_chosen_content'].content_map = {};
	$select_btns = $('div.success_msg').find('._selection_buttons').find('._to_select');
	$.each($select_btns, function(key, value){ value.click()});
	
	$select_btns = $('div.success_msg').find('._selection_buttons').find('._selected');
	$.each($select_btns, function(key, value){ value.click()});
}

function _init_content_selection()
{
	if ('undefined' == typeof window['_chosen_content'].content_map){window['_chosen_content'].content_map = {};}
	window['_chosen_content'].temp_map = window['_chosen_content'].content_map;	
}

function _finish_content_selection($click_ele, $save)
{
	if($save)
	{
		// order is important here
		window['_chosen_content'].content_map = window['_chosen_content'].temp_map;
		$new_html = $click_ele.closest('div.success_msg').find("temp").html();
		$click_ele.closest('div.success_msg').find('close_button').click();
		$('div.ccp').html($new_html);
	}
	else
	{
		$click_ele.closest('div.success_msg').find('close_button').click();
	}
}

function _build_comma_separated_content_str()
{
	var $cm_str = "";
	
	if ('undefined' != typeof window['_chosen_content'].content_map)
	{
		var $cm = window['_chosen_content'].content_map;
		for ($cid in $cm)
		{
			if (0 == $cm[$cid])
			{
				continue;
			}
			else if ("" == $cm_str)
			{
				$cm_str = $cid.toString();
			}
			else
			{
				$cm_str = $cm_str + "," + $cid.toString();
			}
		}
	}
	
	return $cm_str;
}

function _company_content_picker_ctp_select_item($click_ele, $content_id, $selected)
{
	if (typeof window['_chosen_content'].content_map == 'undefined')
	{
		window['_chosen_content'].content_map = {};
	}
	
	window['_chosen_content'].temp_map[$content_id] = $selected;
	
	$parent = $click_ele.closest('._selection_buttons');
	
	if (1 == parseInt($selected))
	{
		$parent.find('._selected').show();
		$parent.find('._to_select').hide();
		$parent.closest('div.content_news_box_sidebar').css('background-color', '#CCFFCC');
	}
	else
	{
		$parent.find('._selected').hide();
		$parent.find('._to_select').show();		
		$parent.closest('div.content_news_box_sidebar').css('background-color', '');
	}
	return;
}
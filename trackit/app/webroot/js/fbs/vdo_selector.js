function __select_obj_build_obj_selector($this, $type)
{
	$box = $this.closest('div.content_news_box_sidebar');
	if ('vdo' == $type)
	{
		$content_image_x = $box.find('content_image_x');
		$image_url = $content_image_x.find('img').prop('src');
		$title = $content_image_x.find('img').prop('title');
		$fbobjid = $content_image_x.find('span.fbobjid').text().trim();
		$json_obj_detail = $content_image_x.find('textarea.json_obj_detail').text().trim();
		
		set_objs_as_imported($fbobjid, $json_obj_detail);
		
		$content_image_x.find('input').val('1');
		$this.css('display', 'none');
		$this.siblings('._selected').first().css('display', 'block');
		$box.css('background-color', 'rgb(65, 150, 25)');
	}
}
function __deselect_obj_build_obj_selector($this, $type)
{
	$box = $this.closest('div.content_news_box_sidebar');
	
	if ('vdo' == $type)
	{
		var $content_image_x = $box.find('content_image_x');
		$fbobjid = $content_image_x.find('span.fbobjid').text().trim();
		
		clear_obj_as_imported($fbobjid);
		
		$box.find('content_image_x').find('input').val('0');
		$this.css('display', 'none');
		$this.siblings('._to_select').first().css('display', 'block');
		$box.css('background-color', 'white');
	}
}

function show_vdo_import_picker($params){$objs = $params['objs']['data'];
	if (0==$objs.length){cmn_s_m_f_r_f(0, "No videos found!", clear_imported_objs, 0); return;}
	
	$next_url = $params['objs']['paging']['next'];
	$prev_url = $params['objs']['paging']['previous'];
	
	$cur_next_time = $('div.success_msg').find('._next_url').siblings('._qtime').first().html();
	if (undefined != $cur_next_time)
	{
		$('div.success_msg').find('._prev_url').siblings('._qtime').first().html($cur_next_time);
	}
	
	
	$html = "<div style=''>" 
	+ "<div class='content_picker_msg'></div>"
	+"<div class='content_roll_left' style=\"overflow:scroll;\">"
	+"<div style=\"overflow:hidden;\">"
	+"";

		for($i=0;$i<$objs.length;$i++)
		{
			$json_vdo_detail = JSON.stringify($objs[$i]);
			$fbobjid = $objs[$i]['id'];
			$fullpath = $objs[$i]['picture'];
			$title = '';
			
			
			$html = $html
			+"<div class='content_news_box_sidebar' style='width:25%;height:25%;'>"
			+"<heading style='font-size:12px;'></heading>"
			+"<div class='_selection_buttons' style='margin:2px;'>"
			+"	<div class='_to_select' onclick=\"__select_obj_build_obj_selector($(this), 'vdo');\">"
			+"		<black_button>Select.</black_button>"
			+"	</div>"
			+"	<div class='_selected' style=\"display:none;\" onclick=\"__deselect_obj_build_obj_selector($(this), 'vdo');\">"
			+"		<green_button style=\"border:'';\">X.</green_button>"
			+"	</div>"
			+"</div>"
			+"<content_image_x>"
			+	"<img title=\""+$title+"\" style='max-width:90%; max-height:90%;' src='"+$fullpath+"' onclick=\"update_image_in_ad('', 0);\"></img>"
			+	"<textarea class='json_obj_detail' style='display:none'>"+ $json_vdo_detail + "</textarea>"
			+	"<span class='fbobjid' style='display:none'>"+$fbobjid+"</span>"
			+	"<input style='display:none;' value='0'>"
			+"</content_image_x>"
			+"<bottom></bottom>"
			+"</div>";
		}
	
	$html = $html
	+"<div style='color:white; font-style:italic; left:30%; top:0%; position:absolute; z-index:20;cursor:pointer;' onmouseout=\"$(this).css('color', 'white');$(this).css('background-color', 'black');\" onmouseover=\"$(this).css('background-color', 'white');$(this).css('color', 'grey');\" onclick=\"update_images_from_query_url($(this).find('div._prev_url').text(), $(this));\">prev&lt;&lt;<div class='_prev_url' style='display:none;'>"+$prev_url+"</div><div class='_qtime' style='display:none;'></div></div>"
	+"<div style='color:white; font-style:italic; right:30%;top:0%; position:absolute; z-index:20;cursor:pointer;' onmouseout=\"$(this).css('color', 'white');$(this).css('background-color', 'black');\" onmouseover=\"$(this).css('background-color', 'white');$(this).css('color', 'grey');\" onclick=\"update_images_from_query_url($(this).find('div._next_url').text(), $(this));\">next&gt;&gt;<div class='_next_url' style='display:none;'>"+$next_url+"</div><div class='_qtime' style='display:none;'>"+$objs[$objs.length-1].created_time+"</div></div>"
	+"</div>"
	+"<div style='clear:both'></div>"
	+"</div>"

	+"<div style='float:left' onclick=\"$(this).closest('div.success_msg').find('._to_select').click();\">"
	+"<black_button>Select All</black_button>"
	+"</div>"

	+"<div style='float:left' onclick=\"$(this).closest('div.success_msg').find('._selected').click();\">"
	+"<black_button>Un-Select All</black_button>"
	+"</div>"

	+"<div style='float:left' onclick=\"batch_obj_update($(this), 'vdo');\">"
	+"<black_button>Import and Continue</black_button>"
	+"</div>"

	+"<div style='float:left' onclick=\"clear_imported_objs(0);\">"
	+"<black_button>Exit</black_button>"
	+"</div>"
	
	+"<div class='_zoom_pic' style='display:none;'></div>"
	
	+"<div class='_selected_pics' style='display:none;'></div>"
	+"</div>";	
	
	
	cmn_s_m_f_r_f(0, $html, clear_imported_objs, 0);
}

function load_from_fb_vdo_resp($presp, $qurl){var $p=[];
if(0==$presp['data'].length){return;}
$p['photos'] = $presp;$p['base_uri'] = $qurl;show_image_import_picker($p);
}

function update_vdos_from_query_url($qurl, $this){var $qtime = $this.find('._qtime').first().html();
var $full_quri = $qurl ;//+ "&until=" + $qtime;

$.ajax({type: 'GET',url: $full_quri,
	success: function(photos_resp){
		if (!photos_resp || photos_resp.error){}
		else{remove_cb_clk();load_from_fb_pic_resp(photos_resp, $qurl);}
	},error: function(){}
});

FB.api($full_quri, function(photos_resp){
	if (!photos_resp || photos_resp.error){}else{
		remove_cb_clk();load_from_fb_pic_resp(photos_resp, $qurl);
	}});
	
}

function clear_imported_objs(p){window.imported_objs = {};}

function set_objs_as_imported($fbobjid, $json_obj_detail){
	if (undefined == window.imported_objs){window.imported_objs = {};}
	var $obj_details=IsJsonString($json_obj_detail);
	if ($obj_details){window.imported_objs[$fbobjid] = {'od' : $obj_details};}
}

function clear_obj_as_imported($fbobjid){if (undefined != window.imported_objs){delete window.imported_objs[$fbobjid];}}

function batch_obj_update($this, $type){
$batch_obj_update_uri = $S_N+"contents/batch_obj_import_from_fb";
$msg_box = $this.closest('div.success_msg').find('div.content_picker_msg');

if (undefined==window.past_updates){window.past_updates={};}
new_updates={};
for (x in window.imported_objs){if(undefined==window.past_updates[x]){new_updates[x]=window.imported_objs[x];}}

$msg_box.html("Importing ... just a sec ...");
show_loading_image();

$.ajax({url: $batch_obj_update_uri,type: 'POST',data : {objs: JSON.stringify(window.imported_objs), type: $type},
	success: function(data){ hide_loading_image();data = IsJsonString(data);
		if(data&&data.success){$msg_box.html("Imported successfully.");
			for (x in new_updates){window.past_updates[x] = new_updates[x];}
		}else{$msg_box.html("An error occured while importing.");$msg_box.html(data.msg);}
	},error: function(){hide_loading_image();cb_clk();$msg_box.html("Oops. Something went wrong.");}
});	

}
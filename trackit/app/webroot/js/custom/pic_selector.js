function __select_img_build_picture_selector($this)
{
	$box = $this.closest('div.content_news_box_sidebar');
	$content_image_x = $box.find('content_image_x');
	$image_url = $content_image_x.find('img').prop('src');
	$title = $content_image_x.find('img').prop('title');
	$fbimgid = $content_image_x.find('span.fbimgid').text().trim();
	$fbimglink = $content_image_x.find('span.fbimglink').text().trim();
	
	set_image_as_imported($image_url, $title, $fbimgid, $fbimglink);
	
	$content_image_x.find('input').val('1');
	$this.css('display', 'none');
	$this.siblings('._selected').first().css('display', 'block');
	$box.css('background-color', 'rgb(65, 150, 25)');
}
function __deselect_img_build_picture_selector($this)
{
	$box = $this.closest('div.content_news_box_sidebar');
	$image_url = $box.find('content_image_x').find('img').prop('src');
	clear_image_as_imported($image_url);
	
	$box.find('content_image_x').find('input').val('0');
	$this.css('display', 'none');
	$this.siblings('._to_select').first().css('display', 'block');
	$box.css('background-color', 'white');
}

function show_image_import_picker($params)
{
	//console.log($params);
	$pics = $params['photos']['data'];
	if (0==$pics.length){cmn_s_m_f_r_f(0, "No pictures found!", clear_imported_objs, 0); return;}
	$next_url = $params['photos']['paging']['next'];
	$prev_url = $params['photos']['paging']['previous'];
	//$base_url = $next_url.replace("https://graph.facebook.com", "").replace("http://graph.facebook.com", "");//$params['base_uri'];
	
	$cur_next_time = $('div.success_msg').find('._next_url').siblings('._qtime').first().html();
	if (undefined != $cur_next_time)
	{
		$('div.success_msg').find('._prev_url').siblings('._qtime').first().html($cur_next_time);
	}
	/*$prev_url = $cur_next_url; // set current before url
	$next_url = $next_url + "&until=" + $pics[$pics.length-1].created_time; // set current before url
	*/
	
	$html = "<div style=''>" 
	+ "<div class='content_picker_msg'></div>"
	+"<div class='content_roll_left' style=\"width:auto;max-width:100%;height:"+0.6*$(window).height()+"px;max-height:60%;overflow:scroll;\">"
	+"<div style=\"overflow:hidden;\">"
	+"";

		for($i=0;$i<$pics.length;$i++)
		{
			$fullpath = $pics[$i]['source'];
			$title = $pics[$i]['name'];
			$fbimgid = $pics[$i]['id'];
			$fbimglink = $pics[$i]['link'];
			
			//$single_encoded_fullpath = 
			//$double_encoded_fullpath = 
			$html = $html
			+"<div class='content_news_box_sidebar' style='width:25%;height:25%;'>"
			+"<heading style='font-size:12px;'></heading>"
			+"<div class='_selection_buttons' style='margin:2px;'>"
			+"	<div class='_to_select' onclick=\"__select_img_build_picture_selector($(this));\">"
			+"		<black_button>Select.</black_button>"
			+"	</div>"
			+"	<div class='_selected' style=\"display:none;\" onclick=\"__deselect_img_build_picture_selector($(this));\">"
			+"		<green_button style=\"border:'';\">X.</green_button>"
			+"	</div>"
			+"</div>"
			+"<content_image_x>"
			+	"<img title=\""+$title+"\" style='max-width:90%; max-height:90%;' src='"+$fullpath+"' onclick=\"update_image_in_ad('', 0);\"></img>"
			+	"<span class='fbimgid' style='display:none'>"+$fbimgid+"</span>"
			+	"<span class='fbimglink' style='display:none'>"+$fbimglink+"</span>"
			+	"<input style='display:none;' value='0'>"
			+"</content_image_x>"
			+"<bottom></bottom>"
			+"</div>";
		}
	
	$html = $html
	+"<div style='color:white; font-style:italic; left:30%; top:0%; position:absolute; z-index:20;cursor:pointer;' onmouseout=\"$(this).css('color', 'white');$(this).css('background-color', 'black');\" onmouseover=\"$(this).css('background-color', 'white');$(this).css('color', 'grey');\" onclick=\"update_images_from_query_url($(this).find('div._prev_url').text(), $(this));\">prev&lt;&lt;<div class='_prev_url' style='display:none;'>"+$prev_url+"</div><div class='_qtime' style='display:none;'></div></div>"
	+"<div style='color:white; font-style:italic; right:30%;top:0%; position:absolute; z-index:20;cursor:pointer;' onmouseout=\"$(this).css('color', 'white');$(this).css('background-color', 'black');\" onmouseover=\"$(this).css('background-color', 'white');$(this).css('color', 'grey');\" onclick=\"update_images_from_query_url($(this).find('div._next_url').text(), $(this));\">next&gt;&gt;<div class='_next_url' style='display:none;'>"+$next_url+"</div><div class='_qtime' style='display:none;'>"+$pics[$pics.length-1].created_time+"</div></div>"
	+"</div>"
	+"<div style='clear:both'></div>"
	+"</div>"

	+"<div style='float:left' onclick=\"$(this).closest('div.success_msg').find('._to_select').click();\">"
	+"<black_button>Select All</black_button>"
	+"</div>"

	+"<div style='float:left' onclick=\"$(this).closest('div.success_msg').find('._selected').click();\">"
	+"<black_button>Un-Select All</black_button>"
	+"</div>"

	+"<div style='float:left' onclick=\"batch_image_update($(this));\">"
	+"<black_button>Import and Continue</black_button>"
	+"</div>"

	+"<div style='float:left' onclick=\"clear_imported_images(0);\">"
	+"<black_button>Exit</black_button>"
	+"</div>"
	
	+"<div class='_zoom_pic' style='display:none;'></div>"
	
	+"<div class='_selected_pics' style='display:none;'></div>"
	+"</div>";	
	
	s_s_m($html, clear_imported_images, 0);
}

function load_from_fb_pic_resp($presp, $qurl)
{
	var $params = [];
	if (0 == $presp['data'].length)
	{
		return;
	}
	$params['photos'] = $presp;
	$params['base_uri'] = $qurl;
	show_image_import_picker($params);
}

function update_images_from_query_url($qurl, $this)
{
	var $qtime = $this.find('._qtime').first().html();
	var $full_quri = $qurl ;//+ "&until=" + $qtime;
	
}

function clear_imported_images(p)
{
	window.imported_images = {};
}
function set_image_as_imported($img_url, $title, $fbimgid)
{
	if (undefined == window.imported_images)
	{
		window.imported_images = {};
	}
	window.imported_images[$img_url] = {'title' : $title, 'fbimgid' : $fbimgid, 'fbimglink' : $fbimglink};
}
function clear_image_as_imported($img_url)
{
	if (undefined != window.imported_images)
	{
		delete window.imported_images[$img_url];
	}
}

function batch_image_update($this)
{
	$batch_image_update_uri = $S_N+"contents/image_import_from_fb";
	$msg_box = $this.closest('div.success_msg').find('div.content_picker_msg');
	
	if (undefined == window.past_updates)
	{
		window.past_updates = {};
	}
	
	new_updates = {}
	for (x in window.imported_images)
	{
		if (undefined == window.past_updates[x])
		{
			new_updates[x] = window.imported_images[x];
		}
	}
	//console.log(new_updates);
	$msg_box.html("Importing ... just a sec ...");
	show_loading_image();
	
	$.ajax({
		url: $batch_image_update_uri,
		data : {images: window.imported_images},
		type: 'POST',
		success: function(data) {
			hide_loading_image();
			
			data = IsJsonString(data);
			if (data && data.success)
			{
				$msg_box.html("Images imported successfully.");
				for (x in new_updates)
				{
					window.past_updates[x] = new_updates[x];
				}
			}
			else
			{
				$msg_box.html("An error occured while importing images.");
				$msg_box.html(data.msg);
			}
		},
		error: function () {
			hide_loading_image();
			cb_clk();
			show_success_message("Photos successfully imported");
			$msg_box.html("Oops. Something went wrong.");
		}
	});
}
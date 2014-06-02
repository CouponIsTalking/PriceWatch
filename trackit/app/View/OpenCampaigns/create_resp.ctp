<script type='text/javascript'>

function pick_image($this)
{
	toggle_images_container();
	$adv_id = $this.closest('form').attr('id');
	$(".images_container").find('green_button').attr('onclick', "update_images('"+$adv_id+"', $(this));");
}

function pick_news($this)
{
	toggle_news_container();
	$adv_id = $this.closest('form').attr('id');
	$(".news_container").find('green_button').attr('onclick', "update_news('"+$adv_id+"', $(this));");
}

/*
function show_and_add_close_button($container)
{
	var disp_prop = $container.css('display');
	if ('none' == disp_prop)
	{
		$container.css('opacity','0');
		$container.css('display','block');
		$container.fadeTo('opacity', '0.95');
		add_close_button($container);
	}
	else
	{
		$container.find('close_button').click();
	}
}
*/

function toggle_images_container()
{
	$container = $('div.images_container');
	fade_and_add_close_button($container);
}
function toggle_news_container()
{	
	$container = $('div.news_container');
	fade_and_add_close_button($container);
}

function update_image($adv_id, $this)
{
	$adv = $("#"+$adv_id);
	$adv.find('img').attr('src', $this.find('img').attr('src'));
	toggle_images_container();
}

function update_news_link($adv_id, $this)
{
	$adv = $("#"+$adv_id);
	$adv.find('a.news').attr('href', $this.find('a').attr('href'));
	toggle_news_container();
}


</script>
<div style='display:none'>
<div>
	<div class='custom_fb_adv'>
		<div class='top_comment'>
		Create Facebook Ad.
		</div>
	
		<div class='custom_fb_adv_line' onmouseover='overlay_it($(this));' >
			<div class='overlay_div' onclick="" onmouseout='deoverlay_it($(this));'>Add your one line feedback.</div>
		</div>
		<div class='custom_fb_adv_image' onmouseover='overlay_it($(this));' >
			<div class='overlay_div' onclick="" onmouseout='deoverlay_it($(this));'>Pick an image.</div>
		</div>
		<div class='custom_fb_adv_desc' onmouseover='overlay_it($(this));' >
			<div class='overlay_div' onclick="" onmouseout='deoverlay_it($(this));'>Pick a news link.</div>
		</div>
		<div style='clear:both'></div>
		<black_button>Create Facebook Post</black_button>
	</div>
</div>

<div style="clear:both; height:20px; border:'2px solid black'"></div>
<div>
	<div class='custom_tw_adv'>
		<div class='top_comment'>
			Create Twitter Ad.
		</div>
		<div class='custom_tw_adv_line' onmouseover='overlay_it($(this));' >
			<div class='overlay_div' onclick="" onmouseout='deoverlay_it($(this));'>Add your one line feedback.</div>
		</div>
		<div class='custom_tw_adv_image' onmouseover='overlay_it($(this));' >
			<div class='overlay_div' onclick="" onmouseout='deoverlay_it($(this));'>Pick an image.</div>
		</div>
		<div style='clear:both'></div>
		<black_button>Create Tweet</black_button>
	</div>
</div>
</div>
<?php

echo "<div class='images_container' style='display:block;'>";

foreach ($contents as $index => $content)
{
	if ($content['Content']['type'] != 'image')
	{
		continue;
	}
	$img_src = $content['Content']['link'];
	echo "
		<div class='content_news_box_sidebar' style='width:300px; z-index:2'>
		<content_img><img src=\"$img_src\"></img></content_img>
		<input type='checkbox'></input>
		</div>
	";
}

echo "
<green_button onclick=\"update_image('', $(this));\">
</green_button>
";
echo "</div>";


echo "<div class='news_container' style='display:block;'>";

foreach ($contents as $index => $content)
{
	if ($content['Content']['type'] != 'news' && $content['Content']['type'] != 'blog')
	{
		continue;
	}
	$news_src = $content['Content']['link'];
	echo "
		<div class='content_news_box_sidebar' style='width:300px; z-index:2'>
		<a href=\"$news_src\">{$content['Content']['title']}</a>
		<input type='checkbox'></input>
		</div>
	";
}

echo "
<green_button onclick=\"update_news('', $(this));\">
</green_button>
";
echo "</div>";


?>
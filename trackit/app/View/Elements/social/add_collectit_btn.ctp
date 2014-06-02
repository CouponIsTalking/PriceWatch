<style type='text/css'>

* div.extn_add_outer_container{
	width:auto;
	height:auto;
	position:relative;
}

* div.extn_add_inner_container{
	width:950px;
	/*position:absolute;*/
	min-height:500px;
	height:auto;
	margin:auto;
	background:#F5EFF5;
}

* div.extn_add_inner_container .extn_box_left_side{
	margin:10px 0px 10px 0px;
	padding:10px 25px 10px 25px;
	color:black;
	font-size:16px;
	color:black;
	font-family:Helvetica sans-serif;
	width:450px;
	float:left;
	background:transparent;
}

* div.extn_add_inner_container .extn_box_right_side{
	margin:10px 25px 10px 25px;
	padding:10px 25px 10px 25px;
	color:black;
	font-size:16px;
	color:black;
	font-family:Helvetica sans-serif;
	width:350px;
	float:left;
	background:transparent;
}

* div.extn_add_inner_container .dark_dot{
	border:2px solid white;
	width:10px;
	height:10px;
	border-radius:10px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	color:black;
	-transition-duration:0.2s;
	-moz-transition-duration:0.2s;
	-webkit-transition-duration:0.2s;
	background-color:gray;
	cursor:pointer;
	margin-left:5px;
	float:left;
}

* div.extn_add_inner_container .dark_dot:hover{
	color:gray;
	border-radius:8px;
	-moz-border-radius:8px;
	-webkit-border-radius:8px;
}

* div.extn_add_inner_container .dark_dot_pressed{
	border:2px solid white;
	width:10px;
	height:10px;
	border-radius:10px;
	-moz-border-radius:10px;
	-webkit-border-radius:10px;
	color:black;
	-transition-duration:0.2s;
	-moz-transition-duration:0.2s;
	-webkit-transition-duration:0.2s;
	background-color:black;
	margin-left:5px;
	float:left;
}

* div.extn_add_inner_container .extn_box_right_side h1{
	font-size:30px;
	letter-spacing:2px;
	text-decoration:underline;
	color:#694169;
	background:transparent;
}

* div.extn_add_inner_container .extn_box_right_side h2{
	font-size:20px;
	text-align:justify;
	text-align-last:center;
	word-spacing: 2px;
	letter-spacing: 1px;
	line-height: 27px;
	vertical-align: middle;
	color:#744E74;
	background:transparent;
}

* div.extn_add_inner_container .extn_box_right_side h3{
	font-size:16px;
	color:#4B2B4B;
}

* div.extn_add_inner_container .extn_box_right_side h3 a{
	color:#4B2B4B;
	font-weight:normal;
	text-decoration:none;
	cursor:pointer;
	background-color:#E9DCE9;
	padding-left:4px;
	padding-right:4px;
}

* div.extn_add_inner_container .extn_box_right_side .extn_desc{
}

* div.extn_add_inner_container .extn_box_right_side .get_btn{
	font-size:20px;
	color:#E4CBE4;
	padding:10px;
	margin:20px;
	border:3px solid purple;
	border-radius: 3px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	letter-spacing:2px;
	-transition-duration:0.2s;
	-moz-transition-duration:0.2s;
	-webkit-transition-duration:0.2s;
	background:purple;
	cursor:pointer;
	padding-left:45px;
}

* div.extn_add_inner_container .extn_box_right_side .get_btn:hover{
	font-style:underline;
	border:3px solid #E4CBE4;
	background:#E4CBE4;
	color:purple;
}

* div.extn_add_inner_container ._images{
	display:none;
}
* div.extn_add_inner_container ._full_images{
	display:none;
}


* div.extn_add_inner_container .extn_full_image_container{
	opacity:0;
	position:absolute;
	top:10px;
	margin:2px 2px;
	padding:8px;
	border: 2px solid gold;
	border-radius:5px;
	-webkit-border-radius:5px;
	-moz-border-radius:5px;
}

</style>

<script type='text/javascript'>
function _dark_dot_clk($this, i){
var i_slctr='img:eq('+i+')';
var ni=$('.extn_add_inner_container').find('._images').find(i_slctr).attr('src');
var fi=$('.extn_add_inner_container').find('._full_images').find(i_slctr).attr('src');
$('.extn_active_img').attr('src',ni);$('.extn_active_fullimg').attr('src',fi);
$this.siblings('div').attr('class','dark_dot');
$this.attr('class','dark_dot_pressed');
}
</script>

<div class='extn_add_outer_container'>
<div class='extn_add_inner_container'>

<div class='extn_box_left_side'>
<img class='extn_active_img' style='cursor:pointer;' onmouseover="$('.extn_full_image_container').fadeTo('slow',0.95);" src="<?php echo $tile_images[0];?>">

<div class='extn_full_image_container' onmouseout="$('.extn_full_image_container').fadeOut('slow',0);">
<img class='extn_active_fullimg' width='800' height='480' src="<?php echo $full_images[0];?>">
</div>

<div class='_images'>
<?php
for($i=0;$i<count($tile_images);$i++){
	echo "<img class='extn_promo_tile' src=\"{$tile_images[$i]}\">";
}
?>
</div>

<div class='_full_images'>
<?php
for($i=0;$i<count($tile_images);$i++){
	echo "<img class='extn_promo_tile' src=\"{$full_images[$i]}\">";
}
?>
</div>

<div style='display:inline-flex;cursor:pointer;'>
<?php
for($i=0;$i<count($tile_images);$i++){
	if (0==$i){
		echo "<div class='dark_dot_pressed' onmouseover='_dark_dot_clk($(this), {$i});'></div>";
	}else{
		echo "<div class='dark_dot' onmouseover='_dark_dot_clk($(this), {$i});'></div>";
	}
}
?>
</div>

</div>

<div class='extn_box_right_side'>
	<h1 style='margin-left:100px;'>
	CollectIt
	</h1>
	<h2>
	Collect the shopping items you like across the web, build collection, share those with friends and know when prices drop for those.
	</h2>
	<h3>
	CollectIt is a plugin developed by <a href="<?php echo SITE_NAME;?>"><?php echo GENERIC_APPNAME; ?></a>. It allows you to save the shopping items you like across the web at one common place, lets you build collection of the items, and track the prices of those items, informing you when the prices drop.
	</h3>
	<div class='get_btn' onclick="var eip=new extnInstallPrompt();eip.installChromeExtn();">Get CollectIt Button</div>
</div>

</div>
</div>
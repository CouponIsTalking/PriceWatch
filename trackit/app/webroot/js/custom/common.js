function in_iframe(){try {return window.self !== window.top;} catch(e){return true;}}
function m_n_c(nc,nca){make_next_call(nc,nca);} function make_next_call(nc, nca){if (nc){if(nca){nc(nca);}else{nc();}}}
function IsJsonString(str){try{dstr=JSON.parse(str);return dstr;}catch(e){return false;}return true;}
function is_ie(){if ('Microsoft Internet Explorer'==navigator.appName){return true;}return false;}
function is_netscape(){return 'Netscape'==navigator.appName;}
function is_mobile(){if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){return true;}return false;} 

function OpenInPopUpWindow(windowName, url) {
if (is_ie()){newwindow = window.open(url,windowName,'_blank','toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=300,left = 10,top = 10');
}else{newwindow=window.open(url,windowName,'height=300,width=600');}
if(!newwindow||newwindow.closed||typeof newwindow.closed=='undefined'){return false;}if (window.focus) {newwindow.focus()}return true;
}

function addhttp($ou){var $u = $ou.trim();
if (0!=$u.toLowerCase().indexOf('http')){$u = "http://"+$u;return $u;}return $ou;
}

function OpenInNewTab(u){var win=window.open(u, '_blank');win.focus();return;}
function pageRefresh(){location.reload();}
function moveTo($u){window.location = $u;}
function moveToHomePage(){moveTo($S_N);}

function fade_and_add_close_button(nd){
nd.show();nd.fadeTo(500, 0.95);nd.css("z-index", 10);
$cb = $("<close_button><img src='"+$S_N+"img/close_button.png'></img></close_button>");
$cb.css('position', 'absolute');nd.append($cb);
var h = $cb.css('height');var w = $cb.css('width');
$cb.css('top', '-'+h.toString());$cb.css('left', '-'+w.toString());
$fd = $('#fade');$fd.show();
$cb.on('click', function(){nd.empty();nd.hide();$fd.hide();});
if(!is_mobile()){$fd.on('click', function(){$cb.trigger('click')});}
$fd.css("z-index", 9);nd.draggable();
}

function show_error_message($m, to_call, args){s_s_m($m, to_call, args);}

function show_message_in_div(div_slctr, $m, to_call, args){
nc = to_call;nca = args;
nd = $(div_slctr);nd.html($m);nd.show().fadeTo(500, 0.95);nd.css('opacity', 0.95);
$cb = $("<close_button>X</close_button>");
$cb.css('position', 'absolute');nd.append($cb);
$cb.on('click', function(){nd.empty();nd.hide();m_n_c(nc, nca);});
nd.draggable();cnvrt_pval_f_to_a(div_slctr);
zout_on_mbl();
}

function show_success_message($m, to_call, args){
var $slctr = "div.success_msg"; nc = to_call;nca = args;
nd = $($slctr);nd.html($m);nd.show();
nd.fadeTo(500, 0.95);nd.css("z-index", 10).css('opacity', 0.95).css('position', 'fixed').css('width', '30%').css('height', '25%').css('top', '40%').css('left', '30%');
$cb = $("<close_button>X</close_button>");
nd.append($cb);
$fd = $('#fade');$fd.show();
$cb.on('click',function(){nd.empty();nd.hide();$fd.hide();m_n_c(nc, nca);});
if(!is_mobile()){$fd.on('click', function(){$cb.trigger('click')});}
$fd.css("z-index", 9);
nd.draggable({disabled:true});
fit_to_inner_content($slctr);r_i_c($slctr);//cnvrt_pval_f_to_a($slctr);
zout_on_mbl();
}

function show_message_fit_reposition_flat($slctr, $m, $nc, $nca){cb_clk();
show_message_in_div($slctr, $m, $nc, $nca);
fit_to_inner_content($slctr);r_i_c($slctr);
}

function show_message_fit_reposition_ary($p){
 $slctr = $p['selector'];$m = $p['msg'];$nc = $p['next_call'];$nca = $p['next_call_args'];
 show_message_fit_reposition_flat($slctr, $m, $nc, $nca);
}

function close_button_click(){$("close_button").click();}

function remove_close_button_click(){$close_button.on('click', function(){});}

function zoomVimeoVideo(src){
	$video_div_html = "<iframe id='player1' src='"+ src +"' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
	$window_w = $( window ).width();
	node = $("div.video_div");node.html($video_div_html);node.show();		
	$image_w = node.width();	
	if ($image_w < 0.9*$window_w){$left = parseInt(($window_w - $image_w)/2) + "px";}
	else{$left = "10%";}
	node.fadeTo(500, 0.95);node.css("z-index", 10);$fade = $('#fade');$fade.show();
	$fade.on('click', function(){node.empty();node.hide();$fade.hide();});
	$fade.css("z-index", 9);
}

function zoomYoutubeVideo(src){ $video_w = 425;$video_h = 350;
	$video_div_html = "<iframe id='ytplayer' type='text/html' width='640' height='360' src='"+ src + "' frameborder='0' allowfullscreen>";
	$window_w = $( window ).width();	
	node = $("div.video_div");node.html($video_div_html);node.show();		
	$image_w = node.width();
	if ($image_w < 0.9*$window_w){$left = parseInt(($window_w - $image_w)/2) + "px";}
	else{$left = "10%";}
	
	node.fadeTo(500, 0.95);node.css("z-index", 10);
	$fade = $('#fade');$fade.show();
	$fade.on('click', function(){node.empty();node.hide();$fade.hide();});
	$fade.css("z-index", 9);
}

function zoomFBVideoFromCommentedIframe($txt_html){
$txt_html=$txt_html.replace("<!--","<div><div onclick='$(this).parent().remove();' style='cursor:pointer;position:fixed;left:0%;top:0%;background-color:black;-moz-opacity:0.7;opacity:.70;filter:alpha(opacity=70);width:100%;height:100%;z-index:29;'></div><div style='z-index:30;top:3%;left:3%;position:fixed;max-width:90%;max-height:90%;padding:1%;'><green_button style='vertical-align:top;' onclick='$(this).parent().parent().remove();'>close</green_button>").replace("-->","</div></div>");
$('body').append($txt_html);
}

function zoomImage(src){$window_w = $(window).width();
$image_div = $("<img src='"+src+"'></img>");
nd = $("div.image_div");nd.append($image_div);nd.show();$image_w = nd.width();

if($image_w < 0.9*$window_w){$left=parseInt(($window_w-$image_w)/2)+"px";}else{$left="10%";}

nd.fadeTo(500, 0.95);nd.css("z-index", 10);
$fd = $('#fade');$fd.show();$fd.on('click', function(){nd.empty();nd.hide();$fd.hide();});
$fd.css("z-index", 9);
}

function validateEmail(email) { 
var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
return re.test(email);
} 

function get_today_mm_dd_yyyy(){
var today = new Date();var dd = today.getDate();var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = mm+'/'+dd+'/'+yyyy;
return today;
}

function show_loading_image(){
$loading_image = $("#loading_image");$loading_image.css('z-index', '20');$loading_image.css('opacity', '0');
$loading_image.css('display', 'block');$loading_image.fadeTo('500', '0.95');
}

function hide_loading_image(){$("#loading_image").css('display', 'none');}

function overlay_it($this){var $od=$this.find('.overlay_div');$od.fadeTo('opacity', '0.30');$od.css('display', 'block');}
function deoverlay_it($this){$this.css('display', 'none');}

function fit_to_inner_content(slctr){$(slctr).css('height', 'auto');$(slctr).css('width', 'auto');}

//bs=box_selector
function reposition_in_center_height(bs){
$(bs).css('height', 'auto');
//var $h = parseInt($(bs).css('height'));
var $h = parseInt($(bs).height());var $wh = $(window).height();
var $top = ($wh - $h)/2;
if($top <= 0 ){$top='10px';}else if($top > $h * 0.1) {$top = '10%';}
else {$top = $top.toString() + "px";}
$(bs).css('top', $top);$(bs).css('max-height', ($wh-30).toString() + "px");
}

function reposition_in_center_width(bs){$(bs).css('width', 'auto');
var $prev_w = $(bs).width();var $w = parseInt($prev_w);var $ww = $(window).width();
var $left = ($ww - $w)/2;
if($left <= 0 ){$left='10px';}else {$left = $left.toString() + "px";}
$(bs).css('left', $left);$(bs).css('max-width', ($ww-30).toString() + "px");
if ($(bs).width()!=$prev_w){$(bs).width($prev_w);}
}

function reposition_in_center(bs){$(bs).css('position', 'fixed');
reposition_in_center_height(bs);reposition_in_center_width(bs);
var $w = parseInt($(bs).width());var $h = parseInt($(bs).height());
var $ww = $(window).width();var $wh = $(window).height();

if (($w > 0.5 * $ww)&& ($h < 0.4 * $wh)){
 $half_width = 0.5 * $ww;$(bs).css('max-width', ($half_width).toString() + "px");
 }
cnvrt_pval_f_to_a(bs); 
}

function point_wise_message($this){
$how_tos = $this.find('div.how_to_messages').children('div');
$t_s = $how_tos.length;

var $html = "<div>";
for (var $index=0; $index < $t_s; $index++){
$step_html = $($how_tos[$index]).html();
$html = $html + "<div style='position:relative;'><div class='step_no'>"+($index+1).toString()+"</div><div class='step_desc'>"+$step_html+"</div></div><div style='clear:both'></div>";
}
$html += "</div>";

s_s_m($html);
$div_sm=$('div.success_msg');$div_sm.css('max-width', '90%');
r_i_c('div.success_msg');
$div_sm.css('max-height', '60%');$div_sm.css('overflow-y', 'auto');

}

function slide_to_view($this) {var speed = 500;var locationHref = window.location.href
var bgColor = $this.css('background-color');
var destination = Math.max($this.offset().top - 10, 0);
$("html:not(:animated),body:not(:animated)").animate({ scrollTop: destination}, speed, function() {
 $this.animate({ backgroundColor: "#FCFCD8" },1).delay(1000).animate({ backgroundColor: bgColor }, 1500);
});
return false;
}

function show_current_limits(){var $l = {};$l[0] = ['.', '3 Tweets per hour'];$l[1] = ['.', '3 Tweets per day'];
$l[2] = ['.', '3 FB posts per hour'];$l[3] = ['.', '3 FB posts per day'];
	
$html="<div style=\"overflow:scroll;\">";
for (var $id in $l){
 $html=$html+"<div style='position:relative;'><div class='step_no'></div><div class='step_desc'>"+$l[$id][1]+"</div></div><div style='clear:both'></div>";
}
$html=$html+"</div>";
s_s_m($html);r_i_c('div.success_msg');
}

function script_load_and_call($p){var $script_name=$p['script'];call_to_load=$p['call_to_load'];
scn=$p['scn'];scp=$p['scp'];ecn=$p['ecn'];ecp=$p['ecp'];
if (undefined != window[call_to_load]){sc = window[scn];sc(scp);return;}
else{s_s_m("just a sec ...");$.getScript( $S_N+"js/"+$script_name+".js").done(function(script, textStatus){
  cb_clk();sc = window[scn];sc(scp);}).fail(function( jqxhr, settings, exception ){cb_clk();ec=window[ecn];ec(ecp);});
}
}

function isNumber(n){return !isNaN(parseFloat(n)) && isFinite(n);}

function cnvrt_pval_f_to_a($a){return;if('fixed'!=$($a).css('position')){return;}
$($a).css('top', $($a).offset().top + "px");$($a).css('left', $($a).offset().left + "px");
$($a).css('position', 'absolute');
}
function zout_on_mbl(){if(is_mobile()){window.parent.document.body.style.zoom='';}}
function s_s_m($m,nc,nca){show_success_message($m,nc,nca);}
function s_e_m($m,nc,nca){show_error_message($m,nc,nca);}
function r_i_c($s){reposition_in_center($s);}
function cb_clk(){close_button_click();}
function cmn_s_m_f_r_f(a,b,c,d){if(!a){a='div.success_msg';} show_message_fit_reposition_flat(a,b,c,d);}
function ask_logintp(){var $h="Please <a style='cursor:pointer;color:white;' onclick='show_ulf();'>login</a> to proceed.";cmn_s_m_f_r_f(0,$h,0,0);}
function ask_addfb(){var $h="Please connect with Facebook account to proceed.<br/><br/><green_button onclick='action_fb_login();'>Connect Facebook Account</green_button>";cmn_s_m_f_r_f(0,$h,0,0);}
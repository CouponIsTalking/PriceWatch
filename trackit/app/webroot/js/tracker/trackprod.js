function track_product_by_id($pid){

$('._tracker_frame').show();
$('._tracker_frame').find('#msg').text("LOADING...");

$.ajax({
	type: 'POST',url: $S_N + "prod_imports/get_prod_info",
	data:{way:'prodid', val: $pid},
	success:function(data){
		var $d = IsJsonString(data);
		if(!$d){
			cmn_s_m_f_r_f(0, "A network error occured.");
		}else if (0==$d['ul']){
			show_ulf();
		}else if (!$d['s']){
			var m="";if ($d['m']){m = $d['m'];}else{m="A network error occured.";}
			cmn_s_m_f_r_f(0, m);
		}else if($d['pinfo']){
			var $uemail = $d['uinfo']['email'];
			var $pid = $d['pinfo']['prodid'];
			var $title = $d['pinfo']['title'];
			var $prodlink = $d['pinfo']['prodlink'];
			var $image_link1 = $d['pinfo']['image_link1'];
			var $image_link2 = $d['pinfo']['image_link2'];
			var $cur_price = $d['pinfo']['cur_price'];
			var $recent_pricing_info = $d['pinfo']['recent_pricing_info'];
			//var $user_groups = $d['pinfo']['user_groups'];
			$("#trackit1234567890title").val($title);
			$("#trackit1234567890pageurihidden").val($prodlink);
			$("#trackit1234567890email").val($uemail);
			$("#trackit1234567890email").prop('disabled', true);
			$("#form2").show();
			$('._tracker_frame').find('#msg').text("");
		}
	},
	error: function(data){
	}
});


}

function trackit1234567890_part1($this)
{
	$a = $this.parents("#trackit1234567890A");
	$pageurl = $a.find("#form1").find('#trackit1234567890pageuri').val();
	$TRACKER_SITE_NAME = $S_N; //"http://localhost/trackit/";
	$a.find("#msg").text("Please Wait ... ");
	$.ajax({
		type:"POST",
		data:{url: $pageurl},
		url: $TRACKER_SITE_NAME  + "products/add_item_part1",
		success : function(data) {
			info_for_second_part = eval('(' + data + ')');
			//title = data;
			title = info_for_second_part.title;
			user_email = info_for_second_part.user_email;
			$form2 = $a.find('#form2');
			$form2.find('#trackit1234567890title').val(title);
			$form2.find('#trackit1234567890pageurihidden').val($pageurl);
			
			if (user_email != "")
			{
				$form2.find('#trackit1234567890email').val(user_email);
				$form2.find('#trackit1234567890email').attr('disabled', true);
			}
			
			$a.find('#form1').css('display', 'none');
			
			$form2.css('opacity', '0');
			$form2.css('display', 'block');
			$form2.fadeTo('slow', 1);
			
			$a.find("#msg").text("Fill up the above form and click 'track this item' ");

		},
		error : function() {
			//console.log("call failed");
			//ToggleTrackitform($form);
		}
	});
}

function trackit1234567890_part2($this)
{
	$a = $this.parents("#trackit1234567890A");
	$form2 = $a.find('#form2');
	$pageurl = $form2.find('#trackit1234567890pageurihidden').val();
	$name = $form2.find('#trackit1234567890title').val();
	$email = $form2.find("#trackit1234567890email").val()
	$price = $form2.find("#trackit1234567890price").val()
	
	$a.find("#msg").text("Please Wait ..... This may take a while.");
	
	$TRACKER_SITE_NAME = $S_N; //"http://localhost/trackit/";
	$.ajax({
		type:"POST",
		data:{url: $pageurl, name: $name, email: $email, wait_price: $price},
		url: $TRACKER_SITE_NAME  + "products/add_item_part2",
		success : function(data) {
			var result = $.parseJSON($.parseJSON(data));
			//alert(result.success);
			if (result.success)
			{
				//console.log("item successfully added");
				$a = $("#trackit1234567890A");
				$form1 = $a.find('#form1');
				$form2 = $a.find('#form2');
				//$form1.fadeTo('slow', 0, function(){ $(this).css('display','none');} );
				//$form2.fadeTo('slow', 0, function(){ $(this).css('display','none');} );
				$form2.css('display','none');
				
				$form3 = $a.find('#form3')
				$form3.css('opacity', '0');
				$form3.css('display', 'block');
				$form3.fadeTo('slow', 1, function(){
					$a.find("#msg").text("Congrats. We have added this item to our tracker.");
				});
				
				b = result.groups
				$('#group_name_select_box').empty();
				for (var i =0;i<b.length;i++)
				{
					$('#group_name_select_box').append($('<option>', { 
						value: result.groups[i],
						text : result.groups[i] 
					}));
					$('#form3').find('#itemid').val(result.itemid);
				}
				
			}
			else
			{
				//console.log("item adding failed");
				//console.log(result.msg);
				$a.find("#msg").text(result.msg);
			}

		},
		error : function() {
			//console.log("call failed");
			$a.find("#msg").text(result.msg);
		}
	});
}
 
 
function trackit1234567890_part3($this)
{
	$form3 = $this.parents('#form3')
	if ($this.parent().find("#is_creating_new_collection").val() == 1)
	{
		$group_name = $form3.find('#new_group_name').val()
	}
	else
	{
		$group_name = $form3.find('#group_name_select_box').val()
	}
	$itemid = $form3.find('#itemid').val()

	$TRACKER_SITE_NAME = $S_N; //"http://localhost/trackit/";
	$.ajax({
		type:"POST",
		data:{itemid: $itemid, group_name: $group_name},
		url: $TRACKER_SITE_NAME  + "products/update_group",
		success : function(data) {
			var result = $.parseJSON($.parseJSON(data));
			//alert(result.success);
			if (result.success)
			{
				//console.log("item group successfully added");
				$a = $("#trackit1234567890A");
				$form1 = $a.find('#form1');
				$form2 = $a.find('#form2');
				//$form2.fadeTo('slow', 0, function(){ $(this).css('display','none');} );
				$form3.fadeTo('slow', 0, function(){ 
					$(this).css('display','none');
					$form1.css('opacity', '0');
					$form1.css('display', 'block');
					$form1.fadeTo('slow', 1);
					var $ip_bg_text = "Done! Enter url of another product to track.";
					var $ip_box = $form1.find('#trackit1234567890pageuri');
					$ip_box.val($ip_bg_text);
					$ip_box.focus(function(){if (this.value == 'Done! Enter url of another product to track.') {this.value = '';}});
					$ip_box.blur(function(){if (this.value == '') {this.value = 'Done! Enter url of another product to track.';}});
				});			
				
				$a.find("#msg").html("Congrats. We have saved that item to our tracker. Add another item or check <a style='color:white;' href=\""+$S_N+"collections/my\">your collection</a>");
				
			}
			else
			{
				//console.log("item group update failed");
				//console.log(result.msg);
				$a.find("#msg").text(result.msg);
			}

		},
		error : function() {
			//console.log("call failed");
			$a.find("#msg").text(result.msg);
		}
	});
	
} 
 
function ToggleTrackitform($this)
{
	$a = $this.parents("#trackit1234567890A");
	$form = $a.find('form');
	if($form.css('display') == 'none')
	{
		$form.css('z-index', 1000);
		$form.css('opacity',0);
		$form.css('display','block');
		$form.fadeTo('slow', 1.0);			
	}
	else
	{
		$form.fadeTo('slow', 0, 
					function()
						{
							$(this).css('display', 'none');
						}
				);
	}
}

function show_new_collection_form($this)
{
	$form = $this.parent().find("#add_new_collection_name");
	$form.css('opacity', 0);
	$form.css('display', 'block');
	$form.fadeTo('slow', 1.0);
}

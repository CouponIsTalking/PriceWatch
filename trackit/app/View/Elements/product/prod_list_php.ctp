<?php
//prod list element without javascript or css needed for prod list
?>
<?php
foreach ($products as $index=>$product)
{
$group_name = "";
$up_id = 0;
$product = $product['Product'];
$product_name = $product['name'];
$product_id = $product['id'];
$company = $companies[$product['company_id']];
$product_link = $product['purl'];
$product_price = $product['cur_price'];
$product_image = $product['image1'];
$likes = $product['like'];
$wants = $product['want'];
$owns = $product['own'];

if (empty($product_image))
{
	$product_image = SITE_NAME."/img/default_item_pic.png"; 
}
$company_site_name = $company['website'];
$company_name = $company['name'];

$lq=$wq=$oq=0;
if(!empty($prod_votes[$product_id])){
$lq = $prod_votes[$product_id]['like'];
$wq = $prod_votes[$product_id]['want'];
$oq = $prod_votes[$product_id]['own'];
}

$div = "".
"<div class='collection_item'>
	<span class='_p{$product_id}_prod_votes' style='display:none;'>
		<input type='hidden' class='like' value='{$lq}'/>
		<input type='hidden' class='want' value='{$wq}'/>
		<input type='hidden' class='own' value='{$oq}'/>
	</span>
    <span class='_pid' style='display:none;' onclick='_call_draw_pchart($(this));'>{$product_id}</span>
".
"	<item_site_name>".
"		{$company_name}".
"	</item_site_name>".
"	<div style='clear:both'></div>".
"	<div class='item_name' onclick=\"OpenInNewTab('{$product_link}');\">".
//"		<div onclick=\"OpenInNewTab('{$product_link}');\">".
"			{$product_name}".
//"		</div>".
"	</div>".
"	<div style='clear:both'></div>".
"	<item_pic>".
"		<img onclick=\"OpenInNewTab('{$product_link}');\" src=\"{$product_image}\"></img>".
"	</item_pic>".
"	<div style='clear:both'></div>".
"	<price>".
"		{$product_price}".
"	</price>".
"	<div style='clear:both'></div>".
"	<div class='group_name' style='float:right'>".
"		{$group_name}".
"	</div>".
"	<div style='clear:both'></div>";

if ($edit_options){
$div = $div . "<div style='float:right'>
		<div class='group_change_tag_blue'  style='float:right' onclick='update_group_click_in_item_tile($(this));'>remove</div>
		<div class='group_change_tag_brown' style='float:right' onclick='update_group_click_in_item_tile($(this));'>move</div>
		<div class='group_change_tag_blue'  style='float:right' onclick='update_group_click_in_item_tile($(this));'>copy</div>
	</div>";
}
if ($track_options){
$div = $div . "<div style='clear:both;'></div><div style='float:right;margin:1px;'>
		<div class='group_change_tag_blue'  style='float:right' onclick='__call_trackit_frame({$product_id});'>Track It</div>
	</div>";
}
if ($like_want_own_options){
	
	$do_you_like_str="Like, want or own?";
	$like_str = "";
	if($likes>0){$like_str = $likes; 
		if($likes==1){$like_str = $like_str . ' Like';}
		else{$like_str = $like_str . ' Likes';}
	}
	if($wants>0){
		if(""!=$like_str){$like_str = $like_str.", ";}$like_str = $like_str.$wants;
		if($wants==1){$like_str = $like_str . ' Want';}
		else{$like_str = $like_str . ' Likes';}
	}
	if($owns>0){
		if(""!=$like_str){$like_str = $like_str.", ";}$like_str = $like_str.$owns;
		if($owns==1){$like_str = $like_str . ' Own';}
		else{$like_str = $like_str . ' Own';}
	}
	if (""==$like_str){ $like_str="Be the first to like.";}
	
$div = $div . "<div style='clear:both;'></div><div style='margin-top:1px;float:right'>
		<div class='_like_q_tag' onclick='__call_vote_frame($(this),{$product_id});'>Like, want or own?</div>
		<br/>
		<div class='_like_q_tag' style='font-weight:normal;font-size:12px;border-bottom:none;'>{$like_str}</div>
	</div>";
}

$div = $div . "
	<div style='clear:both'></div>
	<div class='trend_sign_container' onclick='_call_draw_pchart($(this));'>
	<div class='trend_sign' style='margin:7px 7px 7px 7px;width:16px;height:16px;'></div>
	</div>
".
"	<input class='action_val' style='display:none;' value=''></input>".
"   <div>".
"		".
"		".
"	<form class='remove_item_form' style='display:none'>".
"		<div>".
"			Are you sure you want to remove this item ?".
"		</div>".
"		<br/>".
"		<black_button onclick=\"remove_from_group($(this), '{$up_id}');\">".
"			Yes".
"		</black_button>".
"		<black_button onclick=\"x_overlay_element_click($(this));\">".
"			No".
"		</black_button>".
"	</form>".
"	<form class='update_collection_form' style='display:none'>
		<input type='hidden' class='existing_group_name' style='display:none;' value='{$group_name}'></input>
		<input type='hidden' value='{$up_id}' class='itemid'></input>		
		<div class='action_name'></div>
		<div class='pick_existing_collection_select_box'>
			<select name='group_name' class='group_name_select_box' required='required'>
";

if (!empty($collection_names)){
foreach ($collection_names as $index=>$group_name)
{
	$div = $div . "<option name='{$group_name}' val='{$group_name}'>{$group_name}</option>";
}
}

$div = $div . "			
			</select>
			
			<input type='hidden' value='0' class='is_creating_new_collection'></input>
			<br/><br/>
			<black_button onclick='update_group_name( $(this) );'>Add To Collection</black_button>
		</div>
		
		<black_button onclick='show_add_new_collection_form($(this));'>+</black_button>
		<div class='add_new_collection_name' style='display:none'>
			<input name='new_group_name' class='new_group_name' required='required' maxlength='20'>
			</input>
			<input type='hidden' value='1' class='is_creating_new_collection'></input>
			<br/><br/>
			<black_button onclick='update_group_name( $(this) );'>Add To Collection</black_button>
		</div>
	</form>".
"		".
"   </div>".
"   <div class='overlay_element' style='display:none;'>".
"	</div>".
"	<div class='x_overlay_element' style='display:none;' onclick='x_overlay_element_click($(this));'>".
"		X".
"	</div>".
"	<div class='msg' style='display:none;'>".
"	</div>".
"	<div style='clear:both'></div>".
"</div>".
"";

echo $div;

}
?>
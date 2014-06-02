function update_group_click_in_item_tile($this)
{
	update_action_val_in_item_tile($this);
	
	$action_name_in_update_form = $this.closest(".collection_item").find(".update_collection_form").find('.action_name');
	if ($this.text() == 'move')
	{
		$action_name_in_update_form.text("Move To");
		show_update_group_form($this);
	}
	else if ($this.text() == 'copy')
	{
		$action_name_in_update_form.text("Copy To");
		show_update_group_form($this);
	}
	else if ($this.text() == 'remove')
	{
		show_remove_item_form($this);
	}
	
}

function update_action_val_in_item_tile($this)
{
	$collection_item = $this.closest(".collection_item");
	$collection_item.find(".action_val").val($this.text());	
}

function show_remove_item_form($this)
{
	$collection_item = $this.closest(".collection_item");
	$collection_item.find(".overlay_element").css('display', 'block');
	$collection_item.find(".x_overlay_element").css('display', 'block');
	$collection_item.find(".update_collection_form").css('display', 'none');
	$collection_item.find(".remove_item_form").css('display', 'block');
	$collection_item.find(".remove_item_form").css('z-index', '7');
	$collection_item.find(".msg").css('display', 'none');	
}

function show_update_group_form($this)
{
	
	$collection_item = $this.closest(".collection_item");
	$collection_item.find(".overlay_element").css('display', 'block');
	$collection_item.find(".x_overlay_element").css('display', 'block');
	$collection_item.find(".update_collection_form").css('display', 'block');
	$collection_item.find(".remove_item_form").css('display', 'none');
	$collection_item.find(".update_collection_form").css('z-index', '7');
	$collection_item.find(".msg").css('display', 'none');
}

function x_overlay_element_click($this)
{
	$collection_item = $this.closest(".collection_item");
	$collection_item.find(".overlay_element").css('display', 'none');
	$collection_item.find(".x_overlay_element").css('display', 'none');
	$collection_item.find(".update_collection_form").css('display', 'none');
	$collection_item.find(".add_new_collection_name").css('display', 'none');		
	$collection_item.find(".remove_item_form").css('display', 'none');
	$collection_item.find(".msg").css('display', 'none');
}

function show_add_new_collection_form($this)
{
	$collection_item = $this.closest(".collection_item");
	$collection_item.find(".add_new_collection_name").css('display', 'block');
}

function show_message_after_group_update_in_item_tile($params)
{
	$msg = $params['msg'];
	
	$collection_item.find(".add_new_collection_name").css('display', 'none');
	$collection_item.find(".update_collection_form").css('display', 'none');
	
	$collection_item.find(".msg").text($msg);
	$collection_item.find(".msg").css('display', 'block');
	$collection_item.find(".msg").css('z-index', '7');
	
}

function update_group_name($this)
{

	$is_creating_new_collection = $this.siblings(".is_creating_new_collection").val();
	
	$update_collection_form = $this.closest(".update_collection_form");
	$existing_group_name = $update_collection_form.find(".existing_group_name").val();
	$itemid = $update_collection_form.find(".itemid").val();
	
	$params = [];
	$params['params'] = [];
	$params['params']['existing_group_name'] = $existing_group_name;
	$params['params']['itemid'] = $itemid;
	$params['scn'] = 'show_message_after_group_update_in_item_tile';
	$params['sp'] = [];
	$params['ecn'] = 'show_message_after_group_update_in_item_tile';
	$params['ep'] = [];
	
	if ( parseInt($is_creating_new_collection) == 0 )
	{
		$new_group_name = $this.siblings(".group_name_select_box").val();
		$params['params']['new_group_name'] = $new_group_name;
	}
	else
	{
		$new_group_name = $this.siblings(".new_group_name").val();
		$params['params']['new_group_name'] = $new_group_name;
	}
	
	$collection_item = $this.closest(".collection_item");
	$action_name = $collection_item.find(".action_val").val();	
	
	if ('move' == $action_name)
	{
		move_to_group($this, $params);
	}
	else if ('copy' == $action_name)
	{
		copy_to_group($this, $params);
	}
	else if ('remove' == $action_name)
	{
		remove_from_group($this, $itemid);
	}
	
	return;
	
}

function remove_from_group($this, $itemid)
{
	$update_collection_form = $this.closest(".update_collection_form");
	//$itemid = $update_collection_form.find(".itemid").val();
	
	scn = 'show_message_after_group_update_in_item_tile'; //$params['scn'];
	sp = [];
	sp['msg'] = 'Item is being removed.'; //$params['sp'];
	ecn = 'show_message_after_group_update_in_item_tile'; //$params['ecn'];
	ep = [];
	ep['msg'] = 'There was an error in remove this item.'; // $params['ep'];


	$.ajax({
		type:"POST",
		data:{itemid: $itemid},
		url: $S_N + "user_products/remove_user_product_from_group",
		success : function(data) {
			var result = $.parseJSON(data);
			//alert(result.success);
			if (result.success)
			{
				x_overlay_element_click($this);
				$collection_item.find(".overlay_element").css('display', 'block');
				if (scn)
				{
					sc = window[scn];
					sp['msg'] = result.msg;
					sc(sp);
				}
				
			}
			else
			{
				if (ecn)
				{
					ec = window[ecn];
					ep['msg'] = result.msg;
					ec(ep);
				}
			}

		},
		error : function() {
			//console.log("call failed");
			if(ec)
			{
				ec = window[ecn];
				ep['msg'] = "There was an error in removing this item from this group.";
				ec(ep);
			}
		}
	});

}

function copy_to_group($this, $params)
{
	$existing_group_name = $params['params']['existing_group_name'];
	$new_group_name = $params['params']['new_group_name'];
	$itemid = $params['params']['itemid'];
	
	scn = $params['scn'];
	sp = $params['sp'];
	ecn = $params['ecn'];
	ep = $params['ep'];


	if ($existing_group_name != $new_group_name)
	{
		$.ajax({
			type:"POST",
			data:{itemid: $itemid, group_name: $new_group_name},
			url: $S_N+ "user_products/copy_user_product_to_group",
			success : function(data) {
				var result = $.parseJSON(data);
				//alert(result.success);
				if (result.success)
				{
					if (scn)
					{
						sc = window[scn];
						sp['msg'] = result.msg;
						sc(sp);
					}
					
				}
				else
				{
					if (ecn)
					{
						ec = window[ecn];
						ep['msg'] = result.msg;
						ec(ep);
					}
				}

			},
			error : function() {
				//console.log("call failed");
				if(ec)
				{
					ec = window[ecn];
					ep['msg'] = "There was an error in copying the item to new group.";
					ec(ep);
				}
			}
		});
	}
	else
	{
		if (scn)
		{
			sc = window[scn];
			sp['msg'] = "Item is already in '"+$new_group_name+"' group.";
			sc(sp);
		}
	}
}

function move_to_group($this, $params)
{
	$existing_group_name = $params['params']['existing_group_name'];
	$new_group_name = $params['params']['new_group_name'];
	$itemid = $params['params']['itemid'];
	
	scn = $params['scn'];
	sp = $params['sp'];
	ecn = $params['ecn'];
	ep = $params['ep'];
	
	
	if ($existing_group_name != $new_group_name)
	{
		$.ajax({
			type:"POST",
			data:{itemid: $itemid, group_name: $new_group_name},
			url: $S_N+ "user_products/move_user_product_to_group",
			success : function(data) {
				var result = $.parseJSON(data);
				//alert(result.success);
				if (result.success)
				{
					if (scn)
					{
						sc = window[scn];
						sp['msg'] = result.msg;
						sc(sp);
					}
					
				}
				else
				{
					if (ecn)
					{
						ec = window[ecn];
						ep['msg'] = result.msg;
						ec(ep);
					}
				}

			},
			error : function() {
				//console.log("call failed");
				if(ec)
				{
					ec = window[ecn];
					ep['msg'] = "There was an error in copying the item to new group.";
					ec(ep);
				}
			}
		});
	}
	else
	{
		if (scn)
		{
			sc = window[scn];
			sp['msg'] = "Item is already in '"+$new_group_name+"' group.";
			sc(sp);
		}
	}
}
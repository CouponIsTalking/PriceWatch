<?php
echo $this->Html->css('pchart');
echo $this->Html->script('custom/manage_collection');
echo $this->Html->script('pdisp/pchart');
echo $this->Html->script('underscore/underscore-min');
echo $this->Html->script('charts/d3.v3');

?>
<style type='text/css'>
* .collection_name {margin: 10px;padding: 5px;background: rgb(7, 7, 7);color: white;
font-family: Helvetica sans-serif;font-size: 18px;cursor:pointer;
}
* .collection_name:hover{ 
color: rgb(7,7,7); background:rgb(220,220,220);
-webkit-transition: background-color 300ms linear;
-moz-transition: background-color 300ms linear;
-o-transition: background-color 300ms linear;
-ms-transition: background-color 300ms linear;
transition: background-color 300ms linear;
}

*.selected_collection_name{margin: 10px;padding: 5px;background:rgb(170,70,70);color: white;
font-family: Helvetica sans-serif;font-size: 18px;cursor:pointer;	
}

</style>

<script type='text/javascript'>
function _call_draw_pchart($this){
	$prodid=$this.parents('.collection_item').find('._pid').text().trim();
	draw_price_chart(parseInt($prodid));
}
</script>

<?php
echo "<div class='content_news_box' style='width:100%;border:none;'>";
echo "<br/>";
?>

<p>
<?php
/*echo $this->Paginator->counter(array(
'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
));*/
echo $this->Paginator->counter(array(
'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total')
));
?>
</p>
<div class="paging">
<?php
	echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
	echo $this->Paginator->numbers(array('separator' => ''));
	echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
?>
</div>

<?php	
echo "<div class='selected_collection_name' style='display:inline-block;white-space:nowrap;'>{$filtered_group_name}</div>";

echo "<br/><br/>";

echo "</div>";


?>

<?php

$show_track_option = true;
if (!empty($self_collection) && $self_collection){
	$show_track_option = false;
}

$element_out = $this->element('product/prod_list', 
	array('products' => $products,
		'prod_votes' => $prod_votes,
		'loggedin_user_id'=>$preset_var_logged_in_user_id, 
		'edit_options' => true,
		'track_options' => $show_track_option,
		'like_want_own_options'=>true
	));
	
echo "<div style='max-width:100%;float:left;'>";
echo $element_out;
echo "</div>";

//$bgtex3_image = SITE_NAME.'img/bgtex3.jpg';
if ($show_track_option){
echo "<div class='_tracker_frame' style=\"display:none;position:fixed;width:100%;height:100%;top:0px;left:0px;background-color:rgba(0,0,0,0.6);\">";
echo $this->element('product/trackprod_frame', array(
	'loggedin_user_id' => $preset_var_logged_in_user_id, 
	'list_supported_stores' => false,
	'show_close_button' => true
	));
echo "</div>";
}


echo "<div class='_prodvote_frame' onclick=\"$(this).find('._prodvote_popup_x').click();\" style=\"display:none;position:fixed;width:100%;height:100%;top:0px;left:0px;background-color:transparent\">";
echo $this->element('product/voteprod_frame', array(
	'loggedin_user_id' => $preset_var_logged_in_user_id,
	'show_close_button' => true
	));
echo "</div>";

?>
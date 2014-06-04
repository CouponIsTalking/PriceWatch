<span class='blk_underline' style="font-size:18px;margin-bottom:15px;color:red;cursor:pointer;" onclick="moveTo('<?php echo $edit_link;?>')">Edit it</span>
<br/><br/><br/><br/>
<?php

echo "<div style='font-style:Helvetica sans-serif; font-size:16px;width:800px;'>";
foreach($data as $key => $val){
	echo " <div style='height:auto;margin:2px;padding:10px;background-color:black;color:white;'>
			<div style='width:50%;'>{$key}</div>
			<div style='width:50%;'>{$val}</div>
		   </div>
			";

}
echo "</div>";
?>
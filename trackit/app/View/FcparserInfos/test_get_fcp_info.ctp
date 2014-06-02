<script type='text/javascript'>
function RunTest(){

	$.ajax({
		type:'POST',
		url:$S_N+"fcparser_infos/get_fcp_info",
		data:{cid:'<?php echo $cid; ?>', pcode:'<?php echo $pcode; ?>'},
		success:function(data){var $d = IsJsonString(data);$('._result').html(data);},
		error:function(data){$('._result').html("Error Occured.");}
	});

}
</script>

<green_button onclick='RunTest();'>RunTest</green_button>
<div style='clear:both;'></div>
<div class='_result' style='background:black;color:white;padding:30px;font-size:24px;font-family:Helvetica sans-serif;'>

</div>
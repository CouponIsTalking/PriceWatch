<?php
$site_name = "localhost";
?>
<html>

<head>

<script type="text/javascript">

function show_code()
{
	var cur_disp = document.getElementById("code_div").style.display; 
	if (cur_disp != 'none') 
		document.getElementById("code_div").style.display='none'; 
	else 
		document.getElementById("code_div").style.display='block';
}

</script>

<title>
Your wonderful site
</title>

<style type="text/css">
* .getcode {
	float:left;
	background-color:#333; 
	color:white; 
	text-weight:500; 
	width:200px; 
	height:auto; 
	cursor:pointer;
}
* .getcode:hover {
	background-color:grey;
}

</style>

</head>

<body>

<div>
	<input style="cursor:pointer;" type='checkbox' onclick="show_code();"></input>
	<div class="getcode" onclick="show_code();">
		 Get Code for My Button
	</div>
	
	<div id="code_div" style="display:none;width:500px;border-width:5px; border-color:grey;">
		<textarea id="code" class="b2-text" rows="10" cols="150" readonly=""><script type="text/javascript"> buttons javascript here </script><style type="text/css"> buttons style here </style><a href="">MyButton</a>
		</textarea>
	</div>
	
</div>



</body>
</html>
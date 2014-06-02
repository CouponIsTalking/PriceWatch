
<script type='text/javascript'>

function popitup(windowName) {
		url = "http://www.google.com";
       newwindow=window.open(url,windowName,'height=300,width=600');
       if (window.focus) {newwindow.focus()}
       return false;
     }
	 
</script>

<style type='text/css'>
* .get_button{
	padding: 0 0.6em 0.3em 0.1em;
	box-shadow: 0 2px 1px rgba(0, 0, 0, 0.3),0 1px 0 rgba(255, 0, 0, 0.4) inset;
	background-color: #A7127D;
	color: #fff;
	border: 1px solid #000!important;
	border-radius: 3px;
	text-decoration: none;
	margin: 3px 3px 3px 3px;
	cursor: pointer;
	font-weight:bold;
	font-size:14px;
	width:60px;
	height:15px;
}

* .get_coupon_button:hover{
	background-color:#B968B0;
	cursor:pointer;
}
</style>


<div class='get_button' onclick=popitup('0_0')>
MyButton
</div>

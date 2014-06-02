<script language="javascript" type="text/javascript">
	
	function resizeIframe(obj) 
	{
		obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
	}
	
	function RunOnLoad()
	{
		$("#content").css('padding-left','0');
		$("#content").css('padding-right','0');
		$("#content").css('padding-bottom','0');
		$("#content").css('overflow','hidden');
		$content_top = $("#content").position().top;
		$footer_top = $("#footer").position().top;
		$window_height = $("body").height();
		$("#shopiframe").attr('height', $footer_top - $content_top);
	}
	
</script>

<?php


$shopurl = $this->CommonFunc->addhttp($shopurl);
$src = "<iframe id='shopiframe' onLoad=\"alert(this.contentWindow.location);\" overflow='visible' border='2' frameborder='2' hspace='0' vspace='0' marginheight='0' marginwidth='0' width=\"100%\" src=\"{$shopurl}\" onload=\"javascript:resizeIframe(this);\">" .
		"</iframe>"
		;
echo $src;
?>
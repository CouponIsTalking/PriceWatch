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
	$element_out = $this->element('layout/tracker_top', array('content_id' => $content_id));
	echo $element_out;
?>
				
<?php


$url = $this->CommonFunc->addhttp($url);
$src = "<iframe id='shopiframe' overflow='visible' border='2' frameborder='2' hspace='0' vspace='0' marginheight='0' marginwidth='0' width=\"100%\" src=\"{$url}\" onload=\"javascript:resizeIframe(this);\">" .
		"</iframe>"
		;
echo $src;
?>
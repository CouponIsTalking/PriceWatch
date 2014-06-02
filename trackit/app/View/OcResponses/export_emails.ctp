<?php

if (empty($emails))
{
//pass
}
else
{
foreach($emails as $index=>$ocr)
{
	echo "{$ocr['OcResponse']['response_data']}<br/>";
}
}

?>
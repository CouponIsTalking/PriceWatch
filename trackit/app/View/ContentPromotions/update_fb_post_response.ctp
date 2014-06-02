<?php
if (!empty($msg))
{
	echo $msg;
}
else
{
	if ($success)
	{
		echo "Thanks for writing that Facebook post. Your entry is received. We are processing it to cross check acceptance criteria. We will let you know of our response in 2-3 days. Till then happy surfing :)";
	}
	else
	{
		echo "Hmmm.. something is not right. Are you logged into Facebook ?. If it continues to give problem, send us an email and we will resolve it as soon as possible for better experience of all.";
	}
}

?>
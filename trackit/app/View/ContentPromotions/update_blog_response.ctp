<?php
if (!empty($msg))
{
	echo $msg;
}
else
{
	if ($success)
	{
		echo "Thanks for writing that blog post. Your post is received. We are processing it to cross check acceptance criteria. We will let you know of our response in 2-3 days. Till then happy blogging :)";
	}
	else
	{
		echo "Hmmm.. something is not right. Did you enter the url right ?. If it continues to give problem, send us email with your blog post's url.";
	}
}

?>
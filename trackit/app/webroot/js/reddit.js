function action_reddit_login(myparams)
{
	if (typeof myparams !== 'undefined')
	{
		var scn = myparams['scn'];
		var ecn = myparams['ecn'];
		var sp = myparams['sp'];
		var ep = myparams['ep'];
	}

	var $username = $("div.user_login_form input[id=RedditUsername]").val();
	var $password = $("div.user_login_form input[id=RedditPassword]").val();
	
	$.ajax({
		type:"POST",
		data:{ user: $username, passwd: $password, api_type: 'json' },
		url: $S_N+'users/reddit_login',// . $username,
		success : function(resp) {
		   //console.log(resp);
		   if (resp == 1)
		   {
				if (typeof scn !== 'undefined')
				{
					sc = window[scn];
					sc(sp);
				}
				else
				{
					$.ajax({
						type:"POST",
						data:{ reddit_username: $username}, 
						url: $S_N+"bloggers/build_profile_ajax/",
						success : function(data) {
						   //alert(data);// will alert "ok"
							if (data == '1')
							{
								$fade.trigger('click');
								show_success_message("Thanks, we could verify your reddit username and its updated now", pageRefresh, 0);
							}
							else
							{
								$fade.trigger('click');
								s_e_m("Though, we were able to verify your reddit username, there was an issue in updating that on file. If the problem persists, please let us know about this and we will fix it for better experience of all.", pageRefresh, 0);
							}
						},
						error : function(data) {
						   //alert("false");
							$fade.trigger('click');
							s_e_m("Though, we were able to verify your reddit username, there was an issue in updating that on file. If the problem persists, please let us know about this and we will fix it for better experience of all.", pageRefresh, 0);
							
						}
					});
				}
			}
			else
			{
				if (typeof ecn !== 'undefined')
				{
					ec = window[ecn];
					ec(ep);
				}
				else
				{
					s_e_m("Sorry, we couldn\'t log you in. Please check your Reddit username and password again.");
				}
			}
		},
		error : function(resp) {
		   //alert("false");
			s_e_m("Sorry, we couldn\'t log you in. Please, try again.");
		}
	});
}


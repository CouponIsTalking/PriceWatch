<?php

$s = "{\"like_info\":{\"can_like\":true,\"like_count\":\"0\",\"user_likes\":false},\"comment_info\":{\"can_comment\":true,\"comment_count\":\"0\",\"comment_order\":\"chronological\"},\"share_info\":{\"can_share\":false,\"share_count\":\"0\"},\"message\":\"Beautiful it is\",\"is_published\":true,\"privacy\":{\"description\":\"Only Me\",\"value\":\"SELF\",\"friends\":\"\",\"networks\":\"\",\"allow\":\"\",\"deny\":\"\"},\"permalink\":\"https://www.facebook.com/rahul.alb/posts/617201678345374\",\"source_id\":\"100001666415739\"}";

$y = json_decode($s, true);
var_dump($y);

$s = "{\"created_at\":\"Tue Jan 21 23:26:38 +0000 2014\",\"id\":4.257714505789e+17,\"id_str\":\"425771450578903040\",\"text\":\"Pick a news to link with your tweet.\",\"source\":\"srcurl\",\"truncated\":false,\"in_reply_to_status_id\":null,\"in_reply_to_status_id_str\":null,\"in_reply_to_user_id\":null,\"in_reply_to_user_id_str\":null,\"in_reply_to_screen_name\":null,\"user\":{\"id\":47177225,\"id_str\":\"47177225\",\"name\":\"hemant\",\"screen_name\":\"hemant456\",\"location\":\"\",\"description\":\"\",\"url\":null,\"entities\":{\"description\":{\"urls\":[]}},\"protected\":false,\"followers_count\":63,\"friends_count\":322,\"listed_count\":0,\"created_at\":\"Sun Jun 14 21:19:18 +0000 2009\",\"favourites_count\":0,\"utc_offset\":null,\"time_zone\":null,\"geo_enabled\":false,\"verified\":false,\"statuses_count\":12,\"lang\":\"en\",\"contributors_enabled\":false,\"is_translator\":false,\"profile_background_color\":\"C0DEED\",\"profile_background_image_url\":\"http:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png\",\"profile_background_image_url_https\":\"https:\/\/abs.twimg.com\/images\/themes\/theme1\/bg.png\",\"profile_background_tile\":false,\"profile_image_url\":\"http:\/\/pbs.twimg.com\/profile_images\/262956152\/OgAAADfAXHWtcDDktKi_m8bkP4SU36SreyKAkwMFyi5EUMdB0zEBcK5QrBXpq8Steo6jO8UQ84478W2SpF51Mup9L0MAm1T1UCn-v2la86hRIFSl0CoDxUNxOUiY_normal.jpg\",\"profile_image_url_https\":\"https:\/\/pbs.twimg.com\/profile_images\/262956152\/OgAAADfAXHWtcDDktKi_m8bkP4SU36SreyKAkwMFyi5EUMdB0zEBcK5QrBXpq8Steo6jO8UQ84478W2SpF51Mup9L0MAm1T1UCn-v2la86hRIFSl0CoDxUNxOUiY_normal.jpg\",\"profile_link_color\":\"0084B4\",\"profile_sidebar_border_color\":\"C0DEED\",\"profile_sidebar_fill_color\":\"DDEEF6\",\"profile_text_color\":\"333333\",\"profile_use_background_image\":true,\"default_profile\":true,\"default_profile_image\":false,\"following\":false,\"follow_request_sent\":false,\"notifications\":false},\"geo\":null,\"coordinates\":null,\"place\":null,\"contributors\":null,\"retweet_count\":0,\"favorite_count\":0,\"entities\":{\"hashtags\":[],\"symbols\":[],\"urls\":[],\"user_mentions\":[]},\"favorited\":false,\"retweeted\":false,\"lang\":\"en\"}";
$y = json_decode($s, true);
var_dump($y);

//$payload = file_get_contents('http://graph.facebook.com/loft');
//$payload = json_decode($payload, true);
//var_dump($payload);

list($key, $id) = explode ('UMN', '12345UMN67812');
var_dump($key);
var_dump($id);

$b = basename("http://www.youtube.com/watch?v=F5zCGgZMaWQ");
var_dump($b);

$pu = parse_url("http://www.youtube.com/watch?v=F5zCGgZMaWQ");
var_dump($pu);

var_dump(urlencode("http://localhost/trackit/open_campaigns/get_open_campaign/63"));

var_dump(urldecode("http%3A%2F%2Flocalhost%2Ftrackit%2Fopen_campaigns%2Fget_open_campaign%2F62"));

var_dump(str_replace(array(" ", "$"), array("",""), "$$$5"));

var_dump("http://www.landsend.com/pp/StylePage-431009_A6.html?CM_MERCH=REC-_-FPPP-_-GGT-_-1-_-431009-_-431129");
var_dump(urlencode("http://www.landsend.com/pp/StylePage-431009_A6.html?CM_MERCH=REC-_-FPPP-_-GGT-_-1-_-431009-_-431129"));
var_dump(htmlentities(urlencode("http://www.landsend.com/pp/StylePage-431009_A6.html?CM_MERCH=REC-_-FPPP-_-GGT-_-1-_-431009-_-431129"), ENT_QUOTES));

//$a = "http://www.landsend.com/pp/StylePage-431009_A6.html?CM_MERCH=REC-_-FPPP-_-GGT-_-1-_-431009-_-431129";
$a = "https://www.google.com/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8#q=hi%20hello";
$urlencode_a = urlencode($a);
$html_n_url_encode_a = htmlentities(urlencode($a), ENT_QUOTES);
$htmlencode_a = htmlentities($a, ENT_QUOTES);

echo "<a target='_blank' href=\"{$a}\">{$a}</a><br/><br/>";
echo "<a target='_blank' href=\"{$htmlencode_a}\">{$a}</a><br/><br/>";
echo "<a target='_blank' href=\"{$urlencode_a}\">{$a}</a><br/><br/>";
echo "<a target='_blank' href=\"{$html_n_url_encode_a}\">{$a}</a><br/><br/>";
?>
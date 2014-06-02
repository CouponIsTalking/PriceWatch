<?php
$form_html = " ".
"<div class='users'>".
"<div><label> Please enter email to finish registration. </label></div>".
"<div class='input text required'>".
	"<label for='UserUsername'>Email</label>".
	"<input name='data[User][username]' maxlength='40' type='text' id='UserUsername' required='required'/>".
"</div>".
"<br/>".
"<br/>".
"<div class='submit'>".
	"<input type='submit' onclick='action_tw_email_reg();' value='Finish Registration'/>".
"</div>".
"<div class='error_msg1'></div>".
"<div class='info_msg1'></div>".
"<div class='success_msg1'></div>".
"</div>";

echo $form_html;
?>
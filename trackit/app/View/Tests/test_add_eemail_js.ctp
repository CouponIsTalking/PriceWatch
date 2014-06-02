<?php echo $this->Html->script('ue/extraemgmt'); ?>
<script type='text/javascript'>
function test_call_add_email()
{
	$email = $('._test_email').val();
	sadd_eemail($email);
}
function test_uhe()
{
	$p = {}
	$p['e'] = $('._test_email').val();
	$p['scn'] = 'show_success_message';
	$p['sp'] = 'This email exists in your profile.';
	$p['ecn'] = 0;
	$p['ep'] = 0;
	uhe($p);
}
</script>
<?php
echo "Enter Email : <input class='_test_email'></input>";
echo "<br/><br/>";
echo "<green_button onclick='test_call_add_email();'>Add Email</green_button>";
echo "<br/><br/>";
echo "<green_button onclick='test_uhe();'>Is email added in user profile?</green_button>";
?>
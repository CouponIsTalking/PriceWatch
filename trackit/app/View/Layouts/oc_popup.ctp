<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

//$cakeDescription = __d('8goalslogo', 'Bring items from multiple sites, know when their prices drop, & buy at your price');
$cakeDescription = "UseMeNot";

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('ab');
		echo $this->Html->css('jquery-ui');
		echo $this->Html->css('themes/smoothness/jquery-ui');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<?php echo $this->Html->script('fbs/fb_pg_cmn'); ?>
	<?php echo $this->Html->script('custom/pic_selector'); ?>
	<?php echo $this->Html->script('custom/common'); ?>
	<?php echo $this->Html->script('jquery-1.10.2.min'); ?>
	<?php echo $this->Html->script('fbinf'); ?>
	<?php echo $this->Html->script('patch/cda'); ?>
	<?php //echo $this->Html->script('reddit'); ?>
	<?php echo $this->Html->script('jquery-ui'); ?>
	<?php echo $this->Html->script('custom/userauth'); ?>
	<?php echo $this->Html->script('custom/createadv'); ?>
	<?php echo $this->Html->script('custom/trackit_button_click'); ?>
	
</head>
<body>
	
	<div id="container">
		<div id="header">
			<?php 
				$element_out = $this->element('layout/header', array('layout_var' => 'popup'));
				echo $element_out;
			?>
		</div>
				
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<?php
				
		echo "<div id='footer_green' style='clear:both;height:40px;position:fixed;bottom:0px;width:100%'></div>";
			
		echo "<div id='button_popup_footer'>	
				<div class='powered_by_msg'>
				Powered by <a target='blank' href=\"".SITE_NAME."\">".SITE_TEXT_NAME."</a>.
				</div>
			 </div>";
		?>
		
	</div>
	<?php //echo $this->element('sql_dump'); ?>
	
<?php echo $this->Js->writeBuffer(); ?>

<?php
$loading_image_path = SITE_NAME . "/img/loadin.gif";

echo "
<img id='loading_image' style=\"display:none; position:fixed; top:48%; left:48%;\" src=\"{$loading_image_path}\"></img>
";
?>

<div class="success_msg"></div>
<div class="info_msg"></div>
<div class="error_msg"></div>
<div class="image_div"></div>
<div class="video_div"></div>
<div id="fade"></div>

<div id="fb-root"></div>
<div style="clear:both"></div>
<script>
	var $S_N = <?php echo "'".SITE_NAME."';"; ?> //'http://localhost/trackit/';
	
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '<?php echo FBAPPID;?>',    // App ID from the app dashboard
      channelUrl : $S_N+'channel.html', // Channel file for x-domain comms
      status     : true,                        // Check Facebook Login status
      xfbml      : true                         // Look for social plugins on the page
    });

    // Additional initialization code such as adding Event Listeners goes here
	
	SubscribeAuthEvents();
	
  };

  // Load the SDK asynchronously
  LoadFBSdk(document, 'script', 'facebook-jssdk');
  
  $( document ).ready(function() {
		if (typeof(RunOnLoad) == "function")
		{
			RunOnLoad();
		}
		
		if (is_ie())
		{
			$('body').prepend("<div class='tlw'>We highly recommend using a browser other than Internet Explorer. Anything else, such as, Chrome, Firefox or Safari should be good.</div>");
		}
	});
</script>

	
</body>
</html>
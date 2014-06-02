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
$cakeDescription = "Coupon is talking";

?>
<!DOCTYPE html>
<html>
<head>

	<link rel="chrome-webstore-item" href="https://chrome.google.com/webstore/detail/cgdgohngjjknfpckhoclbcimpmebjcdf">
	
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
		//echo $this->Html->css('ad_view');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<?php echo $this->Html->script('fbs/fb_pg_cmn'); ?>
	<?php echo $this->Html->script('custom/pic_selector'); ?>
	<?php //echo $this->Html->script('fbs/fb_vdo_import'); ?>
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
	
	<?php
		echo $this->element('social/get_collectit_sidebar');
	?>
	
	<div id="container">
		<div id="header">
			<span>
				<div onclick="moveTo('<?php echo SITE_NAME;?>');" style="cursor:pointer;position:absolute;padding:0px;margin:0px;">
				<img style='height:100px;' src="<?php echo SITE_NAME . 'img/logo-big.png'?>"></img>
				</div>
				<!--div onclick="moveTo('<?php //echo SITE_NAME;?>');" style="cursor:pointer;font-family: vivaldi;color:rgba(0,0,0,0.83);position: absolute;padding: 0px;margin: 0px;">
					<div style="position:absolute;font-size:100px;">T</div>
					<div style="position:absolute;font-weight:bold;font-size:24px;left:70px;top:30px;width:80px;">oupon is</div>
					<div style="font-size:60px;position:absolute;left:105px;top:30px;">alking</div>
					<span style="font-family:Helvetica sans-serif;font-weight:bold;background-color:rgba(0,0,0,0.5);color:white;letter-spacing:2px;text-shadow:none;vertical-align:sub;font-size:12px;">
					@lpha
					</span>
				</div-->
				
				<?php 
					/*
					echo "<span id='logo_header' onclick=\"moveToHomePage();\">{$cakeDescription}
				<span style='letter-spacing:2px;text-shadow:none;font-weight:bold;vertical-align:sub;font-size:12px;'>
				@lpha
				</span>
			</span>";
			*/
					//echo $this->Html->link($cakeDescription, SITE_NAME);
				?>
				<?php	
					//$element_out = $this->element('layout/tracker_top', array('user_email' => $preset_var_logged_in_user_email));
					//echo $element_out;
				?>
				
			</span>
			<span>
			<?php 
				$element_out = $this->element('layout/header');//, array('somevar' => 'somevar_value'));
				echo $element_out;
			?>
			</span>
		</div>
		
		<?php	
		/*
			$element_out = $this->element('layout/tracker_top', array('user_email' => $preset_var_logged_in_user_email));
			echo $element_out;
		
			$element_out = $this->element('layout/header');//, array('somevar' => 'somevar_value'));
			echo $element_out;
			*/
		?>
		
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		
		<?php
			/*
			echo "<div id='footer'>";
				 echo $this->Html->link(
						$this->Html->image('8goalslogo.jpg', array('alt' => $cakeDescription, 'border' => '0')),
						SITE_NAME,
						array('target' => '_blank', 'escape' => false)
					);
			echo "</div>";
			
			echo "<div id='footer'>
					<div onclick='moveToHomePage();' style='background-color: black; color:white; cursor:pointer;'>Um..</div>
				</div>
			";
			*/
		
		echo "<div id='footer_green' style='clear:both;height:40px;width:100%'></div>";
			
			echo "<div id='footer' style='background:rgba(0,0,0,0);width:100%;'>";
				
				echo "<div class='footer_tabs'>";
				echo $this->Html->link(
						$this->Html->image('footerimg.jpg', array('alt' => $cakeDescription, 'border' => '0')),
						SITE_NAME,
						array('target' => '_blank', 'escape' => false)
					);
				echo "</div>";	
				echo "
				<!--div class='footer_tabs'>
					<a style=\"font-size:12px;\" href='".SITE_NAME."contacts/speak_with_us'>speak with us</a>
				</div>
				<div class='footer_tabs'>
					<a style=\"font-size:12px;\" href='".SITE_NAME."contacts/about_us'>about us</a>
				</div-->
				
				";
				
					
			echo "</div>";
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

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	
</body>
</html>
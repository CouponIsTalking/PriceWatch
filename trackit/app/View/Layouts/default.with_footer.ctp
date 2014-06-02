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
	<?php echo $this->Html->script('custom/common'); ?>
	<?php echo $this->Html->script('jquery-1.10.2.min'); ?>
	<?php echo $this->Html->script('fbinf'); ?>
	<?php echo $this->Html->script('reddit'); ?>
	<?php echo $this->Html->script('jquery-ui'); ?>
	<?php echo $this->Html->script('custom/userauth'); ?>
	<?php echo $this->Html->script('custom/createadv'); ?>
	<?php echo $this->Html->script('custom/trackit_button_click'); ?>
	
</head>
<body>
	<div id="container">
		<div id="header">
			<h1>
				<?php 
					//$logolink = SITE_NAME."/img/8goalslogo.jpg";
					//echo "<img src='{$logolink}'></img>";
					//echo "<div id='logo_header' onclick=\"moveToHomePage();\">{$cakeDescription}</div>";
					//echo $this->Html->link($cakeDescription, SITE_NAME);
					echo "<span id='logo_header' onclick=\"moveToHomePage();\">{$cakeDescription}
				<span style='letter-spacing:2px;text-shadow:none;font-weight:bold;vertical-align:sub;font-size:12px;'>
				@lpha
				</span>
			</span>";
				?>
				<?php	
					//$element_out = $this->element('layout/tracker_top', array('user_email' => $preset_var_logged_in_user_email));
					//echo $element_out;
				?>
				

			</h1>
			<?php 
				$element_out = $this->element('layout/header');//, array('somevar' => 'somevar_value'));
				echo $element_out;
			?>
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
		
		echo "<div id='footer_green' style='clear:both;height:40px;position:fixed;bottom:0px;width:100%'></div>";
			
			echo "<div id='footer'>";
				
			?>
			<!-- Place this tag where you want the +1 button to render. -->
			<div class="g-plusone" data-size="medium"></div>

			<!-- Place this tag after the last +1 button tag. -->
			<script type="text/javascript">
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/platform.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			</script>
			
			<a href="https://twitter.com/share" class="twitter-share-button" data-via="twitterapi" data-lang="en">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			
			<div class="fb-like" data-href="<?php echo SITE_NAME;?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>

			<?php	
				echo $this->Html->link(
						$this->Html->image('footerimg.jpg', array('alt' => $cakeDescription, 'border' => '0')),
						SITE_NAME,
						array('target' => '_blank', 'escape' => false)
					);
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
	});
</script>

	
</body>
</html>
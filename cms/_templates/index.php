<?php defined('CORE') OR die('403'); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Landing CMS</title>
	<meta charset="utf-8">
	<meta name='HandheldFriendly' content='True'>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?=Utils::getLink('web/_cms/css/main.css');?>">
	<link rel="stylesheet" href="<?=Utils::getLink('web/_cms/css/magic-check.min.css');?>">
	<link rel="stylesheet" href="<?=Utils::getLink('web/_cms/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?=Utils::getLink('web/_cms/css/color-picker.min.css');?>">
	<script type="text/javascript">
		var root_path = '<?=Utils::getLink();?>';
	</script>
	<script src="<?=Utils::getLink('web/_cms/js/color-picker.min.js');?>"></script>
	<script src="<?=Utils::getLink('web/_cms/js/main.js');?>"></script>
	<script src="<?=Utils::getLink('cms/_tinymce/js/tinymce/tinymce.min.js');?>"></script>
	<meta name='copyright' content='Landing CMS'>
	<meta name="description" content="Free CMS for Landing">
	<meta name="keywords" content="CMS, Landing, Free">
	<meta name="author" content="Ilia Chernykh">
	<meta name="generator" content="Landing CMS 0.0.4" />

	<noscript>
		<link rel="stylesheet" href="<?=Utils::getLink('web/_cms/css/no_js.css');?>">
	</noscript>
</head>
<body>

<!-- HEADER begin -->
	<div class="navbar navbar-default navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="collapsed navbar-toggle" onclick="collapseMenu();">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="<?=Utils::getLink('cms/');?>" class="navbar-brand" title="Go to the main CMS page.">Landing CMS</a>
			</div>
			<div class="collapse navbar-collapse" id="js_collapse">
				<ul class="right nav navbar-nav">
				<?php if( defined('IS_LOGIN') ): ?>
					<li>
						<a href="<?=Utils::getLink('cms/');?>" title="Go to content editing.">Edit Content</a>
					</li>
					<li>
						<a href="<?=Utils::getLink('cms/add-field/');?>" title="Go to adding a Field.">Add Field</a>
					</li>
					<li>
						<a href="<?=Utils::getLink('cms/password/');?>" title="Go to password editing.">Edit Password</a>
					</li>
					<li>
						<a href="<?=Utils::getLink('cms/login/?logout=true');?>" title="Exit from the CMS.">Log Out</a>
					</li>
				<?php endif; ?>
					<li>
						<a href="<?=Utils::getLink();?>" target="_blank" title="Go to the Landing Page.">Website</a>
					</li>
					<li>
						<a href="https://github.com/Elias-Black/Landing-CMS" target="_blank" title="Go to the site about Landing CMS.">About CMS</a>
					</li>
					<li>
						<form
						 action="https://www.paypal.com/cgi-bin/webscr"
						 method="post"
						 target="_blank"
						 class="donate-btn">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="QGKZW29YXRDCL">
							<input
							 type="image"
							 src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"
							 border="0"
							 name="submit"
							 alt="Press the button to donate for Landing CMS via PayPal"
							 title="Press the button to donate for Landing CMS via PayPal">
						</form>
					</li>
				</ul>
			</div>
		</div>
	</div>
<!-- HEADER end -->

<!-- MAIN begin -->
	<div class="container">
		<div class="row">
			<?php if( isset($vars['error_message']) ): ?>
				<p class="alert alert-danger"><?=$vars['error_message'];?></p>
			<?php endif; ?>

			<?php if( isset($vars['success_message']) ): ?>
				<p class="alert alert-success"><?=$vars['success_message'];?></p>
			<?php endif; ?>

			<?=$vars['content'];?>
		</div>
	</div>
<!-- MAIN end -->

<!-- FOOTER begin -->
	<div class="navbar navbar-default navbar-fixed-bottom">
		<div class="container">
			<div class="col-sm-12 text-center navbar-text">
				2017 &copy; <a href="https://github.com/Elias-Black/Landing-CMS" target="_blank" title="Go to the site about Landing CMS." class="unstyle-link">Landing CMS</a>
			</div>
		</div>
	</div>
<!-- FOOTER end -->

<script type="text/javascript">
	init();
</script>

</body>
</html>

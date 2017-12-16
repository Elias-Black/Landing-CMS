<?php defined('CORE') OR die('403'); ?>

<!DOCTYPE html>
<html>
<head>
	<title>Landing CMS</title>
	<meta charset="utf-8">
	<meta name='HandheldFriendly' content='True'>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?=Utils::getLink('assets/_cms/css/main.css');?>">
	<link rel="stylesheet" href="<?=Utils::getLink('assets/vendor/magic-check_1.0.3/css/magic-check.min.css');?>">
	<link rel="stylesheet" href="<?=Utils::getLink('assets/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css');?>">
	<link rel="stylesheet" href="<?=Utils::getLink('assets/vendor/color-picker_1.3.5/color-picker.min.css');?>">
	<script type="text/javascript">
		var root_path = '<?=Utils::getLink();?>';
		var leave_prevention_message = '<?=Utils::getMessage('template:leave_prevention');?>';
	</script>
	<script src="<?=Utils::getLink('assets/_cms/js/main.js');?>"></script>
	<script src="<?=Utils::getLink('assets/vendor/color-picker_1.3.5/color-picker.min.js');?>"></script>
	<script src="<?=Utils::getLink('assets/vendor/tinymce_4.7.2/js/tinymce/tinymce.min.js');?>"></script>
	<script src="<?=Utils::getLink('assets/vendor/responsive_filemanager_9.12.1/tinymce/plugins/responsivefilemanager/plugin.min.js');?>"></script>
	<meta name='copyright' content='Landing CMS'>
	<meta name="description" content="Free CMS for Landing">
	<meta name="keywords" content="CMS, Landing, Free">
	<meta name="author" content="Ilia Chernykh">
	<meta name="generator" content="Landing CMS 0.0.6" />

	<noscript>
		<link rel="stylesheet" href="<?=Utils::getLink('assets/_cms/css/no_js.css');?>">
	</noscript>
</head>
<body>

<!-- HEADER begin -->
	<div class="navbar navbar-default navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button
				 class="collapsed navbar-toggle js_sitemenu_toggle"
				 type="button"
				 title="Open site menu."
				 data-open-title="Open site menu."
				 data-close-title="Close site menu.">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a
				 href="<?=Utils::getLink('cms/');?>"
				 class="navbar-brand"
				 title="Go to the main CMS page.">
					Landing CMS
				</a>
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
			<div
			 id="js_error_message"
			 class="alert alert-danger<?=isset($vars['error_message'])?'':' hidden';?>"
			 role="alert">
				<?=Utils::pr($vars['error_message']);?>
			</div>

			<div
			 id="js_success_message"
			 class="alert alert-success<?=isset($vars['success_message'])?'':' hidden';?>"
			 role="alert">
				<?=Utils::pr($vars['success_message']);?>
			</div>

			<div
			 id="js_info_message"
			 class="alert alert-warning<?=isset($vars['info_message'])?'':' hidden';?>"
			 role="alert">
				<?=Utils::pr($vars['info_message']);?>
			</div>

			<?=Utils::pr($vars['content']);?>
		</div>
	</div>
<!-- MAIN end -->

<!-- FOOTER begin -->
	<div class="navbar navbar-default navbar-fixed-bottom">
		<div class="container">
			<div class="col-sm-12 text-center navbar-text">
				2017 &copy; <a href="https://github.com/Elias-Black/Landing-CMS" target="_blank" title="Go to the site about Landing CMS." class="unstyle-link">Landing CMS <small>v0.0.6</small></a>
			</div>
		</div>
	</div>
<!-- FOOTER end -->

<script type="text/javascript">
	init();
</script>

</body>
</html>

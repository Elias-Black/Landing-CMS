<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getPerms($path)
{
	return substr( sprintf( '%o', fileperms($path) ), -3 );
}

$db = array(
	'cms/_db',
);

$upload = array(
	'assets/_cms/uploads/tinymce/source',
	'assets/_cms/uploads/tinymce/thumbs',
);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Landing CMS</title>
	<meta charset="utf-8">
	<meta name='HandheldFriendly' content='True'>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="assets/_cms/css/main.css">
	<link rel="stylesheet" href="assets/vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css">
	<script src="assets/_cms/js/main.js"></script>
	<meta name='copyright' content='Landing CMS'>
	<meta name="description" content="Free CMS for Landing">
	<meta name="keywords" content="CMS, Landing, Free">
	<meta name="author" content="Ilia Chernykh">
	<meta name="generator" content="Landing CMS 0.0.6" />

	<noscript>
		<link rel="stylesheet" href="assets/_cms/css/no_js.css">
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
				 href="cms/"
				 class="navbar-brand"
				 title="Go to the main CMS page.">
					Landing CMS
				</a>
			</div>
			<div class="collapse navbar-collapse" id="js_collapse">
				<ul class="right nav navbar-nav">
					<li>
						<a href="cms/" title="Go to the main CMS page.">CMS</a>
					</li>
					<li>
						<a href="./" target="_blank" title="Go to the Landing Page.">Website</a>
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

			<div class="alert alert-warning" role="alert">
				<h4 class="alert-heading">Attention!</h4>
				<p>Permissions on the folders should allow PHP to create, view and edit files inside them.</p>
				<p>Also, permissions on the files themselves should allow viewing and editing them for PHP.</p>
			</div>

			<h2>Database permissions</h2>

			<p>You need to set 777 permissions for this folder:</p>

			<?php foreach ($db as $folder): ?>

				<h5>

					<?php if(getPerms($folder) == 777): ?>

						<span class="label label-success">YES</span>

					<?php else: ?>

						<span class="label label-danger">NO</span>

					<?php endif; ?>

					<?=$folder;?>

				</h5>

			<?php endforeach; ?>

			<h2>Uploads permissions</h2>

			<p>You need to set 777 permissions for this folders:</p>

			<?php foreach ($upload as $folder): ?>

				<h5>

					<?php if(getPerms($folder) == 777): ?>

						<span class="label label-success">YES</span>

					<?php else: ?>

						<span class="label label-danger">NO</span>

					<?php endif; ?>

					<?=$folder;?>

				</h5>

			<?php endforeach; ?>

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

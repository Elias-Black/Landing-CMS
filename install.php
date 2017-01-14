<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getPerms($path)
{
	return substr( sprintf( '%o', fileperms($path) ), -3 );
}

function getStatus($url)
{

	$HTTPheaders = @get_headers("http://{$_SERVER['SERVER_NAME']}/$url");

	$HTTPSheaders = @get_headers("https://{$_SERVER['SERVER_NAME']}/$url");

	if( $HTTPheaders && strpos($HTTPheaders[0], '403') || $HTTPSheaders && strpos($HTTPSheaders[0],'403') )
		return true;

}

$db = array(
	'cms/_db/password.php',
	'cms/_db/private.php',
	'cms/_db/public.php'
);

$upload = array(
	'web/_cms/uploads/tinymce/source',
	'web/_cms/uploads/tinymce/thumbs'
);

$closed = array(
	'cms/_db/password.php',
	'cms/_db/private.php',
	'cms/_db/public.php',
	'cms/_templates/',
	'cms/_classes/'
);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Landing CMS</title>
	<meta charset="utf-8">
	<meta name='HandheldFriendly' content='True'>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/web/_cms/css/main.css">
	<link rel="stylesheet" href="/web/_cms/css/bootstrap.min.css">
	<script src="/web/_cms/js/main.js"></script>
	<meta name='copyright' content='Landing CMS'>
	<meta name="description" content="Free CMS for Landing">
	<meta name="keywords" content="CMS, Landing, Free">
	<meta name="author" content="Ilia Chernykh">
	<meta name="generator" content="Landing CMS 0.0.1" />
</head>
<body>

<!-- HEADER begin -->
	<nav class="navbar navbar-default navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="collapsed navbar-toggle" onclick="collapseMenu();">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="/cms/" class="navbar-brand">Landing CMS</a>
			</div>
			<div class="collapse navbar-collapse" id="js_collapse">
				<ul class="right nav navbar-nav">
					<li>
						<a href="/" target="_blank">Website</a>
					</li>
					<li>
						<a href="#">About CMS</a>
					</li>
					<li>
						<a href="#">Donate</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
<!-- HEADER end -->

<!-- MAIN begin -->
	<div class="container">
		<div class="row">
			<h2>Web-server config</h2>
			<h5>Close user's access to:</h5>
<?php

foreach ($closed as $folder)
{

	$res = getStatus($folder) ? '<span class="label label-success">YES</span>' : '<span class="label label-danger">NO</span>';

	echo "<h5>$res NECESSARY 403: $folder</h5>";

}

?>
			<h2>Database permissions</h2>
<?php

foreach ($db as $file)
{

	$res = getPerms($file) == 666 ? '<span class="label label-success">YES</span>' : '<span class="label label-danger">NO</span>';

	echo "<h5>$res NECESSARY 666: $file</h5>";

}

?>
			<h2>Uploads permissions</h2>
<?php

foreach ($upload as $folder)
{

	$res = getPerms($folder) == 777 ? '<span class="label label-success">YES</span>' : '<span class="label label-danger">NO</span>';

	echo "<h5>$res NECESSARY 777: $folder</h5>";

}

?>
		</div>
	</div>
<!-- MAIN end -->

<!-- FOOTER begin -->
	<nav class="navbar navbar-default navbar-fixed-bottom">
	  <div class="container">
	    <div class="col-sm-12 text-center navbar-text">
	        2017 &copy; Landing CMS
	    </div>
	  </div>
	</nav>
<!-- FOOTER end -->

</body>
</html>
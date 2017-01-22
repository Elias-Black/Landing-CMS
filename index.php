<?php

// Connecting the DataBase
require_once('cms/_db/public.php');

// Connecting a module
require_once('modules/rand_num.php');

?>

<!DOCTYPE html>
<html>

	<head>

		<title>Demo</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<style>

			html, body, #header, #main
			{
				padding: 0;
				margin: 0;
				height: 100%;
				font-family: Helvetica, Tahoma, Arial;
			}

			#header, #footer
			{
				text-align: center;
			}

			#header
			{
				background-color: #5bc0de;
				font-size: 40px;
				color: #fff;
			}

			#main
			{
				padding-left: 15%;
				padding-right: 15%;
			}

			#footer
			{
				background-color: #f5f5f5;
				height: 100px;
			}

			.center
			{
				position: relative;
				top: 50%;
				transform: translateY(-50%);
			}

		</style>

	</head>

	<body>
		<div id="header">
			<div class="center">
				<?php echo $get['title'] ? $get['title'] : 'Create field with \'title\' alias'; ?>
			</div>
		</div>
		<div id="main">
			<div class="center">
				<?php echo $get['main'] ? $get['main'] : 'Create field with \'main\' alias'; ?>
				<p>Random module: <?php echo $rand_num; ?></p>
			</div>
		</div>
		<div id="footer">
			<div class="center">
				<?php echo $get['footer'] ? $get['footer'] : 'Create field with \'footer\' alias'; ?>
			</div>
		</div>
	</body>

</html>
<?php

// Connecting the public controller
require_once( realpath($_SERVER["DOCUMENT_ROOT"]) . '/web/index.php' );

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
				<?php if( isset($get['title']) ): ?>
					<?=$get['title'];?>
				<?php else: ?>
					Create <i>String</i> field with 'title' alias
				<?php endif; ?>
			</div>
		</div>
		<div id="main">
			<div class="center">

				<?php if( isset($get['main']) ): ?>
					<?=$get['main'];?>
				<?php else: ?>
					Create <i>Multiline/WYSIWYG</i> field with 'main' alias
				<?php endif; ?>

				<p>
					<?php if( isset($get['main_group']) && is_array($get['main_group']) ): ?>
						<p>
							<b>Items:</b>
						</p>
						<ul>
							<?php foreach($get['main_group'] as $item): ?>
								<li><?=$item;?></li>
							<?php endforeach; ?>
						</ul>
					<?php else: ?>
						Create <i>Group of fields</i> with 'main_group' alias and some <i>String</i> items
					<?php endif; ?>
				</p>

				<p>Random module: <?php echo $rand_num; ?></p>

			</div>
		</div>
		<div id="footer">
			<div class="center">
				<?php if( isset($get['footer']) ): ?>
					<?=$get['footer'];?>
				<?php else: ?>
					Create <i>String</i> field with 'footer' alias
				<?php endif; ?>
			</div>
		</div>
	</body>

</html>
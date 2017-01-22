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
	</head>

	<body>

		<h2><?php echo $get['new']; ?></h2>

		<?php if($rand_num): ?>
		<p>
			<b>Random number module:</b> <?php echo $rand_num; ?>
		</p>
		<?php endif; ?>

	</body>

</html>
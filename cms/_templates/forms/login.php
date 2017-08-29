<?php defined('CORE') OR die('403'); ?>

<form method="POST" action="">

	<h2>Login</h2>

	<div class="form-group">
		<input
		 id="password"
		 class="form-control"
		 name="password"
		 type="password"
		 title="Enter the password here."
		 placeholder="Enter the password here"
		 autofocus
		 required
		 />
	</div>

	<div class="buttons">
		<?php echo Utils::render(
			'elements/button_blue.php',
			 array('text' => 'Log in', 'title' => 'Log in and go to the CMS.')
		); ?>

		<?php echo Utils::render(
			'elements/button_white.php',
			 array(
			 	'text' => 'Cancel',
			 	'title' => 'Go to the site.',
			 	'url' => Utils::getLink()
			 )
		); ?>
	</div>

</form>

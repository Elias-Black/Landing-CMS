<?php defined('CORE') OR die('403'); ?>

<form method="POST" action="">

	<h2>Password</h2>

	<div class="form-group">
		<label for="pass1">New password</label>
		<input
		 id="pass1"
		 class="form-control"
		 name="pass1"
		 type="password"
		 title="Enter new password here."
		 placeholder="New password"
		 autofocus
		 required
		 />
		<div class="help-block">Please enter a new password here.</div>
	</div>

	<div class="form-group">
		<label for="pass2">Confirm new password</label>
		<input
		 id="pass2"
		 class="form-control"
		 name="pass2"
		 type="password"
		 title="Enter new password here again."
		 placeholder="New password again"
		 required
		 />
		<div class="help-block">Please enter the new password again here.</div>
	</div>

	<div class="buttons">
		<?php echo Utils::render(
			'elements/button_blue.php',
			 array('text' => 'Save', 'title' => 'Save new password.')
		); ?>

		<?php echo Utils::render(
			'elements/button_white.php',
			 array(
			 	'text' => 'Cancel',
			 	'title' => 'Cancel password changing.',
			 	'url' => Utils::getLink('cms/')
			 )
		); ?>
	</div>

</form>

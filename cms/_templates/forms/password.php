<?php defined('CORE') OR die('403'); ?>

<form method="POST" action="">

	<h2>Password</h2>

	<div class="form-group">
		<label for="pwd1">New password</label>
		<input
		 id="pwd1"
		 class="form-control"
		 name="pwd1"
		 type="password"
		 title="Enter new password here."
		 placeholder="New password"
		 value="<?=Utils::pr($vars['sent_data']['pwd1']);?>"
		 autofocus
		 required
		 />
		<div class="help-block">Please enter a new password here.</div>
	</div>

	<div class="form-group">
		<label for="pwd2">Confirm new password</label>
		<input
		 id="pwd2"
		 class="form-control"
		 name="pwd2"
		 type="password"
		 title="Enter new password here again."
		 placeholder="New password again"
		 value="<?=Utils::pr($vars['sent_data']['pwd2']);?>"
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

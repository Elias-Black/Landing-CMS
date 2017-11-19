<?php defined('CORE') OR die('403'); ?>

<form method="POST" action="" id="js_main_form">

	<h2>Content</h2>


	<div id="js_empty_main_form" class="<?=empty($vars['fields']) ? '' : 'hidden';?>">

		<div class="alert alert-info">No Fields.</div>
		<div class="buttons">
			<?php echo Utils::render(
				'elements/button_green.php',
				 array(
				 	'text' => 'Add Field',
				 	'title' => 'Go to adding a new Field.',
				 	'url' => Utils::getLink('cms/add-field/')
				 )
			); ?>
		</div>

	</div>

	<div id="js_filled_main_form" class="<?=empty($vars['fields']) ? 'hidden' : '';?>">

		<div class="buttons">
			<?php echo Utils::render(
				'elements/button_blue.php',
				 array('text' => 'Save', 'title' => 'Save all Fields.')
			); ?>
			<?php echo Utils::render(
				'elements/button_green.php',
				 array(
				 	'text' => 'Add Field',
				 	'title' => 'Go to adding a new Field.',
				 	'url' => Utils::getLink('cms/add-field/')
				 )
			); ?>
		</div>

		<?php echo Utils::render(
			"fields/fields_group.php",
			$vars);
		?>

		<div class="buttons">
			<?php echo Utils::render(
				'elements/button_blue.php',
				 array('text' => 'Save', 'title' => 'Save all Fields.')
			); ?>
			<?php echo Utils::render(
				'elements/button_green.php',
				 array(
				 	'text' => 'Add Field',
				 	'title' => 'Go to adding a new Field.',
				 	'url' => Utils::getLink('cms/add-field/')
				 )
			); ?>
		</div>

	</div>

</form>

<a href="#" class="scroll-to-top" title="Scroll to Top.">
	<img src="<?=Utils::getLink('assets/_cms/img/icon-up-black.png');?>" alt="Scroll to Top">
</a>

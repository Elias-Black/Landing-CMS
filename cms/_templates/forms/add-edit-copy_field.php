<?php defined('CORE') OR die('403'); ?>

<form method="POST" action="">

	<h2><?=Utils::pr($vars['page_header']);?></h2>

	<div class="form-group<?=Utils::pr($vars['invalid_fields']['parent']) ? ' has-error' : '';?>">
		<label for="parent">Parent<span title="This is a required field.">&nbsp;*</span></label>
		<select
		 id="parent"
		 class="form-control"
		 name="parent"
		 title="Select a Parent for the Field."
		 onchange="confirmLeave('on');">
			<?php

				$rm_yourself = Utils::pr($vars['rm_yourself_from_parents'], false);
				$parents = Content::getAllParents($rm_yourself);

				foreach($parents as $parent):

			?>

				<?php

					$selected_parent = '';

					if( Utils::pr($vars['sent_data']['parent']) == $parent['path'] )
					{
						$selected_parent = ' selected';
					}

				?>

				<option value="<?=Utils::pr($parent['path']);?>"<?=Utils::pr($selected_parent);?>><?=Utils::pr($parent['title']);?></option>

			<?php endforeach; ?>
		</select>

		<div class="help-block">Select a Parent of the new Field.</div>
	</div>

	<div class="form-group<?=Utils::pr($vars['invalid_fields']['type']) ? ' has-error' : '';?>">
		<label for="type">Type<span title="This is a required field.">&nbsp;*</span></label>
		<select
		 id="type"
		 class="form-control"
		 name="type"
		 title="Select a Type of the Field."
		 onchange="confirmLeave('on');"
		 required>
			<?php foreach(Content::getFieldsTypes() as $field_name => $field_value): ?>

				<?php

					$selected_type = '';

					if( Utils::pr($vars['sent_data']['type']) == $field_value )
					{
						$selected_type = ' selected';
					}

				?>

				<option value="<?=Utils::pr($field_value);?>"<?=Utils::pr($selected_type);?>><?=Utils::pr($field_name);?></option>

			<?php endforeach; ?>
		</select>

		<div class="help-block">Select a Type of the new Field.</div>
	</div>

	<div class="form-group<?=Utils::pr($vars['invalid_fields']['alias']) ? ' has-error' : '';?>">
		<label for="alias">Alias<span title="This is a required field.">&nbsp;*</span></label>
		<input
		 id="alias"
		 class="form-control"
		 name="alias"
		 type="text"
		 value="<?=Utils::pr($vars['sent_data']['alias']);?>"
		 title="Enter an Alias for the Field here."
		 placeholder="Enter an Alias here"
		 onchange="confirmLeave('on');"
		 required
		 />
		<div class="help-block">It is necessary for calling the new Field in the backend.</div>
	</div>

	<div class="form-group<?=Utils::pr($vars['invalid_fields']['title']) ? ' has-error' : '';?>">
		<label for="title">Title<span title="This is a required field.">&nbsp;*</span></label>
		<input
		 id="title"
		 class="form-control"
		 name="title"
		 type="text"
		 value="<?=Utils::pr($vars['sent_data']['title']);?>"
		 title="Enter a Title for the Field here."
		 placeholder="Enter a Title here"
		 onchange="confirmLeave('on');"
		 required
		 />
		<div class="help-block">The name of the new Field in Admin panel.</div>
	</div>

	<div class="form-group<?=Utils::pr($vars['invalid_fields']['description']) ? ' has-error' : '';?>">
		<label for="description">Description</label>
		<input
		 id="description"
		 class="form-control"
		 name="description"
		 type="text"
		 value="<?=Utils::pr($vars['sent_data']['description']);?>"
		 title="Enter a Description for the Field here."
		 placeholder="Enter a Description here"
		 onchange="confirmLeave('on');"
		 />
		<div class="help-block">The description of the new Field in admin panel.</div>
	</div>

	<div class="form-group<?=Utils::pr($vars['invalid_fields']['required']) ? ' has-error' : '';?>">
		<input
		 name="required"
		 type="hidden"
		 value="off"
		 />

		<input
		 id="required"
		 class="magic-checkbox"
		 name="required"
		 type="checkbox"
		 title="Check this box if the Field is required for filling."
		 onchange="confirmLeave('on');"
		 <?=Utils::pr($vars['sent_data']['required']) == 'on' ? 'checked' : '';?>
		 />

		<label
		 for="required"
		 title="Check this box if the Field is required for filling."
		>
			Required Field<span title="This is a required field.">&nbsp;*</span>
		</label>
		<p class="help-block">Does not work for Fields Groups.</p>
	</div>

	<div class="buttons">
		<?php echo Utils::render(
			'elements/button_blue.php',
			 array(
			 	'text' => 'Save',
			 	'title' => Utils::pr($vars['save_btn_text'])
			 )
		); ?>
		<?php echo Utils::render(
			'elements/button_white.php',
			 array(
			 	'text' => 'Cancel',
			 	'title' => Utils::pr($vars['cancel_btn_text']),
			 	'url' => Utils::getLink('cms/')
			 )
		); ?>
	</div>

</form>

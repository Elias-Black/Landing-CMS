<?php defined('CORE') OR die('403'); ?>

<?php foreach($vars['fields'] as $alias => $field): ?>

	<?php
		$field['parents'] = Utils::pr($vars['parents'], array());
		$field['parents'][] = $alias;
		$field['name'] = Content::getNameAsString($field['parents']);
	?>

	<?php if( $field['type'] == 'fields_group' ): ?>

		<div class="form-group">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="pull-right panel-controls">
						<a
						 class="group-controll js_move_field_up"
						 href="?moveFieldUp=<?=$field['name'];?>"
						 title="Move &laquo;<?=$field['title'];?>&raquo; Group Up.">
							<img src="<?=Utils::getLink('web/_cms/img/icon-up-white.png');?>" alt="Up">
						</a>
						<a
						 class="group-controll js_move_field_down"
						 href="?moveFieldDown=<?=$field['name'];?>"
						 title="Move &laquo;<?=$field['title'];?>&raquo; Group Down.">
							<img src="<?=Utils::getLink('web/_cms/img/icon-down-white.png');?>" alt="Down">
						</a>
						<a
						 class="group-controll"
						 href="<?=Utils::getLink('cms/edit-field/?name='.$field['name']);?>"
						 title="Edit &laquo;<?=$field['title'];?>&raquo; Group.">
							<img src="<?=Utils::getLink('web/_cms/img/icon-edit-white.png');?>" alt="Edit">
						</a>
						<a
						 class="group-controll js_group_toggle"
						 href="?<?=Utils::pr($field['open'])?'closeGroup':'openGroup';?>=<?=$field['name'];?>"
						 title="Open/Close &laquo;<?=$field['title'];?>&raquo; Group.">
							<?php $src = Utils::pr($field['open']) ? Utils::getLink('web/_cms/img/icon-collapse.png') : Utils::getLink('web/_cms/img/icon-increase.png');?>
							<img
							 data-opened="<?=Utils::getLink('web/_cms/img/icon-collapse.png');?>"
							 data-closed="<?=Utils::getLink('web/_cms/img/icon-increase.png');?>"
							 src="<?=$src;?>"
							 alt="Switch">
						</a>
						<a
						 class="group-controll danger"
						 href="?delete=<?=$field['name'];?>"
						 title="Delete &laquo;<?=$field['title'];?>&raquo; Group."
						 onclick="confirm('Do you want to delete &laquo;<?=$field['title'];?>&raquo; Group?') && deleteField('<?=$field['name'];?>'); return false;">
							<img src="<?=Utils::getLink('web/_cms/img/icon-delete-white.png');?>" alt="Delete">
						</a>
					</div>
					<h3 class="panel-title"><?=$field['title'];?></h3>
				</div>
				<div
				 id="<?=$field['name'];?>"
				 class="panel-body<?=Utils::pr($field['open'])?'':' hidden';?>">

					<?php
						$info['parents'] = $field['parents'];
						$info['fields'] = $field['output'];

						echo Utils::render(
							"fields/{$field['type']}.php",
							 $info
						);
					?>

				</div>
			</div>
			<p class="help-block"><?=$field['description'];?></p>
		</div>

	<?php else: ?>

		<div class="form-group">
			<div class="pull-right panel-controls">
				<a
				 href="?moveFieldUp=<?=$field['name'];?>"
				 title="Move &laquo;<?=$field['title'];?>&raquo; Field Up."
				 class="field-controll js_move_field_up">
					<img src="<?=Utils::getLink('web/_cms/img/icon-up-black.png');?>" alt="Up">
				</a>
				<a
				 href="?moveFieldDown=<?=$field['name'];?>"
				 title="Move &laquo;<?=$field['title'];?>&raquo; Field Down."
				 class="field-controll js_move_field_down">
					<img src="<?=Utils::getLink('web/_cms/img/icon-down-black.png');?>" alt="Down">
				</a>
				<a
				 href="<?=Utils::getLink('cms/edit-field/?name='.$field['name']);?>"
				 title="Edit &laquo;<?=$field['title'];?>&raquo; Field."
				 class="field-controll">
					<img src="<?=Utils::getLink('web/_cms/img/icon-edit-black.png');?>" alt="Edit">
				</a>
				<a
				 href="?delete=<?=$field['name'];?>"
				 title="Delete &laquo;<?=$field['title'];?>&raquo; Field."
				 class="field-controll danger"
				  onclick="confirm('Do you want to delete &laquo;<?=$field['title'];?>&raquo; Field?') && deleteField('<?=$field['name'];?>'); return false;">
					<img src="<?=Utils::getLink('web/_cms/img/icon-delete-red.png');?>" alt="Delete">
				</a>
			</div>
			<?php echo Utils::render(
				"fields/{$field['type']}.php",
				 $field);
			?>
			<p class="help-block"><?=$field['description'];?></p>
		</div>
	<?php endif; ?>

<?php endforeach; ?>

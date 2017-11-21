<?php defined('CORE') OR die('403'); ?>

<input
 name="<?=$vars['name'];?>"
 type="hidden"
 value="off"
 />

<input
 id="<?=$vars['name'];?>"
 class="magic-checkbox"
 name="<?=$vars['name'];?>"
 type="checkbox"
 title="Select a value for the &laquo;<?=$vars['title'];?>&raquo; Field."
 onchange="confirmLeave('on');"
 <?=($vars['output'] == 'on') ? 'checked' : '';?>
 />

<label
 for="<?=$vars['name'];?>"
 title="Select a value for &laquo;<?=$vars['title'];?>&raquo; Field."
>
	<?=$vars['title'];?><?=$vars['required_str'];?>
</label>

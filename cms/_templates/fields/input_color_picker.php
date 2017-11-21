<?php defined('CORE') OR die('403'); ?>

<label for="<?=$vars['name'];?>"><?=$vars['title'];?><?=$vars['required_str'];?></label>

<div class="input-group no-js-color-picker">
	<input
	 id="<?=$vars['name'];?>"
	 class="form-control"
	 name="<?=$vars['name'];?>"
	 type="text"
	 value="<?=$vars['output'];?>"
	 title="Enter a color-code (HEX, RGB, RGBA, HSL, HSLA) for &laquo;<?=$vars['title'];?>&raquo; Field here."
	 placeholder="Enter a color-code (HEX, RGB, RGBA, HSL, HSLA) here"
	 onchange="confirmLeave('on');"
	 />
	<span
	 class="input-group-addon"
	 role="button"
	 tabindex="0"
	 title="Click or Tap here to open the color-picker."
	 style="background-color: <?=$vars['output'];?>;"></span>
</div>

<noscript>
	<input
	 class="form-control"
	 name="<?=$vars['name'];?>"
	 type="color"
	 value="<?=$vars['output'];?>"
	 title="Click or Tap here to open the color-picker."
	 placeholder="Enter a color-code (HEX, RGB, RGBA, HSL, HSLA) here"
	 />
</noscript>

<script>
	CPinit('<?=$vars['name'];?>', '<?=$vars['output'];?>');
</script>

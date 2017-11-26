<?php defined('CORE') OR die('403'); ?>

<label for="<?=$vars['name'];?>"><?=$vars['title'];?><?=$vars['required_str'];?></label>

<div class="input-group">
	<?php if( Utils::pr($vars['output']) ): ?>
		<span class="input-group-addon"><a href="<?=$vars['output'];?>" target="_blank">Open file</a></span>
	<?php endif; ?>
	<input
	 id="<?=$vars['name'];?>"
	 class="form-control"
	 name="<?=$vars['name'];?>"
	 type="text"
	 value="<?=$vars['output'];?>"
	 title="Enter a path to a file for &laquo;<?=$vars['title'];?>&raquo; Field here."
	 placeholder="Enter a path to a file here"
	 data-iframe-id="js_<?=$vars['name'];?>_iframe"
	 onchange="confirmLeave('on');" />
	<span
	 id="js_<?=$vars['name'];?>_btn"
	 class="input-group-addon"
	 role="button"
	 tabindex="0"
	 title="Click or Tap here to open the file-uploader."
	 data-iframe-id="js_<?=$vars['name'];?>_iframe">Choose a file</span>
</div>

<div class="hidden">
	<span
	 id="js_<?=$vars['name'];?>_close"
	 class="close-iframe"
	 role="button"
	 tabindex="0"
	 title="Click or Tap here to close the file-uploader."
	 data-iframe-id="js_<?=$vars['name'];?>_iframe">Close</span>
	<iframe
	 id="js_<?=$vars['name'];?>_iframe"
	 width="100%"
	 height="550"
	 frameborder="0"
	 data-src="<?=Utils::getLink('assets/vendor/responsive_filemanager_9.12.1/filemanager/dialog.php?type=2&field_id='.$vars['name'].'&relative_url=1&lang=en_EN');?>">
	</iframe>
</div>

<script type="text/javascript">

	var inp_id = '<?=$vars['name'];?>';
	var btn_id = 'js_<?=$vars['name'];?>_btn';
	var cls_id = 'js_<?=$vars['name'];?>_close';

	fileUpInit(inp_id, btn_id, cls_id);

</script>

<?php defined('CORE') OR die('403'); ?>

<!-- TinyMCE 4.x -->
<script type="text/javascript">

	tinymce.init({
		selector: ".js_wysiwyg_<?=$vars['name'];?>",

		plugins: [
			"advlist autolink link image lists charmap print preview hr anchor pagebreak",
			"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
			"table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
		],
		toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
		toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor	| print preview code fontselect",
		image_advtab: true ,

		external_filemanager_path: "<?=Utils::getLink('assets/vendor/responsive_filemanager_9.12.1/filemanager/');?>",
		filemanager_title: "Responsive Filemanager",
		external_plugins: { "filemanager" : "<?=Utils::getLink('assets/vendor/responsive_filemanager_9.12.1/filemanager/plugin.min.js');?>"},
		setup: function(ed) {
			ed.on('change', function(ed) {
				confirmLeave('on');
			});
		}
	});

</script>
<!-- /TinyMCE -->

<label for="<?=$vars['name'];?>"><?=$vars['title'];?><?=$vars['required_str'];?></label>

<noscript>
	<p>Your browser doesn't support JavaScript, so you do not see the visual editor here. But you can still use <a href="https://developer.mozilla.org/en/docs/Web/HTML" target="_blank">HTML</a> to manually edit the content in this Field.</p>
</noscript>

<textarea
 id="<?=$vars['name'];?>"
 class="form-control js_wysiwyg_<?=$vars['name'];?>"
 name="<?=$vars['name'];?>"
 rows="7"
 title="Enter a text for &laquo;<?=$vars['title'];?>&raquo; Field here."
 placeholder="Enter a text here"><?=$vars['output'];?></textarea>
 
<?php defined('CORE') OR die('403'); ?>

<label for="<?=$vars['name'];?>"><?=$vars['title'];?><?=$vars['required_str'];?></label>

<textarea
 id="<?=$vars['name'];?>"
 class="form-control"
 name="<?=$vars['name'];?>"
 rows="7"
 title="Enter a text for &laquo;<?=$vars['title'];?>&raquo; Field here."
 placeholder="Enter a text here"
 onchange="confirmLeave('on');"><?=$vars['output'];?></textarea>
 
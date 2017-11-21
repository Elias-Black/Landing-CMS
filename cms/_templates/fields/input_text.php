<?php defined('CORE') OR die('403'); ?>

<label for="<?=$vars['name'];?>"><?=$vars['title'];?><?=$vars['required_str'];?></label>

<input
 id="<?=$vars['name'];?>"
 class="form-control"
 name="<?=$vars['name'];?>"
 type="text"
 value="<?=$vars['output'];?>"
 title="Enter a text for &laquo;<?=$vars['title'];?>&raquo; Field here."
 placeholder="Enter a text here"
 onchange="confirmLeave('on');"
 />
 
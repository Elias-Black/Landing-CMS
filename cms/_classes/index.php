<?php

/*

***** DEBUG MODE *****

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

*/

define('ROOT_PATH',			realpath($_SERVER["DOCUMENT_ROOT"]) );

define('DB_PASSWORD_PATH',	ROOT_PATH. '/cms/_db/password.php');
define('DB_PRIVATE_PATH',	ROOT_PATH. '/cms/_db/private.php');
define('DB_PUBLIC_PATH',	ROOT_PATH. '/cms/_db/public.php');

define('SECURE_TEXT',		'<?php die; ?>');
define('SECURE_LENGTH',		13);

define('RANDOM_STR_LENGTH',	-10);

define('AUTH_EXPIRE',	31536000);



require_once("utils.class.php");

require_once("content.class.php");

require_once("user.class.php");



$message = User::auth();



$field_types = array(
	'String'			=>	'input_text',
	'HTML (WYSIWYG)'	=>	'textarea_html',
	'Multiline text'	=>	'textarea_text',
	'Checkbox'			=>	'input_checkbox',
	'Color Picker'		=>	'input_color_picker',
	'File Uploader'		=>	'input_file_uploader',
);

define('FIELD_TYPES',	serialize($field_types));

?>
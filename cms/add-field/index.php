<?php

/**
 * Landing CMS
 *
 * A simple CMS for Landing Pages.
 *
 * @package    Landing CMS
 * @author     Ilia Chernykh <landingcms@yahoo.com>
 * @copyright  2017, Landing CMS (https://github.com/Elias-Black/Landing-CMS)
 * @license    https://opensource.org/licenses/LGPL-2.1  LGPL-2.1
 * @version    Release: 0.0.4
 * @link       https://github.com/Elias-Black/Landing-CMS
 */

// Connecting main classes
require_once('../_classes/index.php');

defined('CORE') OR die('403');

$data = array();

// Catching form submit
if( !empty($_POST) )
	$data = Content::addNewFieldAction();

$data['page_header'] = 'Adding a new Field';
$data['save_btn_text'] = 'Save and add the new Field.';
$data['cancel_btn_text'] = 'Cancel adding the new Field.';

// Render form of adding new field
$content = Utils::render(
	'forms/add-edit-copy_field.php',
	 $data
);

// Printing page
echo Utils::renderIndex($content, $data);

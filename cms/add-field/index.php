<?php

// Connecting main classes
require_once('../_classes/index.php');

$data = array();

// Catching form submit
if( !empty($_POST) )
	$data = Content::addNewFieldAction();

$data['page_header'] = 'Adding a new field';

// Render form of adding new field
$content = Utils::render(
	'forms/add-edit_field.html',
	 $data
);

// Printing page
echo Utils::renderIndex($content, $data);

?>
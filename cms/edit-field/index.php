<?php

// Connecting main classes
require_once('../_classes/index.php');

$data = array();

// Catching form submit
if( !empty($_POST) )
	$data = Content::updateField();
else
	$data = Content::editField();

$data['page_header'] = 'Field editing';
$data['save_btn_text'] = 'Save changes of the Field.';
$data['cancel_btn_text'] = 'Cancel changes of the Field.';

// Render form of adding new field
$content = Utils::render(
	'forms/add-edit_field.html',
	 $data
);

// Printing page
echo Utils::renderIndex($content, $data);

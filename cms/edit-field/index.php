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

// Render form of adding new field
$content = Utils::render(
	'forms/add.html',
	 $data
);

// Printing page
echo Utils::renderIndex($content, $data);

?>
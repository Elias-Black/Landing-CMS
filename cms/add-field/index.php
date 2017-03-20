<?php

// Connecting main classes
require_once('../_classes/index.php');

// Catching form submit
if( !empty($_POST) )
	$message = Content::addNewField();

// Render form of adding new field
$content = Utils::render(
	'forms/add.html',
	 array('fields' => $message['fields'])
);

// Printing page
echo Utils::renderIndex($content, $message);

?>
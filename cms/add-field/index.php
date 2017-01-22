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
echo Utils::render(
	'index.html',
	 array('content' => $content, 'error_message' => $message['error'], 'success_message' => $message['success'])
);

?>
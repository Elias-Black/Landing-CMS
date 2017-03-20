<?php

// Connecting main classes
require_once('_classes/index.php');

// Deleting the block
if( isset($_GET['delete']) )
	Content::deleteField($_GET['delete']);

// Catching form submit
if( !empty($_POST) )
	Content::updateContent();

// Render form of fields edit
$content = Content::getMainForm();

// Printing page
echo Utils::render(
	'index.html',
	 array('content' => $content)
);

?>
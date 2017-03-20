<?php

// Connecting main classes
require_once('../_classes/index.php');

if( isset($_GET['logout']) )
	User::logout();

// Render login form
$content = Utils::render(
	'forms/login.html',
	 array()
);

// Printing page
echo Utils::render(
	'index.html',
	 array('content' => $content, 'error_message' => $message['error'], 'success_message' => $message['success'])
);

?>
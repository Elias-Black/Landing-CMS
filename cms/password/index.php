<?php

// Connecting main classes
require_once('../_classes/index.php');

if( !defined('CORE') ) { die('403'); }

// Catching form submit
if( !empty($_POST) )
	$message = User::updatePassword();

// Render form of changing password
$content = Utils::render(
	'forms/password.php',
	 array()
);

// Printing page
echo Utils::renderIndex($content, $message);

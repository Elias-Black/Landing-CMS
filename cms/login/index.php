<?php

// Connecting main classes
require_once('../_classes/index.php');

if( !defined('CORE') ) { die('403'); }

// Logout
if( isset($_GET['logout']) )
	User::logout();

// Redirect to the CMS if logged in
if( defined('IS_LOGIN') )
	Utils::redirect( Utils::getLink('cms/') );

// Render login form
$content = Utils::render(
	'forms/login.php',
	 array()
);

// Printing page
echo Utils::renderIndex($content, $message);

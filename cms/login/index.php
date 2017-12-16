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
 * @version    Release: 0.0.6
 * @link       https://github.com/Elias-Black/Landing-CMS
 */

// Connecting main classes
require_once('../_classes/index.php');

defined('CORE') OR die('403');

// Logout
if( isset($_GET['logout']) )
	User::logout();

// Redirect to the CMS if logged in
if( defined('IS_LOGIN') )
	Utils::redirect('cms/');

// Render login form
$content = Utils::render('forms/login.php');

// Printing page
echo Utils::renderIndex($content, $message);

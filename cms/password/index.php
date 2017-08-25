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
 * @version    Release: 0.0.4
 * @link       https://github.com/Elias-Black/Landing-CMS
 */

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

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


// Path to Public DB from the root folder
$public_db_path = 'cms/_db/public.php';

// Nesting of this file to the root folder
$controller_file_nesting = 1;

// Length security content prefix (dying code)
$security_length = 13;


$root_path = realpath( dirname(__FILE__) . str_repeat('/..', $controller_file_nesting) ) . '/';

$public_db_path = $root_path . $public_db_path;


$public_db_content = array();

if( is_readable($public_db_path) === TRUE )
{

	$public_db_content = file_get_contents($public_db_path);
	$public_db_content = unserialize( base64_decode( substr($public_db_content, $security_length) ) );

}


$get = $public_db_content;

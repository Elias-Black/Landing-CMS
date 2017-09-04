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

$public_db_path = 'cms/_db/public.php';

$controller_file_nesting = 1;

$root_path = realpath( dirname(__FILE__) . str_repeat('/..', $controller_file_nesting) ) . '/';

$public_db_path = $root_path . $public_db_path;

$public_db_content = file_get_contents($public_db_path);



$get = unserialize($public_db_content);

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
require_once('_classes/index.php');

defined('CORE') OR die('403');

// Deleting the block
if( isset($_GET['delete']) )
	Content::deleteFieldAction($_GET['delete']);

// Moving Field Up
if( isset($_GET['moveFieldUp']) )
	Content::moveFieldUpDown($_GET['moveFieldUp'], 'up');

// Moving Field Down
if( isset($_GET['moveFieldDown']) )
	Content::moveFieldUpDown($_GET['moveFieldDown'], 'down');

// Moving Field Down
if( isset($_GET['openGroup']) )
	Content::openCloseGroup($_GET['openGroup'], 'open');

// Moving Field Down
if( isset($_GET['closeGroup']) )
	Content::openCloseGroup($_GET['closeGroup'], 'close');

// Catching form submit
if( !empty($_POST) )
	$message = Content::updateContent();

// Render form of Fields edit
$content = Content::getMainForm($message);

if( isset($content['error']) && $content['error'] === true)
{
	$message = $content;
	$content = '';
}

// Printing page
echo Utils::renderIndex($content, $message);

<?php

// Connecting main classes
require_once('_classes/index.php');

if( !defined('CORE') ) { die('403'); }

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
	Content::updateContent();

// Render form of Fields edit
$content = Content::getMainForm();

// Printing page
echo Utils::renderIndex($content, $message);

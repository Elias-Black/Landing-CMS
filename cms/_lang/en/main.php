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

defined('CORE') OR die('403');

// Messages for User Class
$MESSAGE['user:invalid_password']		= 'Invalid password.';
$MESSAGE['user:invalid_data']			= 'Invalid data.';
$MESSAGE['user:all_fields_required']	= 'All fields are required.';
$MESSAGE['user:passwords_not_match']	= 'Password and Confirm Password doesn\'t match.';
$MESSAGE['user:create_password']		= 'Create password.';
$MESSAGE['user:saved_successfully']		= 'Password successfully saved.';

// Messages for DB Class
$MESSAGE['db:not_readable_password_db']	= 'Can not read from the Password database. Check permissions on <a href="%s" target="_blank">this</a> helper.';
$MESSAGE['db:not_writable_password_db']	= 'Can not be written to the Password database. Check permissions on <a href="%s" target="_blank">this</a> helper.';

$MESSAGE['db:not_readable_private_db']	= 'Can not read from the Private database. Check permissions on <a href="%s" target="_blank">this</a> helper.';
$MESSAGE['db:not_writable_private_db']	= 'Can not be written to the Private database. Check permissions on <a href="%s" target="_blank">this</a> helper.';

$MESSAGE['db:not_writable_public_db']	= 'Can not be written to the Public database. Check permissions on <a href="%s" target="_blank">this</a> helper.';

$MESSAGE['db:saved_successfully']		= 'Saved successfully.';

// Messages for Content Class
$MESSAGE['content:required_field']					= 'Field &laquo;%s&raquo; is required for filling.';
$MESSAGE['content:required_fields']					= 'Parent, Type, Alias and Title are required fields.';
$MESSAGE['content:required_field_tip']				= 'It\'s a required field.';
$MESSAGE['content:reserved_alias']					= 'Alias can\'t have this word. <a href="http://php.net/manual/en/reserved.variables.php" target="_blank">Here</a> are the majority forbidden words.';
$MESSAGE['content:forbidden_alias']					= 'Alias have forbidden word.';
$MESSAGE['content:alias_has_separator']				= 'Alias can\'t have this word.';
$MESSAGE['content:invalid_alias_string']			= 'Invalid Alias. Alias names follow the same <a href="http://php.net/manual/en/language.variables.basics.php" target="_blank">rules</a> as variable names in PHP.';
$MESSAGE['content:invalid_alias']					= 'Invalid Alias.';
$MESSAGE['content:parent_is_absent']				= 'This Parent is absent.';
$MESSAGE['content:invalid_parent']					= 'Invalid Parent.';
$MESSAGE['content:parent_already_has_the_child']	= 'The Parent already have a child with this Alias.';
$MESSAGE['content:copying_info']					= 'To continue copying the Field, specify another Parent or Alias, in order not to create a duplicate.';

// Messages for Controllers
$MESSAGE['edit_c:page_header']	= 'Field editing';
$MESSAGE['edit_c:save_btn']		= 'Save changes of the Field.';
$MESSAGE['edit_c:cancel_btn']	= 'Cancel changes of the Field.';

$MESSAGE['copy_c:page_header']	= 'Copying a Field';
$MESSAGE['copy_c:save_btn']		= 'Save and add the copied Field.';
$MESSAGE['copy_c:cancel_btn']	= 'Cancel Copying the Field.';

$MESSAGE['add_c:page_header']	= 'Adding a new Field';
$MESSAGE['add_c:save_btn']		= 'Save and add the new Field.';
$MESSAGE['add_c:cancel_btn']	= 'Cancel adding the new Field.';

// Messages for Templates
$MESSAGE['template:leave_prevention']	= 'Do you want to leave this page with unsaved Fields?';

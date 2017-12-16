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

/**
* The Class for working with Content
*/

class Content
{

	const NAME_SEPARATOR = '__S__';



	/* PUBLIC API */

	public static function getMainForm( $data = array() )
	{

		$db_content = DB::getPrivateContent(true);

		if($db_content['error'] === true)
		{
			return $db_content;
		}

		$data['fields'] = $db_content['db_content'];

		return Utils::render(
			'forms/main.php',
			 $data
		);

	}

	public static function addNewFieldAction()
	{

		$db_content = DB::getPrivateContent();

		if($db_content['error'] === false)
		{
			$db_content = $db_content['db_content'];
		}
		else
		{
			$db_content['sent_data'] = Utils::replaceQuotesInArray($_POST);

			return $db_content;
		}

		$field_is_added = self::addNewField($db_content);

		if( !isset($field_is_added['error']) && isset($field_is_added['db_content']) )
		{
			$db_content = $field_is_added['db_content'];
		}
		else
		{
			return $field_is_added;
		}

		$result = DB::updateContent($db_content);

		if($result['error'] === false)
		{
			Utils::redirect('cms/');
		}
		else
		{
			$result['sent_data'] = Utils::replaceQuotesInArray($_POST);

			return $result;
		}

	}

	public static function editField()
	{

		$result = array();

		$field_name = isset($_GET['name']) ? $_GET['name'] : false;

		if( !$field_name )
		{
			Utils::redirect('cms/');
		}

		$name_arr = self::getNameArray($field_name);

		$db_content = DB::getPrivateContent(true);

		if($db_content['error'] === false)
		{
			$db_content = $db_content['db_content'];
		}
		else
		{
			Utils::redirect('cms/');
		}

		$field_data = self::getField($db_content, $field_name);

		$field_exists = self::fieldExists($field_data);

		if( $field_exists['error'] == true )
		{
			Utils::redirect('cms/');
		}

		$result['sent_data'] = $field_data;

		$result['sent_data']['parent'] = $name_arr['parents'];

		$result['sent_data']['alias'] = $name_arr['alias'];

		return $result;

	}

	public static function copyField()
	{

		$result = array();

		$result = self::editField();

		$result['info_message'] = Utils::getMessage('content:copying_info');
		return $result;

	}

	public static function editOrCopyFieldAction($what_to_do)
	{

		$old_field_name = isset($_GET['name']) ? $_GET['name'] : false;

		if( !$old_field_name )
		{
			Utils::redirect('cms/');
		}


		$db_content = DB::getPrivateContent();

		if($db_content['error'] === false)
		{
			$db_content = $db_content['db_content'];
		}
		else
		{
			$db_content['sent_data'] = Utils::replaceQuotesInArray($_POST);

			return $db_content;
		}

		$new_field_data = self::getNewFieldData();

		$old_name_arr = self::getNameArray($old_field_name);



		$old_field = self::getField($db_content, $old_field_name);

		$field_exists = self::fieldExists($old_field);

		if( $field_exists['error'] == true )
		{
			Utils::redirect('cms/');
		}


		$old_position = 0;

		if( $old_name_arr['parents'] == $new_field_data['parent']['value'] && $what_to_do == 'edit' )
		{

			$old_parent = self::getParent($db_content, $old_name_arr['parents']);

			$old_position = self::getFieldPosition($old_parent, $old_name_arr['alias']);

		}


		if( $old_field['type'] == 'fields_group' && $new_field_data['type']['value'] != 'fields_group' )
		{
			$old_field_output = '';
		}
		elseif( $old_field['type'] != 'fields_group' && $new_field_data['type']['value'] == 'fields_group' )
		{
			$old_field_output = array();
		}
		else
		{
			$old_field_output = $old_field['output'];
		}


		if($what_to_do == 'edit')
		{

			$field_is_deleted = self::deleteField($db_content, $old_field_name);

			if( !isset($field_is_deleted['error']) && isset($field_is_deleted['db_content']) )
			{
				$db_content = $field_is_deleted['db_content'];
			}
			else
			{
				return $field_is_deleted;
			}

		}


		$field_is_added = self::addNewField($db_content);

		if( !isset($field_is_added['error']) && isset($field_is_added['db_content']) )
		{
			$db_content = $field_is_added['db_content'];
		}
		else
		{
			return $field_is_added;
		}


		$ref_new_parent = &self::getParent($db_content, $new_field_data['parent']['value']);

		$ref_new_field = &$ref_new_parent[$new_field_data['alias']['value']];

		$ref_new_field['output'] = $old_field_output;


		$new_position = self::getFieldPosition($ref_new_parent, $new_field_data['alias']['value']);

		self::moveFieldFromTo($ref_new_parent, $new_position, $old_position);


		$result = DB::updateContent($db_content);

		if($result['error'] === false)
		{
			Utils::redirect('cms/');
		}
		else
		{
			$result['sent_data'] = Utils::replaceQuotesInArray($_POST);

			return $result;
		}

	}

	public static function updateContent()
	{

		$updated = array();

		$db_content = DB::getPrivateContent();

		if($db_content['error'] === false)
		{
			$db_content = $db_content['db_content'];
		}
		else
		{
			Utils::redirect('cms/');
		}

		foreach ($_POST as $name => $value)
		{

			$ref_field = &self::getField($db_content, $name);

			if( isset($ref_field) )
			{

				if( Utils::pr($ref_field['required']) == 'on' && $value == '' && $ref_field['type'] != 'fields_group' )
				{
					$updated['error'] = true;
					$updated['error_message'] = Utils::getMessage( 'content:required_field', $ref_field['title'] );
					$updated['invalid_fields'][$name] = Utils::getMessage('content:required_field_tip');
				}

				$ref_field['output'] = $value;

			}

			unset($ref_field);

		}


		if( empty($updated) )
		{
			$updated = DB::updateContent($db_content);
		}

		return $updated;

	}

	public static function deleteFieldAction($field_name)
	{

		$result = array();

		$db_content = DB::getPrivateContent();

		if($db_content['error'] === false)
		{
			$db_content = $db_content['db_content'];

			$field_is_deleted = self::deleteField($db_content, $field_name);
		}
		else
		{
			$result = $db_content;
		}


		if( !isset($field_is_deleted['error']) && isset($field_is_deleted['db_content']) )
		{

			$db_content = $field_is_deleted['db_content'];

			$updated = DB::updateContent($db_content);

			if($updated['error'] === false)
			{
				$result['success'] = true;
			}
			else
			{
				$result = $updated;
				$result['success'] = false;
			}

		}
		else
		{
			$result['success'] = false;
		}

		if( Utils::isAJAX() )
		{

			$result = json_encode($result);

			exit($result);

		}
		elseif($result['success'] === true)
		{
			Utils::redirect('cms/');
		}
		else
		{
			return $result;
		}

	}

	public static function getAllParents($remove_yourself = false)
	{

		$parents[] = array(
			'title' => 'root',
			'path' => ''
		);

		$fields = DB::getPrivateContent(true);

		if($fields['error'] === false)
		{
			$fields = $fields['db_content'];
		}
		else
		{
			Utils::redirect('cms/');
		}

		if( isset($_GET['name']) && $remove_yourself )
		{

			$fields_without_yourself = self::deleteField($fields, $_GET['name']);

			if( !isset($fields_without_yourself['error']) && isset($fields_without_yourself['db_content']) )
			{
				$fields = $fields_without_yourself['db_content'];
			}

		}

		$parents = array_merge( $parents, self::getParents($fields) );

		return $parents;

	}

	public static function getFieldsTypes()
	{

		return array(
			'String'			=>	'input_text',
			'HTML (WYSIWYG)'	=>	'textarea_html',
			'Multiline text'	=>	'textarea_text',
			'Checkbox'			=>	'input_checkbox',
			'Color Picker'		=>	'input_color_picker',
			'File Uploader'		=>	'input_file_uploader',
			'Group of Fields'	=>	'fields_group',
		);

	}

	public static function moveFieldUpDown($name, $to)
	{

		$result = array();


		$name_arr = self::getNameArray($name);

		$db_content = DB::getPrivateContent();

		if($db_content['error'] === false)
		{
			$db_content = $db_content['db_content'];
		}
		else
		{
			$result = $db_content;
		}

		$field = self::getField($db_content, $name);

		$field_exists = self::fieldExists($field);

		if( $field_exists['error'] == false )
		{

			$ref_field_parent = &self::getParent($db_content, $name_arr['parents']);

			$field_position = self::getFieldPosition($ref_field_parent, $name_arr['alias']);

			if( strtolower($to) == 'up' )
			{
				$new_field_position = $field_position - 1;
			}
			else
			{
				$new_field_position = $field_position + 1;
			}

			self::moveFieldFromTo($ref_field_parent, $field_position, $new_field_position);


			$updated = DB::updateContent($db_content);

			if($updated['error'] === false)
			{
				$result['success'] = true;
			}
			else
			{
				$result = $updated;
				$result['success'] = false;
			}

		}
		else
		{
			$result['success'] = false;
		}


		if( Utils::isAJAX() )
		{
			$result = json_encode($result);

			exit($result);
		}
		elseif($result['success'] === true)
		{
			Utils::redirect('cms/');
		}
		else
		{
			return $result;
		}

	}

	public static function openCloseGroup($name, $state)
	{
		$result = array();

		$db_content = DB::getPrivateContent();

		if($db_content['error'] === false)
		{
			$db_content = $db_content['db_content'];
		}
		else
		{
			$result = $db_content;
		}

		$ref_field = &self::getField($db_content, $name);

		if( isset($ref_field['type']) && $ref_field['type'] == 'fields_group' )
		{

			if($state == 'open')
			{
				$ref_field['open'] = true;
			}
			else
			{
				$ref_field['open'] = false;
			}

			$updated = DB::updateContent($db_content);

			if($updated['error'] === false)
			{
				$result['success'] = true;
			}
			else
			{
				$result = $updated;
				$result['success'] = false;
			}

		}
		else
		{
			$result['success'] = false;
		}


		$result = json_encode($result);

		if( Utils::isAJAX() )
		{
			exit($result);
		}
		elseif($result['success'] === true)
		{
			Utils::redirect('cms/');
		}
		else
		{
			$result;
		}

	}

	public static function getNameAsString($name_array)
	{
		return implode(self::NAME_SEPARATOR, $name_array);
	}



	/* PRIVATE API */

	private static function addNewField($db_content)
	{

		$result = array();

		$new_field_data = self::getNewFieldData();


		$data_is_not_empty = self::validateRequiredData($new_field_data);

		if( $data_is_not_empty['error'] == true )
		{
			return $data_is_not_empty;
		}


		$alias_is_valid = self::validateAlias($new_field_data['alias']['value']);

		if( $alias_is_valid['error'] == true )
		{
			return $alias_is_valid;
		}


		$ref_new_field_parent = &self::getParent($db_content, $new_field_data['parent']['value']);


		$parent_exists = self::fieldExists($ref_new_field_parent);

		if( $parent_exists['error'] == true )
		{
			return $parent_exists;
		}


		$new_field_is_not_duplicate = self::validateFieldDuplicates($ref_new_field_parent, $new_field_data['alias']['value']);

		if( $new_field_is_not_duplicate['error'] == true )
		{
			return $new_field_is_not_duplicate;
		}


		$new_field_content = array(
			'type'			=> $new_field_data['type']['value'],
			'title'			=> $new_field_data['title']['value'],
			'description'	=> $new_field_data['description']['value'],
			'required'		=> $new_field_data['required']['value'],
			'open'			=> true,
			'output'		=> $new_field_data['default_output']['value'],
		);

		$ref_new_field_parent[$new_field_data['alias']['value']] = $new_field_content;


		$result['db_content'] = $db_content;

		return $result;

	}

	private static function getNameArray($name)
	{

		$result = array();

		$field_parents = explode(self::NAME_SEPARATOR, $name);

		$field_alias = array_pop($field_parents);

		$field_parents = implode(self::NAME_SEPARATOR, $field_parents);

		$result['alias'] = $field_alias;

		$result['parents'] = $field_parents;

		return $result;

	}

	private static function deleteField($db_content, $name)
	{

		$result = array();


		$name_arr = self::getNameArray($name);

		$ref_field_parent = &self::getParent($db_content, $name_arr['parents']);

		unset($ref_field_parent[$name_arr['alias']]);


		$result['db_content'] = $db_content;

		return $result;

	}

	private static function getForbiddenWords()
	{

		$words = array(
			// CMS Predefined Variables
			'root',

			// PHP Predefined Variables (got from http://php.net/manual/en/reserved.variables.php)
			'GLOBALS',
			'_SERVER',
			'_GET',
			'_POST',
			'_FILES ',
			'_REQUEST',
			'_SESSION',
			'_ENV',
			'_COOKIE',
			'php_errormsg',
			'HTTP_RAW_POST_DATA',
			'http_response_header',
			'argc',
			'argv',
			'this',
		);

		foreach ($words as &$ref_word)
		{
			$ref_word = strtolower($ref_word);
		}

		return $words;

	}

	private static function getParents($fields, $parent_name = array())
	{

		$parents = array();

		foreach ($fields as $alias => $field)
		{

			if( $field['type'] == 'fields_group' )
			{

				$this_parent_name = $parent_name;
				$this_parent_name[] = $alias;

				$prefix = str_repeat( '- ', count($parent_name) );

				$parents[] = array(
					'title' => $prefix.$field['title'],
					'path' => implode(self::NAME_SEPARATOR, $this_parent_name)
				);

				$parents = array_merge( $parents, self::getParents($field['output'], $this_parent_name) );

			}

		}

		return $parents;

	}

	private static function getNewFieldData()
	{

		$data = array();

		$data['parent']['required']			= false;
		$data['parent']['value']			= Utils::pr($_POST['parent']);

		$data['type']['required']			= true;
		$data['type']['value']				= Utils::pr($_POST['type']);

		$data['alias']['required']			= true;
		$data['alias']['value']				= Utils::pr($_POST['alias']);

		$data['title']['required']			= true;
		$data['title']['value']				= Utils::pr($_POST['title']);

		$data['description']['required']	= false;
		$data['description']['value']		= Utils::pr($_POST['description']);

		$data['required']['required']		= true;
		$data['required']['value']			= Utils::pr($_POST['required']);

		$data['default_output']['required']	= false;
		$data['default_output']['value']	= $data['type']['value'] == 'fields_group' ? array() : '';

		return $data;

	}

	private static function validateRequiredData($fields)
	{

		$result = array();
		$result['error'] = false;

		foreach ($fields as $name => $data)
		{

			if( $data['required'] === true && $data['value'] === '' )
			{

				$result['error'] = true;
				$result['error_message'] = Utils::getMessage('content:required_fields');
				$result['invalid_fields'][$name] = Utils::getMessage('content:required_field_tip');
				$result['sent_data'] = Utils::replaceQuotesInArray($_POST);

			}

		}

		return $result;

	}

	private static function validateAliasForbiddenWords($alias)
	{

		$result = array();
		$result['error'] = false;

		$alias = strtolower($alias);

		$forbidden_fords = self::getForbiddenWords();

		if( in_array($alias, $forbidden_fords) )
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage('content:reserved_alias');
			$result['invalid_fields']['alias'] = Utils::getMessage('content:forbidden_alias');
			$result['sent_data'] = Utils::replaceQuotesInArray($_POST);

		}

		return $result;

	}

	private static function validateStringForSeparator($alias)
	{

		$result = array();
		$result['error'] = false;

		if( strpos($alias, self::NAME_SEPARATOR) !== false )
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage('content:alias_has_separator');
			$result['invalid_fields']['alias'] = Utils::getMessage('content:forbidden_alias');
			$result['sent_data'] = Utils::replaceQuotesInArray($_POST);

		}

		return $result;

	}

	private static function validateAliasName($alias)
	{

		$result = array();
		$result['error'] = false;

		// PHP variables naming (got from http://php.net/manual/en/language.variables.basics.php)
		if( !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $alias) )
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage('content:invalid_alias_string');
			$result['invalid_fields']['alias'] = Utils::getMessage('content:invalid_alias');
			$result['sent_data'] = Utils::replaceQuotesInArray($_POST);

		}

		return $result;

	}

	private static function validateAlias($alias)
	{

		$result = array();
		$result['error'] = false;

		$alias_is_not_forbidden_word = self::validateAliasForbiddenWords($alias);

		if( $alias_is_not_forbidden_word['error'] == true )
		{
			return $alias_is_not_forbidden_word;
		}


		$alias_contains_separating_sequence = self::validateStringForSeparator($alias);

		if( $alias_contains_separating_sequence['error'] == true )
		{
			return $alias_contains_separating_sequence;
		}


		$alias_has_valid_name = self::validateAliasName($alias);

		if( $alias_has_valid_name['error'] == true )
		{
			return $alias_has_valid_name;
		}

		return $result;

	}

	private static function fieldExists($field)
	{

		$result = array();
		$result['error'] = false;

		if( $field === null )
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage('content:parent_is_absent');
			$result['invalid_fields']['parent'] = Utils::getMessage('content:invalid_parent');
			$result['sent_data'] = Utils::replaceQuotesInArray($_POST);

		}

		return $result;

	}

	private static function validateFieldDuplicates($parent, $alias)
	{

		$result = array();
		$result['error'] = false;

		if( isset($parent[$alias]) )
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage('content:parent_already_has_the_child');
			$result['invalid_fields']['alias'] = Utils::getMessage('content:invalid_parent');
			$result['sent_data'] = Utils::replaceQuotesInArray($_POST);

		}

		return $result;

	}

	private static function &getField(&$ref_content, $parents)
	{

		if($parents == '')
		{
			return $ref_content;
		}

		$parents = explode(self::NAME_SEPARATOR, $parents);

		$field_name = array_pop($parents);

		$ref_to_field = &$ref_content;


		foreach($parents as $parent)
		{

			if( isset($ref_to_field[$parent]['output']) )
			{
				$ref_to_field = &$ref_to_field[$parent]['output'];
			}
			else
			{
				unset($ref_to_field);
			}

		}

		if( isset($ref_to_field[$field_name]) )
		{
			$ref_to_field = &$ref_to_field[$field_name];
		}
		else
		{
			unset($ref_to_field);
		}


		return $ref_to_field;

	}

	private static function &getParent(&$ref_content, $parents)
	{

		$field = &self::getField($ref_content, $parents);

		if( isset($field['output']) && $field['type'] == 'fields_group' )
		{
			$field = &$field['output'];
		}

		return $field;

	}

	private static function moveFieldFromTo(&$ref_parent, $from, $to)
	{

		$to = $to < 0 ? NULL : $to;

		$new_array = array_slice($ref_parent, 0, $from, true) +
					 array_slice($ref_parent, $to, NULL, true);

		$ref_parent = array_slice($new_array, 0, $to, true) +
				 array_slice($ref_parent, $from, 1) +
				 array_slice($new_array, $to, NULL, true);

	}

	private static function getFieldPosition($parent, $alias)
	{
		return array_search($alias, array_keys($parent));
	}

}

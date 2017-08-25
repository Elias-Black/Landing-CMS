<?php

if( !defined('CORE') ) { die('403'); }

/**
* The Class for working with Content
*/

class Content
{

	const NAME_SEPARATOR = '__S__';



	/* PUBLIC API */

	public static function getMainForm()
	{

		return Utils::render(
			'forms/main.php',
			 array( 'fields' => DB::getPrivateContent(true) )
		);

	}

	public static function addNewFieldAction()
	{

		$db_content = DB::getPrivateContent();

		$field_is_added = self::addNewField($db_content);

		if( !isset($field_is_added['error']) && isset($field_is_added['db_content']) )
		{
			$db_content = $field_is_added['db_content'];
		}
		else
		{
			return $field_is_added;
		}

		DB::updateContent($db_content);

		Utils::redirect( Utils::getLink('cms/') );

	}

	public static function editField()
	{

		$result = array();

		$field_name = isset($_GET['name']) ? $_GET['name'] : false;

		if( !$field_name )
		{
			Utils::redirect( Utils::getLink('cms/') );
		}

		$name_arr = self::getNameArray($field_name);

		$db_content = DB::getPrivateContent(true);

		$field_data = self::getField($db_content, $field_name);

		$field_exists = self::fieldExists($field_data);

		if( $field_exists['error'] == true )
		{
			Utils::redirect( Utils::getLink('cms/') );
		}

		$result['sent_data'] = $field_data;

		$result['sent_data']['parent'] = $name_arr['parents'];

		$result['sent_data']['alias'] = $name_arr['alias'];

		return $result;

	}

	public static function updateField()
	{

		$old_field_name = isset($_GET['name']) ? $_GET['name'] : false;

		if( !$old_field_name )
		{
			Utils::redirect( Utils::getLink('cms/') );
		}


		$db_content = DB::getPrivateContent();

		$new_field_data = self::getNewFieldData();

		$old_name_arr = self::getNameArray($old_field_name);



		$old_field = self::getField($db_content, $old_field_name);

		$field_exists = self::fieldExists($old_field);

		if( $field_exists['error'] == true )
		{
			Utils::redirect( Utils::getLink('cms/') );
		}


		$old_position = 0;

		if( $old_name_arr['parents'] == $new_field_data['parent']['value'] )
		{

			$old_parent = self::getField($db_content, $old_name_arr['parents']);

			if( !empty($old_name_arr['parents']) )
			{
				$old_parent = $old_parent['output'];
			}

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


		$field_is_deleted = self::deleteField($db_content, $old_field_name);

		if( !isset($field_is_deleted['error']) && isset($field_is_deleted['db_content']) )
		{
			$db_content = $field_is_deleted['db_content'];
		}
		else
		{
			return $field_is_deleted;
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


		$ref_new_parent = &self::getField($db_content, $new_field_data['parent']['value']);

		if( !empty($new_field_data['parent']['value']) )
		{
			$ref_new_parent = &$ref_new_parent['output'];
		}


		$ref_new_field = &$ref_new_parent[$new_field_data['alias']['value']];

		$ref_new_field['output'] = $old_field_output;


		$new_position = self::getFieldPosition($ref_new_parent, $new_field_data['alias']['value']);

		self::moveFieldFromTo($ref_new_parent, $new_position, $old_position);


		DB::updateContent($db_content);

		Utils::redirect( Utils::getLink('cms/') );

	}

	public static function updateContent()
	{

		$db_content = DB::getPrivateContent();

		foreach ($_POST as $name => $value)
		{

			$ref_field = &self::getField($db_content, $name);

			if( isset($ref_field) )
			{
				$ref_field['output'] = $value;
			}

		}

		DB::updateContent($db_content);

		Utils::redirect( Utils::getLink('cms/') );

	}

	public static function deleteFieldAction($field_name)
	{

		$db_content = DB::getPrivateContent();

		$field_is_deleted = self::deleteField($db_content, $field_name);

		if( !isset($field_is_deleted['error']) && isset($field_is_deleted['db_content']) )
		{
			$db_content = $field_is_deleted['db_content'];
		}
		else
		{
			return $field_is_deleted;
		}

		DB::updateContent($db_content);

		Utils::redirect( Utils::getLink('cms/') );

	}

	public static function getAllParents()
	{

		$parents[] = array(
			'title' => 'root',
			'path' => ''
		);

		$fields = DB::getPrivateContent();

		if( isset($_GET['name']) )
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

		$name_arr = self::getNameArray($name);

		$db_content = DB::getPrivateContent();

		$ref_field_parent = &self::getField($db_content, $name_arr['parents']);

		if( !empty($name_arr['parents']) )
		{
			$ref_field_parent = &$ref_field_parent['output'];
		}

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


		DB::updateContent($db_content);

		Utils::redirect( Utils::getLink('cms/') );

	}

	public static function openCloseGroup($name, $state)
	{
		$result = array();

		$db_content = DB::getPrivateContent();

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

			$result['success'] = true;

		}
		else
		{
			$result['success'] = false;
		}

		DB::updateContent($db_content);

		$result = json_encode($result);

		if( Utils::isAJAX() )
		{
			exit($result);
		}
		else
		{
			Utils::redirect( Utils::getLink('cms/') );
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


		$ref_new_field_parent = &self::getField($db_content, $new_field_data['parent']['value']);

		if( !empty($new_field_data['parent']['value']) )
		{
			$ref_new_field_parent = &$ref_new_field_parent['output'];
		}


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

		$ref_field_parent = &self::getField($db_content, $name_arr['parents']);

		if( !empty($name_arr['parents']) )
		{
			$ref_field_parent = &$ref_field_parent['output'];
		}

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
				$result['error_message'] = 'Parent, Type, Alias and Title are required fields.';
				$result['invalid_fields'][$name] = 'It\' a required field.';
				$result['sent_data'] = Utils::replaceQuotes($_POST);

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
			$result['error_message'] = 'Alias can\'t have this word. <a href="http://php.net/manual/en/reserved.variables.php" target="_blank">Here</a> are the majority forbidden words.';
			$result['invalid_fields']['alias'] = 'Alias have forbidden word.';
			$result['sent_data'] = Utils::replaceQuotes($_POST);

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
			$result['error_message'] = 'Alias can\'t have this word.';
			$result['invalid_fields']['alias'] = 'Alias have forbidden word.';
			$result['sent_data'] = Utils::replaceQuotes($_POST);

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
			$result['error_message'] = 'Invalid Alias. Alias names follow the same <a href="http://php.net/manual/en/language.variables.basics.php" target="_blank">rules</a> as variable names in PHP.';
			$result['invalid_fields']['alias'] = 'Invalid Alias.';
			$result['sent_data'] = Utils::replaceQuotes($_POST);

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
			$result['error_message'] = 'This Parent is absent.';
			$result['invalid_fields']['parent'] = 'Invalid Parent.';
			$result['sent_data'] = Utils::replaceQuotes($_POST);

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
			$result['error_message'] = 'The Parent already have a child with this Alias.';
			$result['invalid_fields']['alias'] = 'The Parent already have a child with this Alias.';
			$result['sent_data'] = Utils::replaceQuotes($_POST);

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

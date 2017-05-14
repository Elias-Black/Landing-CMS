<?php

/**
* The Class for working with DB and Content
*/
class Content
{

	const NAME_SEPARATOR = '__S__';



	/* PUBLIC API */

	public static function getMainForm()
	{

		return Utils::render(
			'forms/main.html',
			 array( 'fields' => self::getPrivateContent(true) )
		);

	}

	public static function addNewField()
	{

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


		$db_content = self::getPrivateContent();

		$new_field_parent = &self::getField($db_content, $new_field_data['parent']['value']);

		if( !empty($new_field_data['parent']['value']) )
		{
			$new_field_parent = &$new_field_parent['output'];
		}


		$parent_exists = self::parentExists($new_field_parent);

		if( $parent_exists['error'] == true )
		{
			return $parent_exists;
		}


		$new_field_is_not_duplicate = self::validateFieldDuplicates($new_field_parent, $new_field_data['alias']['value']);

		if( $new_field_is_not_duplicate['error'] == true )
		{
			return $new_field_is_not_duplicate;
		}


		$new_field_content = array(
			'type'			=> $new_field_data['type']['value'],
			'name'			=> $new_field_data['name']['value'],
			'title'			=> $new_field_data['title']['value'],
			'description'	=> $new_field_data['description']['value'],
			'output'		=> $new_field_data['default_output']['value'],
		);

		$new_field_parent[$new_field_data['alias']['value']] = $new_field_content;


		self::updatePrivateContent($db_content);

		self::updatePublicContent();

		Utils::redirect('/cms/');

	}

	public static function updateContent()
	{

		$db_content = self::getPrivateContent();

		foreach ($_POST as $name => $value)
		{

			$field = &self::getField($db_content, $name);

			if( isset($field) )
			{
				$field['output'] = $value;
			}

		}

		self::updatePrivateContent($db_content);

		self::updatePublicContent();

		Utils::redirect('/cms/');

	}

	public static function deleteField($parents)
	{

		$parents = explode(self::NAME_SEPARATOR, $parents);

		$field_name = array_pop($parents);

		$parents = implode(self::NAME_SEPARATOR, $parents);

		$db_content = self::getPrivateContent();

		$content = &self::getField($db_content, $parents);

		if( !empty($parents) )
		{
			$content = &$content['output'];
		}

		unset($content[$field_name]);

		self::updatePrivateContent($db_content);

		self::updatePublicContent();

		Utils::redirect('/cms/');

	}

	public static function getAllParents()
	{

		$parents[] = array(
			'title' => 'root',
			'path' => ''
		);

		$fields = self::getPrivateContent();

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
			'Group of fields'	=>	'fields_group',
		);

	}



	/* PRIVATE API */

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

		foreach ($words as &$word)
		{
			$word = strtolower($word);
		}

		return $words;

	}

	private static function getParents($fields)
	{

		$parents = array();

		foreach ($fields as $alias => $field)
		{

			if( $field['type'] == 'fields_group' )
			{

				$c = count( explode(self::NAME_SEPARATOR, $field['name']) ) - 1;

				$t = '';

				for($i = 0; $i < $c; $i++)
				{
					$t .= '- ';
				}

				$parents[] = array(
					'title' => $t.$field['title'],
					'path' => $field['name']
				);

				$parents = array_merge( $parents, self::getParents($field['output']) );

			}

		}

		return $parents;

	}

	private static function getNewFieldData()
	{

		$data = array();

		$data['parent']['required']			= false;
		$data['parent']['value']			= isset($_POST['parent']) ? $_POST['parent'] : '';

		$data['type']['required']			= true;
		$data['type']['value']				= isset($_POST['type']) ? $_POST['type'] : '';

		$data['alias']['required']			= true;
		$data['alias']['value']				= isset($_POST['alias']) ? $_POST['alias'] : '';

		$data['name']['required']			= true;
		$data['name']['value']				= empty($data['parent']['value']) ? $data['alias']['value'] : $data['parent']['value'].self::NAME_SEPARATOR.$data['alias']['value'];

		$data['title']['required']			= true;
		$data['title']['value']				= isset($_POST['title']) ? $_POST['title'] : '';

		$data['description']['required']	= false;
		$data['description']['value']		= isset($_POST['description']) ? $_POST['description'] : '';

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
				$result['error_message'] = "Parent, Type, Alias and Title are required fields.";
				$result['invalid_fields'][$name] = 'It\' a required field.';
				$result['sent_data'] = $_POST;

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
			$result['error_message'] = "Alias can't have this word. <a href=\"http://php.net/manual/en/reserved.variables.php\" target=\"_blank\">Here</a> are the majority forbidden words.";
			$result['invalid_fields']['alias'] = 'Alias have forbidden word.';
			$result['sent_data'] = $_POST;

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
			$result['error_message'] = "Alias can't have this word.";
			$result['invalid_fields']['alias'] = 'Alias have forbidden word.';
			$result['sent_data'] = $_POST;

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
			$result['error_message'] = "Invalid Alias &laquo;{$_POST['alias']}&raquo;. Alias names follow the same <a href=\"http://php.net/manual/en/language.variables.basics.php\" target=\"_blank\">rules</a> as variable names in PHP.";
			$result['invalid_fields']['alias'] = 'Invalid Alias.';
			$result['sent_data'] = $_POST;

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

	private static function parentExists($field)
	{

		$result = array();
		$result['error'] = false;

		if( $field === null )
		{

			$result['error'] = true;
			$result['error_message'] = "This Parent is absent.";
			$result['invalid_fields']['parent'] = 'Invalid Parent.';
			$result['sent_data'] = $_POST;

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
			$result['error_message'] = "This Parent already have a child with alias &laquo;$alias&raquo;.";
			$result['invalid_fields']['alias'] = 'This Parent already have a chiled with alias.';
			$result['sent_data'] = $_POST;

		}

		return $result;

	}

	private static function &getField(&$content, $parents)
	{

		if($parents == '')
		{
			return $content;
		}

		$parents = explode(self::NAME_SEPARATOR, $parents);

		$field_name = array_pop($parents);

		$ref_to_field = &$content;


		foreach($parents as $parent)
		{
		  @$ref_to_field = &$ref_to_field[$parent]['output'];
		}

		@$ref_to_field = &$ref_to_field[$field_name];

		return $ref_to_field;

	}

	private static function getPrivateContent($safe_replace = false)
	{

		$content = file_get_contents(DB_PRIVATE_PATH);

		$content = unserialize( substr($content, SECURE_LENGTH) );

		if(!$content)
		{
			$content = array();
		}

		return $safe_replace ? self::replaceQuotes($content) : $content;

	}

	private static function updatePrivateContent($content)
	{

		$content = SECURE_TEXT . serialize($content);

		file_put_contents( DB_PRIVATE_PATH, $content );

	}

	private static function getFieldsOutput($content)
	{

		$result = array();

		foreach ($content as $alias => $value)
		{

			if( $value['type'] == 'fields_group' )
			{
				$value['output'] = self::getFieldsOutput($value['output']);
			}

			$result[$alias] = $value['output'];

		}

		return $result;

	}

	private static function updatePublicContent()
	{

		$db_content = self::getPrivateContent();

		$result = self::getFieldsOutput($db_content);

		file_put_contents( DB_PUBLIC_PATH, serialize($result) );

	}

	private static function replaceQuotes($array)
	{

		$result = array();

		foreach ($array as $key => $value)
		{

			if( is_array($value) )
			{
				$result[$key] = self::replaceQuotes($value);
			}
			else
			{
				$result[$key] = str_replace( array('"', '\''), array('&quot;', '&apos;'), $value );
			}

		}

		return $result;

	}

}

?>
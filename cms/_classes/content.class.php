<?php

/**
* The Class for working with DB and Content
*/
class Content
{

	public static function getMainForm()
	{

		return Utils::render(
			'forms/main.html',
			 array( 'fields' => self::getPrivateContent(true) )
		);

	}

	public static function addNewField()
	{

		$old_content = self::getPrivateContent();

		if( !preg_match('#^[a-zA-Z0-9_-]{1,25}+$#', $_POST['alias']) )
			return array(
				'error' => "Invalid alias &laquo;{$_POST['alias']}&raquo;. Alias can contain letters, numbers, underscores &laquo;_&raquo; and hyphens &laquo;-&raquo;.",
				'fields' => $_POST
			);

		foreach ($old_content as $field)
		{

			if( $field['alias'] == $_POST['alias'] )
				return array(
					'error' => "Field with alias &laquo;{$_POST['alias']}&raquo; already exist.",
					'fields' => $_POST
				);

		}

		$new_content = array(
			array(
			    'output'		=> '',
			    'title'			=> $_POST['title'],
			    'alias'			=> $_POST['alias'],
			    'description'	=> $_POST['description'],
			    'type'			=> $_POST['type']
			)
		);

		// Inserting the new array into the DB content array
		if( $old_content )
			$new_content = array_merge($old_content, $new_content);

		self::updatePrivateContent($new_content);

		self::updatePublicContent();

		Utils::redirect('/cms/');

	}

	public static function updateContent()
	{

		$old_content = self::getPrivateContent();

		$new_content = array();

		$aliases = array_keys($_POST);

		foreach ($aliases as $alias)
		{

			foreach ($old_content as $field)
			{

				if($field['alias'] == $alias)
				{

					$field['output'] = $_POST[$alias];

					$new_content = array_merge($new_content, array($field));

				}

			}

		}

		self::updatePrivateContent($new_content);

		self::updatePublicContent();

		Utils::redirect('/cms/');

	}

	public static function deleteField($alias)
	{

		$old_content = self::getPrivateContent();

		$new_content = array();

		foreach ($old_content as $field)
		{

			if( $field['alias'] == $alias )
				continue;

			$new_content = array_merge( $new_content, array($field) );

		}

		self::updatePrivateContent($new_content);

		self::updatePublicContent();

		Utils::redirect('/cms/');

	}

	private static function getPrivateContent($safe_replace = false)
	{

		$content = unserialize( file_get_contents(DB_PRIVATE_PATH) );

		return $safe_replace ? self::replaceQuotes($content) : $content;
	}

	private static function updatePrivateContent($content)
	{
		file_put_contents( DB_PRIVATE_PATH, serialize($content) );
	}

	private static function updatePublicContent()
	{

		$content = self::getPrivateContent();

		$public_content = '<?php $get = array(';

		foreach ($content as $field)
		{

			$output = str_replace( array("\\", "'"), array("\\\\", "\'"), $field['output'] );

			$public_content .= "'{$field['alias']}' => '$output', ";

		}

		$public_content .= '); ?>'; 

		file_put_contents( DB_PUBLIC_PATH, $public_content );

	}

	private static function replaceQuotes($array)
	{

		$result = array();

		foreach ($array as $key => $value)
		{

			if( is_array($value) )
				$result[$key] = self::replaceQuotes($value);
			else
				$result[$key] = str_replace( '"', '&quot;', $value );

		}

		return $result;

	}

}

?>
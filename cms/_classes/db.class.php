<?php

/**
* The Class for working with DB
*/
class DB
{

	const DB_PASSWORD_PATH = ROOT_PATH. '/cms/_db/password.php';
	const DB_PRIVATE_PATH = ROOT_PATH. '/cms/_db/private.php';
	const DB_PUBLIC_PATH = ROOT_PATH. '/cms/_db/public.php';

	const SECURE_TEXT = '<?php die; ?>';
	const SECURE_LENGTH = 13;



	/* PUBLIC API */

	public static function getPrivateContent($safe_replace = false)
	{

		$content = file_get_contents(self::DB_PRIVATE_PATH);

		$content = unserialize( substr($content, self::SECURE_LENGTH) );

		if(!$content)
		{
			$content = array();
		}

		return $safe_replace ? Utils::replaceQuotes($content) : $content;

	}

	public static function updateContent($content)
	{

		self::updatePrivateContent($content);

		self::updatePublicContent($content);

	}

	public static function getPassword()
	{

		$content = file_get_contents(self::DB_PASSWORD_PATH);

		return substr($content, self::SECURE_LENGTH);

	}

	public static function updatePassword($password)
	{

		$content = self::SECURE_TEXT . $password;

		file_put_contents( self::DB_PASSWORD_PATH, $content );

	}



	/* PRIVATE API */

	private static function updatePrivateContent($content)
	{

		$content = self::SECURE_TEXT . serialize($content);

		file_put_contents( self::DB_PRIVATE_PATH, $content );

	}

	private static function updatePublicContent($content)
	{

		$result = self::getFieldsOutput($db_content);

		file_put_contents( self::DB_PUBLIC_PATH, serialize($result) );

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

}
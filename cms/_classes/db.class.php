<?php

/**
* The Class for working with DB
*/
class DB
{

	const SECURE_TEXT = '<?php die; ?>';
	const SECURE_LENGTH = 13;



	/* PUBLIC API */

	public static function getPrivateContent($safe_replace = false)
	{

		$content = file_get_contents(self::getPrivateDBPath());

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

		$content = file_get_contents(self::getPasswordDBPath());

		return substr($content, self::SECURE_LENGTH);

	}

	public static function updatePassword($password)
	{

		$content = self::SECURE_TEXT . $password;

		file_put_contents( self::getPasswordDBPath(), $content );

	}



	/* PRIVATE API */

	private static function getPasswordDBPath()
	{
		return dirname(__FILE__) . '/../_db/password.php';
	}

	private static function getPrivateDBPath()
	{
		return dirname(__FILE__) . '/../_db/private.php';
	}

	private static function getPublicDBPath()
	{
		return dirname(__FILE__) . '/../_db/public.php';
	}

	private static function updatePrivateContent($content)
	{

		$content = self::SECURE_TEXT . serialize($content);

		file_put_contents( self::getPrivateDBPath(), $content );

	}

	private static function updatePublicContent($content)
	{

		$result = self::getFieldsOutput($content);

		file_put_contents( self::getPublicDBPath(), serialize($result) );

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
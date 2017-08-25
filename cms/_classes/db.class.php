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

if( !defined('CORE') ) { die('403'); }

/**
* The Class for working with DB
*/

class DB
{

	const SECURE_TEXT = '<?php die; ?>';
	const SECURE_LENGTH = 13;

	const PASSWORD_DB_PATH = 'cms/_db/password.php';
	const PRIVATE_DB_PATH = 'cms/_db/private.php';
	const PUBLIC_DB_PATH = 'cms/_db/public.php';



	/* PUBLIC API */

	public static function getPrivateContent($safe_replace = false)
	{

		$content = file_get_contents( Utils::getPath(self::PRIVATE_DB_PATH) );

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

		$content = file_get_contents( Utils::getPath(self::PASSWORD_DB_PATH) );

		return substr($content, self::SECURE_LENGTH);

	}

	public static function updatePassword($password)
	{

		$content = self::SECURE_TEXT . $password;

		file_put_contents( Utils::getPath(self::PASSWORD_DB_PATH), $content );

	}



	/* PRIVATE API */

	private static function updatePrivateContent($content)
	{

		$content = self::SECURE_TEXT . serialize($content);

		file_put_contents( Utils::getPath(self::PRIVATE_DB_PATH), $content );

	}

	private static function updatePublicContent($content)
	{

		$result = self::getFieldsOutput($content);

		file_put_contents( Utils::getPath(self::PUBLIC_DB_PATH), serialize($result) );

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

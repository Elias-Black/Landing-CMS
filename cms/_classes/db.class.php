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

defined('CORE') OR die('403');

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

		return $safe_replace ? Utils::replaceQuotesInArray($content) : $content;

	}

	public static function updateContent($content)
	{

		$result = array();

		$private_updated = self::updatePrivateContent($content);

		if($private_updated['error'] === true)
		{

			$result['error'] = true;
			$result['error_message'] = 'Can not be written to the Private database. Check permissions on <a href="'.Utils::getLink('install.php').'" target="_blank">this</a> helper.';

			return $result;

		}

		$public_updated = self::updatePublicContent($content);

		if($public_updated['error'] === true)
		{

			$result['error'] = true;
			$result['error_message'] = 'Can not be written to the Public database. Check permissions on <a href="'.Utils::getLink('install.php').'" target="_blank">this</a> helper.';

			return $result;

		}

		$result['error'] = false;
		$result['success_message'] = 'Saved successfully.';

		return $result;

	}

	public static function getPassword()
	{

		$content = file_get_contents( Utils::getPath(self::PASSWORD_DB_PATH) );

		return substr($content, self::SECURE_LENGTH);

	}

	public static function updatePassword($password)
	{

		$result = array();
		$result['error'] = false;

		$content = self::SECURE_TEXT . $password;

		$updated = file_put_contents( Utils::getPath(self::PASSWORD_DB_PATH), $content );

		if($updated === false)
		{
			$result['error'] = true;
			$result['error_message'] = 'Can not be written to the Password database. Check permissions on <a href="'.Utils::getLink('install.php').'" target="_blank">this</a> helper.';
		}

		return $result;

	}



	/* PRIVATE API */

	private static function updatePrivateContent($content)
	{

		$result = array();
		$result['error'] = false;

		$content = self::SECURE_TEXT . serialize($content);

		$updated = file_put_contents( Utils::getPath(self::PRIVATE_DB_PATH), $content );

		if($updated === false)
		{
			$result['error'] = true;
			$result['error_message'] = 'Can not be written to the database. Check permissions on <a href="'.Utils::getLink('install.php').'" target="_blank">this</a> helper.';
		}

		return $result;


	}

	private static function updatePublicContent($content)
	{

		$result = array();
		$result['error'] = false;

		$content = self::getFieldsOutput($content);

		$updated = file_put_contents( Utils::getPath(self::PUBLIC_DB_PATH), serialize($content) );

		if($updated === false)
		{
			$result['error'] = true;
			$result['error_message'] = 'Can not be written to the database. Check permissions on <a href="'.Utils::getLink('install.php').'" target="_blank">this</a> helper.';
		}

		return $result;


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

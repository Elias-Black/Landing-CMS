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
* The Class for working with DB
*/

class DB
{

	const SECURE_TEXT = '<?php die; ?>';
	const SECURITY_LENGTH = 13;

	const PASSWORD_DB_PATH = 'cms/_db/password.php';
	const PRIVATE_DB_PATH = 'cms/_db/private.php';
	const PUBLIC_DB_PATH = 'cms/_db/public.php';



	/* PUBLIC API */

	public static function getPrivateContent($safe_replace = false)
	{

		$result = array();


		$private_db_path = Utils::getPath(self::PRIVATE_DB_PATH);

		try
		{

			if( file_exists($private_db_path) )
			{

				if( is_readable($private_db_path) !== TRUE )
				{
					throw new Exception();
				}

				$content = file_get_contents($private_db_path);

				if($content === FALSE)
				{
					throw new Exception();
				}

			}
			else
			{
				$content = '';
			}

		}
		catch(Exception $e)
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage(
				'db:not_readable_private_db',
				 Utils::getLink('install.php')
			);

			return $result;

		}

		$content = substr($content, self::SECURITY_LENGTH);

		$content = base64_decode($content);

		$content = unserialize($content);

		if(!$content)
		{
			$content = array();
		}

		$result['error'] = false;
		$result['db_content'] = $safe_replace ? Utils::replaceQuotesInArray($content) : $content;


		return $result;

	}

	public static function updateContent($content)
	{

		$result = array();


		$private_updated = self::updatePrivateContent($content);

		if($private_updated['error'] === true)
		{
			return $private_updated;
		}


		$public_updated = self::updatePublicContent($content);

		if($public_updated['error'] === true)
		{
			return $public_updated;
		}


		$result['error'] = false;
		$result['success_message'] = Utils::getMessage('db:saved_successfully');

		return $result;

	}

	public static function getPassword()
	{

		$result = array();


		$password_db_path = Utils::getPath(self::PASSWORD_DB_PATH);

		try
		{

			if( file_exists($password_db_path) )
			{

				if( is_readable($password_db_path) !== TRUE )
				{
					throw new Exception();
				}

				$content = file_get_contents($password_db_path);

				if($content === false)
				{
					throw new Exception();
				}

			}
			else
			{
				$content = '';
			}

		}
		catch(Exception $e)
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage(
				'db:not_readable_password_db',
				 Utils::getLink('install.php')
			);

			return $result;

		}

		$result['error'] = false;
		$result['db_password'] = substr($content, self::SECURITY_LENGTH);


		return $result;

	}

	public static function updatePassword($password)
	{

		$result = array();
		$result['error'] = false;

		$content = self::SECURE_TEXT . $password;

		$password_db_path = Utils::getPath(self::PASSWORD_DB_PATH);

		try
		{

			if( is_writable($password_db_path) !== TRUE && file_exists($password_db_path) )
			{
				throw new Exception();
			}

			$updated = file_put_contents($password_db_path, $content);

			if($updated === false)
			{
				throw new Exception();
			}

		}
		catch(Exception $e)
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage(
				'db:not_writable_password_db',
				 Utils::getLink('install.php')
			);

		}


		return $result;

	}



	/* PRIVATE API */

	private static function updatePrivateContent($content)
	{

		$result = array();
		$result['error'] = false;

		$content = self::SECURE_TEXT . base64_encode( serialize($content) );

		$private_db_path = Utils::getPath(self::PRIVATE_DB_PATH);

		try
		{

			if( is_writable($private_db_path) !== TRUE && file_exists($private_db_path) )
			{
				throw new Exception();
			}

			$updated = file_put_contents($private_db_path, $content);

			if($updated === false)
			{
				throw new Exception();
			}

		}
		catch(Exception $e)
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage(
				'db:not_writable_private_db',
				 Utils::getLink('install.php')
			);

		}

		return $result;

	}

	private static function updatePublicContent($content)
	{

		$result = array();
		$result['error'] = false;

		$content = self::getFieldsOutput($content);
		$content = self::SECURE_TEXT . base64_encode( serialize($content) );

		$public_db_path = Utils::getPath(self::PUBLIC_DB_PATH);

		try
		{

			if( is_writable($public_db_path) !== TRUE && file_exists($public_db_path) )
			{
				throw new Exception();
			}

			$updated = file_put_contents($public_db_path, $content);

			if($updated === false)
			{
				throw new Exception();
			}

		}
		catch(Exception $e)
		{

			$result['error'] = true;
			$result['error_message'] = Utils::getMessage(
				'db:not_writable_public_db',
				 Utils::getLink('install.php')
			);

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

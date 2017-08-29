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
* The support Class
*/

class Utils
{

	const SITE_FOLDER = '/';

	const TEMPLATE_FOLDER = 'cms/_templates/';
	const CURRENT_FILE_NESTING = 2;



	public static function redirect($url = '')
	{

		$url = parse_url($url, PHP_URL_SCHEME) === NULL ? self::getLink($url) : $url;

		header("Location: $url", true);

		exit();

	}

	public static function render($template, $vars)
	{

		$template = self::getPath(self::TEMPLATE_FOLDER) . $template;

		ob_start();
			include($template);
			$result = ob_get_contents();
		ob_get_clean();

		return $result;

	}

	public static function renderIndex($content, $message)
	{
		return self::render(
			'index.php',
			 array(
			 	'content' => isset($content) ? $content : null,
			 	'error_message' => isset($message['error_message']) ? $message['error_message'] : null,
			 	'success_message' => isset($message['success_message']) ? $message['success_message'] : null
			 )
		);
	}

	public static function getRandomString($length = 10)
	{
		return substr( sha1( rand() ), $length);
	}

	public static function pr(&$ref_variable, $default_value = '')
	{
		return isset($ref_variable) ? $ref_variable : $default_value;
	}

	public static function replaceQuotes($array)
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
				$result[$key] = str_replace( array('&', '"', '\'', '<', '>'), array('&amp;', '&quot;', '&apos;', '&lt;', '&gt;'), $value );
			}

		}

		return $result;

	}

	public static function isAJAX()
	{
		return ( self::pr($_POST['ajax']) == 'true' || self::pr($_GET['ajax']) == 'true' );
	}

	public static function getLink($relative_link = '')
	{
		return self::SITE_FOLDER . $relative_link;
	}

	public static function getRootPath()
	{
		return realpath( dirname(__FILE__) . str_repeat('/..', self::CURRENT_FILE_NESTING) ) . '/';
	}

	public static function getPath($path)
	{
		return self::getRootPath().$path;
	}

}

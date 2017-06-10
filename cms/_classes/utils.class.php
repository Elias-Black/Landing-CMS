<?php

/**
* The support Class
*/
class Utils
{

	const TEMPLATE_FOLDER = __DIR__ . '/../_templates/';

	const SITE_FOLDER = '/';



	public static function redirect($url)
	{

		header("Location: $url", true);

		exit();

	}

	public static function render($template, $vars)
	{

		$template = self::TEMPLATE_FOLDER . $template;

		ob_start();
			include($template);
			$result = ob_get_contents();
		ob_get_clean();

		return $result;

	}

	public static function renderIndex($content, $message)
	{
		return self::render(
			'index.html',
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

}

?>
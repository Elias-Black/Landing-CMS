<?php

/**
* The support Class
*/
class Utils
{
	
	public static function redirect($url)
	{

	    header("Location: $url", true);

	    exit();

	}

	public static function render($template, $vars)
	{

		$template = ROOT_PATH . "/cms/_templates/$template";

		ob_start();
			include($template);
			$result = ob_get_contents();
		ob_get_clean();

		return $result;

	}

}

?>
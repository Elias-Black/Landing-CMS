<?php

/**
* The Class for working with User and Session
*/
class User
{

	public static function auth()
	{

		$db_pwd = self::getDbPassword();

		if( empty($db_pwd) )
			return self::createPassword();

		$usr_cookie_pwd = self::getLoginCookie();

		$sys_cookie_pwd = self::getPasswordForCookie();

		$post_pwd = isset($_POST['password']) ? $_POST['password'] : false;

		$entered_pwd = self::preparePasswordForDb($post_pwd);

		if( $entered_pwd == $db_pwd || $usr_cookie_pwd == $sys_cookie_pwd )
		{

			define('IS_LOGIN', true);

			if( $usr_cookie_pwd != $sys_cookie_pwd )
				self::login();

			return;

		}

		if( $_SERVER['REQUEST_URI'] != '/cms/login/' )
			Utils::redirect('/cms/login/');

		if( $entered_pwd && $entered_pwd != $db_pwd )
			return array('error' => 'Invalid password.');

	}

	public static function updatePassword()
	{

		if( $_POST['pass1'] != $_POST['pass2'] )
			return array('error' => 'Password and confirm password doesn\'t match.');

		self::savePassword($_POST['pass1']);

		return array('success' => 'Password successfully saved.');

	}

	public static function logout()
	{

		unset($_COOKIE['login']);

		setcookie('login','',time()-1, '/');

		Utils::redirect('/');

	}

	private static function getDbPassword()
	{

		$content = file_get_contents(DB_PASSWORD_PATH);

		return substr($content, SECURE_LENGTH);

	}

	private static function getPasswordForCookie()
	{
		return md5( self::getDbPassword() );
	}

	private static function setLoginCookie()
	{
		setcookie('login', self::getPasswordForCookie(), time() + (86400 * 365), '/');
	}

	private static function getLoginCookie()
	{
		return isset($_COOKIE['login']) ? $_COOKIE['login'] : false;
	}

	private static function preparePasswordForDb($password)
	{
		return $password ? sha1($password) : false;
	}

	private static function savePassword($password)
	{

		$password = self::preparePasswordForDb($password);

		$content = SECURE_TEXT . $password;

		file_put_contents( DB_PASSWORD_PATH, $content );

		self::setLoginCookie();

	}

	private static function createPassword()
	{

		if( $_SERVER['REQUEST_URI'] != '/cms/password/' )
			Utils::redirect('/cms/password/');

		if( !empty($_POST) )
		{
			$message =  self::updatePassword();

			if( !$message['error'] )
				Utils::redirect('/cms/');

			return $message;

		}

		return array('error' => 'Create password.');

	}

	private static function login()
	{

		self::setLoginCookie();

		Utils::redirect('/cms/');

	}

}

?>
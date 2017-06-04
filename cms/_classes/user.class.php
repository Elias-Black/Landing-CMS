<?php

/**
* The Class for working with User and Session
*/
class User
{



	/* PUBLIC API */

	public static function auth()
	{

		$db_pwd = DB::getPassword();

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
			return array('error_message' => 'Invalid password.');

	}

	public static function updatePassword()
	{

		if( $_POST['pass1'] != $_POST['pass2'] )
			return array('error_message' => 'Password and confirm password doesn\'t match.');

		$prepared_pwd = self::preparePasswordForDb($_POST['pass1']);

		DB::updatePassword($prepared_pwd);

		self::setLoginCookie();

		return array('success_message' => 'Password successfully saved.');

	}

	public static function logout()
	{

		unset($_COOKIE['login']);

		setcookie('login','',time()-1, '/');

		Utils::redirect('/');

	}



	/* PRIVATE API */

	private static function getPasswordForCookie()
	{
		return md5( DB::getPassword() );
	}

	private static function setLoginCookie()
	{
		setcookie('login', self::getPasswordForCookie(), time() + AUTH_EXPIRE, '/');
	}

	private static function getLoginCookie()
	{
		return isset($_COOKIE['login']) ? $_COOKIE['login'] : false;
	}

	private static function preparePasswordForDb($password)
	{

		if(!$password)
			return false;

		$salt = self::getSalt();

		if(!$salt)
			$salt = Utils::getRandomString(RANDOM_STR_LENGTH);

		$password = sha1($password . $salt);

		$result = $password . $salt;

		return $result;

	}

	private static function getSalt()
	{

		$content = DB::getPassword();

		return substr($content, RANDOM_STR_LENGTH);

	}

	private static function createPassword()
	{

		if( $_SERVER['REQUEST_URI'] != '/cms/password/' )
			Utils::redirect('/cms/password/');

		if( !empty($_POST) )
		{
			$message =  self::updatePassword();

			if( !$message['error_message'] )
				Utils::redirect('/cms/');

			self::setLoginCookie();

			return $message;

		}

		return array('error_message' => 'Create password.');

	}

	private static function login()
	{

		self::setLoginCookie();

		Utils::redirect('/cms/');

	}

}

?>
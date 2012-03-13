<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

//Includes
require_once 'include.php';

require ROOT_DIR.'/model/fbsdk/facebook.php';

class FacebookHelper
{
	private static $fb = null;

	#
	# Used internally to get the Facebook object.
	#
	private static function GetFB($reload=false)
	{
		if(self::$fb == null || $reload)
		{
			include 'config.php';
			
			self::$fb = new Facebook(array(
				'appId'  => $facebookSettings['appId'],
				'secret' => $facebookSettings['secret']
			));
		}
		
		return self::$fb;
	}
	
	#
	# Destroys the Facebook object.
	#
	public static function Destroy()
	{
		if(self::$fb != null)
		{
			self::GetFB()->destroySession();
			self::$fb = null;
		}
		self::deleteFacebookCookies();
	}
	
	#
	# Reloads the Facebook object.
	#
	public static function Reload()
	{
		if(self::$fb != null)
			self::GetFB()->destroySession();
		
		self::deleteFacebookCookies();
		self::GetFB(true);
	}
	
	#
	# Deletes all server side Facebook cookies for this PHP session.
	#
	private function deleteFacebookCookies()
	{
		include 'config.php';

		$apiKey = $facebookSettings['appId'];

		$cookies = array('user', 'session_key', 'expires', 'ss');
		foreach ($cookies as $name) 
		{
			setcookie($apiKey . '_' . $name, false, time() - 3600);
			unset($_COOKIE[$apiKey . '_' . $name]);
		}

		setcookie($apiKey, false, time() - 3600);
		unset($_COOKIE[$apiKey]);       
	}
	
	#
	# Gets ther current user's Facebook ID. You should not call this directly.
	# Use the User class.
	#
	public static function GetUserID()
	{
		return self::GetFB()->getUser();
	}
	
	#
	# Gets basic Facebook information about the current user. You should not
	# call this directly. Use the User class.
	#
	public static function GetUserInfo()
	{
		$userID = self::GetUserID();
		// TODO: Use cURL for this, requires server config
		return json_decode(file_get_contents("http://graph.facebook.com/${userID}"));
	}
	
	#
	# Gets the Facebook login URL.
	#
	public static function GetLoginURL()
	{
		include 'config.php';
		
		$params = array();
		$params['scope']        = $facebookSettings['scope'];
		$params['redirect_uri'] = $facebookSettings['loginRedirect'];
		
		return self::GetFB()->getLoginUrl($params);
	}
	
	#
	# Gets the Facebook logout URL.
	#
	public static function GetLogoutURL()
	{
		include 'config.php';
		
		$params = array();
		$params['next'] = $facebookSettings['logoutRedirect'];
		
		return self::GetFB()->getLogoutUrl($params);
	}
	
	#
	# Gets the Facebook picture URL for a user.
	#
	public static function GetFacebookPictureURL($userID)
	{
		return "https://graph.facebook.com/${userID}/picture";
	}
	
	#
	# Gets the Facebook profile link for a user.
	#
	public static function GetFacebookProfileLink($userID)
	{
		return "http://www.facebook.com/${userID}";
	}
}

?>
<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

//Includes
include_once '../include.php';

require 'fbsdk/facebook.php';

// Start Session
@session_start();
// Initialize the session Facebook object
createFacebook();

// Creates a Facebook API object for the current session
function createFacebook()
{
	$_SESSION["facebook"] = new Facebook(array(
		'appId'  => '193140624094915',
		'secret' => '5e3d1bab04170d5e66bcf58076984509',
	));
}

// Get the User ID of the current user
function GetUserID()
{
	return $_SESSION["facebook"]->getUser();
}

// Determine if there is a logged in user
function IsLoggedIn()
{
	return (GetUserID() != 0);
}

function GetFBUserInfo($userID)
{
	return json_decode(file_get_contents("http://graph.facebook.com/${userID}"));
}

?>

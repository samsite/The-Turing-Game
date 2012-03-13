<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

//Includes
include_once '../include.php';

class Validator
{
	public static function SafeText($text)
	{
		return trim(strip_tags($text));
	}

	public static function IsValidPOST($expectedParams)
	{
		return self::ValidateArray($_POST, $expectedParams);
	}
	
	public static function IsValidGET($expectedParams)
	{
		return self::ValidateArray($_GET, $expectedParams);
	}
	
	private static function ValidateArray($arr, $expectedParams)
	{
		foreach($expectedParams as $param)
		{
			if(isset($arr[$param]) == false)
				return false;
		}
		return true;
	}
}

?>
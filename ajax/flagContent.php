<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/flags.php';
require_once ROOT_DIR.'/model/users.php';
require_once ROOT_DIR.'/misc/validation.php';

$user = User::FetchCurrentUser();
$expectedParams = array(
	'contentId',
	'type',
	'on'
);

if($user != null &&
   Validator::IsValidPOST($expectedParams) &&
   Flag::IsValidFlagType($_POST['type']))
{
	if($_POST['on'] == 'true')
	{
		Flag::Create(
			$user->GetID(),
			$_POST['contentId'],
			$_POST['type']
		);
	}
	else
	{
		$flag = Flag::FetchByContentAndUser($user->GetID(),
											$_POST['contentId'],
											$_POST['type']);
		if($flag != null)
		{
			echo $flag->GetID();
			$flag->Remove();
		}
	}
}

?>
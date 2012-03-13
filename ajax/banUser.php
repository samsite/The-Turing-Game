<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

require_once 'include.php';

require_once ROOT_DIR.'/misc/validation.php';
require_once ROOT_DIR.'/model/users.php';

$user = User::FetchCurrentUser();
$expectedParams = array(
	'userid',
	'on'
);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	$targetUser = User::FetchByID(intval($_POST['userid']));
	
	if($targetUser != null && $targetUser->GetID() != $user->GetID())
	{
		if($_POST['on'] == 'true')
			$targetUser->Ban();
		else
			$targetUser->UnBan();
		
		$targetUser->CommitChanges();
	}
	
	echo json_encode(true);
}
else
{
	echo json_encode(false);
}

?>
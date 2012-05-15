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
	'id'
);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	$targetUser = User::FetchByID(intval($_POST['id']));
	
	if($targetUser != null && $targetUser->GetID() != $user->GetID())
	{
		if($targetUser->IsBanned())
			$targetUser->UnBan();
		else
			$targetUser->Ban();
		
		$targetUser->CommitChanges();
	}
	
	echo json_encode(true);
}
else
{
	echo json_encode(false);
}

?>
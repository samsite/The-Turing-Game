<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/users.php';
require_once ROOT_DIR.'/misc/validation.php';

$user = User::FetchCurrentUser();
$expectedParams = array(
	'alias'
);

if($user != null && Validator::IsValidPOST($expectedParams))
{
	$displayName = null;
	$valid = false;
	
	$newAlias = Validator::SafeText($_POST['alias']);
	if(User::IsAliasTaken($newAlias) == false)
	{
		$user->SetAlias($newAlias);
		$user->CommitChanges();

		$displayName = $newAlias;
		if($displayName == "")
			$displayName = $user->GetFacebookName();
		
		$valid = true;
	}
	
	$ret = array(
		$displayName,
		$valid
	);
	echo json_encode($ret);
}

?>
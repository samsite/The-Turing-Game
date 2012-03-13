<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

require_once 'include.php';

require_once ROOT_DIR.'/misc/validation.php';
require_once ROOT_DIR.'/model/users.php';
require_once ROOT_DIR.'/model/responses.php';
require_once ROOT_DIR.'/model/admin.php';
require_once ROOT_DIR.'/misc/uihelp.php';

$user = User::FetchCurrentUser();
$expectedParams = array(
	'userid',
	'resultsPerPage'
);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	$responses = Admin::GetResponsesByUID($_POST['userid'], intval($_POST['resultsPerPage']), 0);
	if(count($responses) <= 0)
	{
		echo "No responses found for the specified user.";
	}
	
	for($i = 0; $i < count($responses); $i++)
	{
		CreateResponseResult($responses[$i]);
	}
}

?>
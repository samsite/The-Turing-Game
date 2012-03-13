<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

require_once 'include.php';

require_once ROOT_DIR.'/misc/validation.php';
require_once ROOT_DIR.'/model/users.php';
require_once ROOT_DIR.'/model/admin.php';

$user = User::FetchCurrentUser();
$expectedParams = array(
	'responseID'
);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	Response::RemoveByID($_POST['responseID']);
	
	$success = array("success" => true);
	return json_encode($success);
}
else
{
	$success = array("success" => false);
	return json_encode($success);
}

?>
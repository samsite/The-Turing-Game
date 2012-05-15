<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/admin.php';
require_once ROOT_DIR.'/model/users.php';
require_once ROOT_DIR.'/misc/validation.php';

$user = User::FetchCurrentUser();
$expectedParams = array(
	'id'
);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	$u = User::FetchByID($_POST['id']);
	if($u != null)
		echo $u->JSONEncode();
	else
		echo '0';
}

?>
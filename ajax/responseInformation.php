<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/admin.php';
require_once ROOT_DIR.'/model/responses.php';
require_once ROOT_DIR.'/misc/validation.php';

$user = User::FetchCurrentUser();
$expectedParams = array(
	'id'
);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	$r = Response::FetchByID($_POST['id']);
	if($r != null)
		echo $r->JSONEncode();
	else
		echo '0';
}

?>
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
	'id',
	'suggested'
);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	echo Admin::SetQotD(intval($_POST['id']), $_POST['suggested'] == 'true');
}

?>
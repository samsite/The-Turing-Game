<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/admin.php';
require_once ROOT_DIR.'/model/questions.php';
require_once ROOT_DIR.'/misc/validation.php';

$user = User::FetchCurrentUser();
$expectedParams = array(
	'id',
	'suggested'
);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	$q = null;
	if($_POST['suggested'] == "true")
		$q = Question::FetchSuggestedByID($_POST['id']);
	else
		$q = Question::FetchSelectedByID($_POST['id']);
	
	if($q != null)
		echo $q->JSONEncode();
	else
		echo '0';
}

?>
<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/users.php';
require_once ROOT_DIR.'/model/responses.php';
require_once ROOT_DIR.'/misc/validation.php';

$user = User::FetchCurrentUser();
$qotd = Question::FetchQOTD();
$expectedParams = array(
	'answerText',
	'honest'
);

if($user != null &&
   $qotd != null &&
   $qotd->HasUserAnswered($user) == false &&
   Validator::IsValidPOST($expectedParams))
{
	$answerText = Validator::SafeText($_POST['answerText']);

	Response::Create(
		$user->GetID(),
		$qotd->GetID(),
		$answerText,
		($_POST['honest'] == 'true')
	);
}

?>
<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/users.php';
require_once ROOT_DIR.'/model/questions.php';
require_once ROOT_DIR.'/misc/validation.php';

$user = User::FetchCurrentUser();
$expectedParams = array(
	'questionText',
	'answerAs'
);

if($user != null && Validator::IsValidPOST($expectedParams))
{
	$questionText = Validator::SafeText($_POST['questionText']);
	$answerAsText = Validator::SafeText($_POST['answerAs']);
	
	Question::CreateSuggested(
		$user->GetID(),
		$questionText,
		$answerAsText
	);
        
        echo "true";
}
else
{
    echo "false";
}

?>
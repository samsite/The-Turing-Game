<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/admin.php';
require_once ROOT_DIR.'/misc/validation.php';
require_once ROOT_DIR.'/misc/uihelp.php';

function CreateResults($findFunc, $genFunc)
{
	$list = Admin::$findFunc($_POST['searchString'], intval($_POST['resultsPerPage']), 0);
	if(count($list) <= 0)
	{
		echo "Nothing found.";
	}
	
	for($i = 0; $i < count($list); $i++)
	{
		$genFunc($list[$i]);
	}
}

$user = User::FetchCurrentUser();
$expectedParams = array(
	'searchString',
	'searchFor',
	'resultsPerPage'
);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	switch ($_POST['searchFor'])
	{
		case "Selected Questions":
			CreateResults('FindSelectedQuestions', 'CreateQuestionResult');
			break;
		case "Suggested Questions":
			CreateResults('FindSuggestedQuestions', 'CreateQuestionResult');
			break;
		case "Users":
			CreateResults('FindUsers', 'CreateUserResult');
			break;
		
		case "Flagged Responses":
			CreateResults('FindFlaggedResponses', 'CreateResponseResult');
			break;
			
		default:
			echo "No functions found for ".$_POST['searchFor'];
			break;
	}
	
	$success = array("success" => true);
	return json_encode($success);

}
else
{
	$success = array("success" => false);
	return json_encode($success);
}
?>
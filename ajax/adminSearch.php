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
		echo "Nothing found.";
	else
		$genFunc($list);
}

$user = User::FetchCurrentUser();
$expectedParams = array(
	'searchString',
	'searchFor',
	'resultsPerPage'
);
$success = array("success" => false);

if($user != null &&
   $user->HasAdminPrivileges() &&
   Validator::IsValidPOST($expectedParams))
{
	$success = array("success" => true);
	switch ($_POST['searchFor'])
	{
		case "Selected Questions":
			CreateResults('FindSelectedQuestions', 'CreatePlayedQuestionResults');
			break;
		case "Suggested Questions":
			CreateResults('FindSuggestedQuestions', 'CreateSuggestedQuestionResults');
			break;
		case "Users":
			CreateResults('FindUsers', 'CreateUserResults');
			break;
		case "Flagged Responses":
			CreateResults('FindResponses', 'CreateResponseResults');
			break;
		default:
			$success = array("success" => false);
			break;
	}
}

return json_encode($success);

?>
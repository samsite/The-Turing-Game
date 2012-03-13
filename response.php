<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once 'misc/uihelp.php';
require_once 'model/questions.php';
require_once 'model/responses.php';
require_once 'misc/validation.php';

$expectedParams = array(
	'id'
);

$response = null;
$question = null;

if(Validator::IsValidGET($expectedParams))
{
	$response = Response::FetchByID($_GET['id']);
	if($response != null)
		$question = Question::FetchSelectedByID($response->GetQuestionID());
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>The Turing Game</title>
<link rel="stylesheet" type="text/css" href="css/index.css" />
</head>
<body>
<div style="width:750px; margin:15px;">

<?php

if($response != null && $question != null)
{
	StartBlock('Question', true);

	$questionText = $question->GetText();
	$poseAsText = $question->GetPoseAs();
	echo "${questionText} Answer as a <strong>${poseAsText}</strong>.";

	EndBlock();
	
	echo "<br />";
	
	StartBlock('Response', true);

	echo $response->GetAnswerText();

	EndBlock();
}

?>

</div>
</body>
</html>

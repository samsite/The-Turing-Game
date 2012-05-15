<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

function CreateSuggestedQuestionResults($list)
{
	CreateQuestionResults($list, true);
}

function CreatePlayedQuestionResults($list)
{
	CreateQuestionResults($list, false);
}

function CreateQuestionResults($list, $suggested)
{
	for($i = 0; $i < count($list); $i++)
	{
		$question = $list[$i];
		$questionText = $question->GetText();
		$poseAs = $question->GetPoseAs();
		$id = $question->GetID();
		$isSuggested = $question->IsSuggested();
		$func = $suggested ? "selectSuggestedQuestion" : "selectPlayedQuestion";
		
		$colortag = "";
		if($i % 2 == 0)
			$colortag = " altcolor";
		
echo <<<EOT
		<div class="result${colortag}" onclick="${func}(${id});">
			<strong>${questionText}</strong> Answer as a <strong>${poseAs}</strong>.
		</div>
EOT;
	}
}

function CreateUserResults($list)
{
	for($i = 0; $i < count($list); $i++)
	{
		$user = $list[$i];
		$fbName = $user->GetFacebookName();
		$alias = $user->GetAlias();
		$alias = ($alias != "") ? '"' . $alias . '"' : "";
		$id = $user->GetID();
		
		$colortag = "";
		if($i % 2 == 0)
			$colortag = " altcolor";

echo <<<EOT
		<div class="result${colortag}" onclick="selectUser(${id});">
			<strong>${fbName}</strong> ${alias}
		</div>
EOT;
	}
}

function CreateResponseResults($list)
{
	for($i = 0; $i < count($list); $i++)
	{
		$response = $list[$i];
		$responseText = $response->GetAnswerText();
		$id = $response->GetID();
		$respondingUser = $response->GetUserID();
		
		$colortag = "";
		if($i % 2 == 0)
			$colortag = " altcolor";

echo <<<EOT
		<div class="result${colortag}" onclick="selectResponse(${id});">
			<strong>${responseText}</strong>
		</div>
EOT;
	}
}

?>

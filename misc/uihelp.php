<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Starts a styled HTML content block
//
// $title: Title to display for block
// $pad:   Boolean to indicate if contents should be padded by 15px
function StartBlock($title, $pad)
{
	$blockContentClass = ' class="blockContent"';
	if($pad == false)
		$blockContentClass = "";
		
	echo '<div class="block shadow">
			<div class="header">
				<div class="headerin">'.
					$title.
				'</div>
			</div>';
	echo '	<div'. $blockContentClass .'>';
}

function StartPopupBlock($title, $pad, $closeButtonID)
{
	$blockContentClass = ' class="blockContent"';
	if($pad == false)
		$blockContentClass = "";
		
	echo "<div class='block shadow'>
			<div class='header'>
				<div class='headerin'>
					${title}
					<img id='${closeButtonID}' class='closeButton' src='images/close.png' />
				</div>
			</div>
			<div${blockContentClass}>";
}

// TODO: Rework popup system
function StartPopupBlockNoClose($title, $pad, $closeButtonID)
{
	$blockContentClass = ' class="blockContent"';
	if($pad == false)
		$blockContentClass = "";
		
	echo "<div class='block shadow'>
			<div class='header'>
				<div class='headerin'>
					${title}
				</div>
			</div>
			<div${blockContentClass}>";
}

// Ends a styled HTML content block
function EndBlock()
{
	echo '</div>
		  </div>';
}

// Creates a login prompt HTML block
function CreateLoginPromptBlock()
{
	startBlock('Please Login', true);
	echo 'Please login above to play the Turing Game.';
	endBlock();
}

function CreateAnonymousResponse($answerText, $responseID)
{
$permalink = "http://128.61.105.227/response.php?id=${responseID}";

echo <<<EOT
<div id="a_${responseID}" class="answer answerTag">
	<div class="answerflag">
		<img class="flag" id="f_${responseID}" onClick="toggleResponseFlag(${responseID});" src="images/flag_gray.png" />
	</div>
	<div class="responseflagged" id="af_${responseID}">
		Response marked as inappropriate
	</div>
	<div id="ac_${responseID}">
		<div class="answertext">
			"${answerText}"
		</div>
		<div class="answercontrols">
			<div class="answerlike">
				<fb:like href="{$permalink}" layout="button_count" send="true" show_faces="false"></fb:like>   
			</div>
			<div class="answervote">
				<div class="left">Lying</div>
				<div class="left">
					<input id="v_${responseID}" class="controlpad" type="range" min="0" max="10" />
				</div>
				<div class="left">Truthful</div>
				<div class="buttonlight left controlpad" onClick="vote(${responseID});">
					<div class="buttonlightin">
						<span>Vote</span>
					</div>
				</div>
				<div id="s_${responseID}" class="votespinner"><img src="images/votespinner.gif" /></div>
			</div>
		</div>
	</div>
</div>
EOT;
}

function CreateNonAnonymousResponse($answerText, $name, $responseID, $fblink, $percent, $honest)
{
$permalink = "http://128.61.105.227/response.php?id=${responseID}";

$nameStr = $name;
if($fblink != null)
	$nameStr = "<a href='${fblink}'>${name}</a>";

$color = "#008900";
$msg = "they were right!";
if(($percent < 50.0 && $honest == true) || ($percent >= 50.0 && $honest == false))
{
	$color = "#ae1212";
	$msg = "they were fooled!";
}

echo <<<EOT
<div class="answer">
	<div class="answerflag">
		<img class="flag" id="f_${responseID}" onClick="toggleResponseFlag(${responseID});" src="images/flag_gray.png" />
    </div>
	<div class="responseflagged" id="af_${responseID}">
		Response marked as inappropriate
	</div>
	<div id="ac_${responseID}">
		<div class="answertext">
			"${answerText}"
		</div>
		<div class="answercontrols">
			<div class="answerlike left">
				<fb:like href="{$permalink}" layout="button_count" send="true" show_faces="false"></fb:like>
			</div>
			<div class="left">
				${nameStr}'s answer
			</div>
			<div class="answervote">
				User Verdict: <strong>${percent}%</strong> Truthful, <a style="color:${color}"><strong>${msg}</strong></a>
			</div>
		</div>
	</div>
</div>
EOT;
}

function CreateQuestionResult($question)
{
$questionText = $question->GetText();
$qid = $question->GetID();
$isSuggested = $question->IsSuggested();

echo <<<EOT
<div class="answer">
	<div class="answertext">
		"${questionText}"
	</div>
	<div class="answercontrols">
		<button onclick="selectQuestion(${qid}, ${isSuggested});">Select For Play</button>
	</div>
</div>
EOT;
}

function CreateUserResult($user)
{
$fbName = $user->GetFacebookName();
$alias = $user->GetAlias();
$id = $user->GetID();

$buttonCode = "<button onclick='makeAdmin(${id}, true);'>Make Admin</button>";
if($user->HasAdminPrivileges())
	$buttonCode = "<button onclick='makeAdmin(${id}, false);'>Remove Admin</button>";

echo <<<EOT
<div style="position:relative; height:32px">
	<strong>${fbName}</strong> "${alias}"<br />
	<div style="position:absolute; top:0px; right:0px">
		${buttonCode}&nbsp;&nbsp;<button onclick="showUserResponses(${id});">Responses</button>
	</div>
</div>
EOT;
}

function CreateResponseResult($response)
{
$responseText = $response->GetAnswerText();
$responseID = $response->GetID();
$respondingUser = $response->GetUserID();

echo <<<EOT
<div class="answer">
	<div class="answertext">
		"${responseText}"
	</div>
	<div class="answercontrols">
		<button onclick="deleteResponse(${responseID});">Delete Response</button>
		
	</div>
</div>
EOT;
//<button onclick="searchUser(${respondingUser});">Search User</button>
}

?>

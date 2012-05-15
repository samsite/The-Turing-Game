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
require_once 'model/users.php';

$user = User::FetchCurrentUser();
$qotd = Question::FetchQOTD();

if($user != null)
{
	if($user->IsBanned())
	{
		header('Location: banned.php');
		exit(0);
	}
}

?>

<?php
/////////////////////////////////////////////////////////////////////////
// Begin: Not answered and logged in content block
/////////////////////////////////////////////////////////////////////////
if($user != null && !$qotd->HasUserAnswered($user))
{
?>

	<?php StartBlock('Question', true); ?>
	<div id="answer">
		<div><?php echo $qotd->GetText(); ?> Answer as a <strong><?php echo $qotd->GetPoseAs(); ?></strong>.</div>
		<div class="vspacer" />
		<div>
			<textarea id="answerText" class="answerarea" rows="5" />
		</div>
		<div class="vspacer" />
		<div class="buttonrow">
			<input id="answerbutton" class="button" type="button" value="Answer" />
		</div>
	</div>
	<?php EndBlock(); ?>

	<div class="popup" id="honestAnswerPopup">
		<?php StartPopupBlock('Almost Done...', true, 'answerCloseButton'); ?>
			<div class="honestAnswerPopupContent">
				<div>
					Are you truly a <strong><?php echo $qotd->GetPoseAs(); ?></strong>?
				</div>
				<div class="vspacer" />
				<div class="buttonrow">
					<input id="answertrue" type="button" class="button" value="Yes" />
					&nbsp;&nbsp;
					<input id="answerfalse" type="button" class="button" value="No" />
				</div>
			</div>
		<?php EndBlock(); ?>
	</div>

	<div class="popupBack" id="honestAnswerPopupBack" />

<?php
}
/////////////////////////////////////////////////////////////////////////
// End: Not answered and logged in content block
/////////////////////////////////////////////////////////////////////////
?>



<?php
/////////////////////////////////////////////////////////////////////////
// Begin: Already answered and logged in content block
/////////////////////////////////////////////////////////////////////////
if($user != null && $qotd->HasUserAnswered($user))
{
?>

	<?php StartBlock('Question', true); ?>
		<div><?php echo $qotd->GetText(); ?> Answer as a <strong><?php echo $qotd->GetPoseAs(); ?></strong>.</div>
		<br />
		<div id="myanswer">
		<?php
			$answerText = $qotd->GetUserResponse($user)->GetAnswerText();
			echo "\"${answerText}\"";
		?>
		</div>
	<?php EndBlock(); ?>

	<br />

	<?php
		// This php block prints the answers section
		StartBlock("Answers", false);

		$responses = $qotd->GetUnvotedResponses($user);
		$responseCount = count($responses);
		if($responseCount == 1)
			$responses = array($responses);
		
		for($i = 0; $i < $responseCount; $i++)
		{
			CreateAnonymousResponse($responses[$i]->GetAnswerText(),
									$responses[$i]->GetID());
		}
		
		$hideVotedAll = "";
		if($responseCount > 0)
			$hideVotedAll = " style='display:none'";
		
		echo "<div id='answermsg'${hideVotedAll}>".
			 "You have voted on all responses!".
			 "</div>";
		
		EndBlock();
	?>
	

<?php
}
/////////////////////////////////////////////////////////////////////////
// End: Already answered and logged in content block
/////////////////////////////////////////////////////////////////////////
?>



<?php

/////////////////////////////////////////////////////////////////////////
// Begin: Not logged in content block
/////////////////////////////////////////////////////////////////////////
if($user == null)
{
	CreateLoginPromptBlock();
}
/////////////////////////////////////////////////////////////////////////
// End: Not logged in content block
/////////////////////////////////////////////////////////////////////////

?>

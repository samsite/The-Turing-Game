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
require_once 'model/facebook.php';

$qotds    = Question::FetchNPreviousQOTD(5);
$loginURL = FacebookHelper::GetLoginURL();
$picURL   = '';
$name     = '';
$user     = User::FetchCurrentUser();

if($user != null)
{
	if($user->IsBanned())
	{
		header('Location: banned.php');
		exit(0);
	}
	
	// Update user Facebook info every time suggest.php is loaded
	$user->GetFacebookInfo(true);
	$picURL = $user->GetFacebookPictureURL();
	
	$name = $user->GetAlias();
	if($name == "")
		$name = $user->GetFacebookName();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>The Turing Game</title>
		<script type="text/javascript" src="javascript/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="javascript/jquery-ui-1.8.18.custom.min.js"></script>
		<script type="text/javascript" src="javascript/fb.js"></script>
		<script type="text/javascript" src="javascript/popup.js"></script>
		<script type="text/javascript" src="javascript/common.js"></script>
		<script type="text/javascript" src="javascript/previous.js"></script>
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/popup.css" />
		<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.18.custom.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/previous.css" />
	</head>
	<body>
		<div id="fb-root"></div>
		<script>includeFacebookSDK(document,'script','facebook-jssdk');</script>
		<div id="header">
			<img id="title" src="images/titletext.png" alt="The Turing Game" />
<?php
/////////////////////////////////////////////////////////////////////////
// Display user widget if user is signed in
/////////////////////////////////////////////////////////////////////////
if($user != null)
{
?>
			<div id="userinfo">
				<table>
					<tr>
						<td><img src="images/uil.png" /></td>
						<td class="usercell">
							<img src="<?php echo $picURL; ?>" width="40" height="40" />
						</td>
						<td class="usercell usertext">
							<span id="username"><?php echo $name; ?></span>
							<div id="userlinks">
								<a class="userlink" id="logoutLink" href="#">Logout</a>
								&nbsp;|&nbsp;
								<a class="userlink" id="accountLink" href="#">Account</a>
							</div>
						</td>
						<td><img src="images/uir.png" /></td>
					</tr>
				</table>
   		    </div>
<?php
}
/////////////////////////////////////////////////////////////////////////
// Display SIGN IN button if user is not signed in
/////////////////////////////////////////////////////////////////////////
else
{
?>
			<a id="signin" class="button" onClick="window.location='<?php echo $loginURL; ?>'">SIGN IN</a>
<?php
}
?>
		</div>
		<div id="navwrapper">
			<table id="navbar">
				<tr>
					<td><img src="images/navl.png" /></td>
					<td id="navcenter">
						<ol id="menu">
							<li id="indexpg">TODAY'S QUESTION</li>
							<li class="selected" id="previouspg">PREVIOUS QUESTIONS</li>
							<li id="suggestpg">SUGGEST QUESTION</li>
							<li id="aboutpg">ABOUT</li>
							<?php
							if($user != null && $user->HasAdminPrivileges())
							{
							?>
							<li id="adminpg" onclick="window.location='admin.php'">ADMIN</li>
							<?php
							}
							?>
						</ol>
					</td>
					<td><img src="images/navr.png" /></td>
				</tr>
			</table>
		</div>
		<div id="content">
<?php
/////////////////////////////////////////////////////////////////////////
// Not logged in content block
/////////////////////////////////////////////////////////////////////////
if($user == null)
{
?>
			<div class="box">
				<img src="images/notsignedin.png" alt="You're not logged in!" />
				<br/><br/>
				Please <a id="signinlink" href="#" onClick="window.location='<?php echo $loginURL; ?>'">sign in with Facebook</a> to play the Turing Game.
			</div>
<?php
}
/////////////////////////////////////////////////////////////////////////
// Logged in and at least one previous question exists
/////////////////////////////////////////////////////////////////////////
else if($qotds != null)
{
	for($q = 0; $q < count($qotds); $q++)
	{
		$qotd = $qotds[$q];
?>
			<div class="box">
				<img src="images/question.png" alt="Previous Question of the Day" />
				<img class="pqexpand" src="images/pqexpand.png" id="expand<?php echo $q; ?>" alt="+" onclick="toggleQ(<?php echo $q; ?>);" />
				<br/><br/>
				<span class="subHeading"><?php echo $qotd->GetText(); ?> Answer as a <strong><?php echo $qotd->GetPoseAs(); ?></strong>.</span>
			</div>
<?php
		/////////////////////////////////////////////////////////////////////////
		// User has answered previous question
		/////////////////////////////////////////////////////////////////////////
		if($qotd->HasUserAnswered($user))
		{
?>
			<div class="box qtag" id="q<?php echo $q; ?>_r">
				<img src="images/yourresponse.png" alt="Your Response" />
				<br/><br/>
				<span class="subHeading"><?php echo $qotd->GetUserResponse($user)->GetAnswerText(); ?></span>
			</div>
<?php
		}
	/////////////////////////////////////////////////////////////////////////
	// User answers section
	/////////////////////////////////////////////////////////////////////////
?>
			<div class="box qtag" id="q<?php echo $q; ?>_a">
				<img src="images/answers.png" alt="Answers" />
				<br/><br/>
				<?php
					$responses = array();
					if($user != null)
						$responses = $qotd->GetUnflaggedResponsesForUser($user);
					else
						$responses = $qotd->GetResponses();
					
					$responseCount = count($responses);
					if($responseCount == 1)
						$responses = array($responses);
					
					for($i = 0; $i < $responseCount; $i++)
					{
						$response = $responses[$i];
						$responseUser = User::FetchByID($response->GetUserID());
						
						$profileLink = null;
						$nameStr = $responseUser->GetAlias();
						if($nameStr == "")
						{
							$nameStr = $responseUser->GetFacebookName();
							$profileLink = $responseUser->GetFacebookProfileLink();
							$nameStr = "<a href='${profileLink}'>${nameStr}</a>";
						}
						
						$percent = 100.0 - ($response->GetAverageRating() + 0.0) * 10.0;
						
						$id = $responses[$i]->GetID();
						$text = $responses[$i]->GetAnswerText();
						$permalink = "http://128.61.105.227/response.php?id=${id}";
						$honest = $response->IsHonest();
						
						$left = "";
						$right = "";
						$poseAs = ucwords($qotd->GetPoseAs());
						
						if($honest)
						{
							$right = $poseAs;
							$left = "Not a " . $right;
						}
						else
						{
							$left = $poseAs;
							$right = "Not a " . $left;
						}
						
						$markeroffset = intval(11.0 + 274.0 * ($percent / 100.0));
						
echo <<<EOT
						<div id="a_${id}" class="answer">
							<div class="answerUser">
								${nameStr}<span id="u_${id}"> said:</span>
								<div class="answerFlag">
									<img class="flag" id="f_${id}" onClick="toggleResponseFlag(${id});" src="images/flag_gray.png" />
								</div>
							</div>
							<div id="c_${id}">
								<div class="answerText">${text}</div>
								<div class="answerControls">
									<fb:like href="${permalink}" layout="button_count" send="true" show_faces="false"></fb:like>
									<div class="answerResult">
										<img class="marker" style="left:${markeroffset}px;" src="images/verdictmarker.png" />
										<span class="verdictleft">${left}</span>
										<span class="verdictright">${right}</span>
									</div>
								</div>
							</div>
						</div>
EOT;
					}
				?>
			</div>
			<br /><br />
<?php
	}
}
/////////////////////////////////////////////////////////////////////////
// Logged in and no previous question exists
/////////////////////////////////////////////////////////////////////////
else
{
?>
			<div class="box">
				<img src="images/pqofdheader.png" alt="Previous Question of the Day" />
				<br/><br/>
				<span id="question">There are no previous questions. Sorry!</span>
			</div>
<?php
}
?>
			<div class="popup" id="accountPopup">
				<span id="accountMsg"></span><br /><br /><br />
				<div id="accountAlias">
					<strong>Alias:</strong><input id="accountAliasTBox" class="textbox" type="text"></input>
				</div>
				<br />
				<a id="accountSaveButton" class="button">Save</a>
				<a id="accountCloseButton" class="button">Close</a>
			</div>
			<div class="popup" id="logoutPopup">
				<img src="images/thanks.png" alt="Thanks for playing!" />
				<br/><br/>
				Logging out of the Turing Game...
			</div>
		</div>
	</body>
</html>
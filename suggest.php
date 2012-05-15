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

$qotd     = Question::FetchQOTD();
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
		<script type="text/javascript" src="javascript/suggest.js"></script>
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/popup.css" />
		<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.18.custom.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/suggest.css" />
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
							<li id="previouspg">PREVIOUS QUESTION</li>
							<li class="selected" id="suggestpg">SUGGEST QUESTION</li>
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
// Logged in content block
/////////////////////////////////////////////////////////////////////////
if($user != null)
{
?>
			<div id="qform" class="box">
				<img src="images/suggestQ.png" alt="Suggest A Question" />
				<br/><br/>
				<textarea id="questionText" rows="8"></textarea>
				<br/><br/>
				Answer as a <input id="answerAs" type="text"><a id="submitButton" class="button">SUBMIT</a>
			</div>
			<div id="thanksMsg" class="box">
				<img src="images/qthanks.png" alt="Suggest A Question" />
				<br/><br/>
				<span class="subHeading"><a id="submitNewQ" href="#">Click here</a> if you'd like to submit another.</span>
			</div>
<?php
}
/////////////////////////////////////////////////////////////////////////
// Not logged in content block
/////////////////////////////////////////////////////////////////////////
else
{
?>
			<div class="box">
				<img src="images/notsignedin.png" alt="You're not logged in!" />
				<br/><br/>
				Please <a id="signinlink" href="#" onClick="window.location='<?php echo $loginURL; ?>'">sign in with Facebook</a> to play the Turing Game.
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
		</div>
	</body>
</html>
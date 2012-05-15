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
		<script type="text/javascript" src="javascript/fb.js"></script>
		<script type="text/javascript" src="javascript/popup.js"></script>
		<script type="text/javascript" src="javascript/common.js"></script>
		<script type="text/javascript" src="javascript/about.js"></script>
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/popup.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/about.css" />
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
							<li id="suggestpg">SUGGEST QUESTION</li>
							<li class="selected" id="aboutpg">ABOUT</li>
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
			<div class="box">
				<img src="images/newtogame.png" alt="New to the Turing Game?" />
				<br/><br/>
				Do men and women behave differently online? Can you tell who is a man and who is a woman on the Internet based on how they interact with others? Can you tell how old someone is, or determine their race or national origin? In the online world as in the real world, issues of personal identity affect how we relate to others. Culture in online environments is created by these understandings and misunderstandings. As the net grows not only in size but in diversity, we must take these issues into account as designers and citizens of this new medium.
				<br /><br />
				Is it possible to create a genderless classroom? A raceless courtroom? A rich environment where a user can be not just a pseudonym, but a person with a full history of culturally-bound experiences? For community designers, these are crucial questions. The Turing Game is a participatory collaborative learning experience to help us understand these phenomena.
				<br /><br />
				Here at the Georgia Institute of Technology, we have created a game to help us understand issues of online identity. In this environment, which we call The Turing Game, a panel of users all pretend to be a member of some group, such as women. Some of the users, who are women, are trying to prove that fact to their audience. Others are men, trying to masquerade as women. An audience of both genders tries to discover whom the imposters are, by asking questions and analyzing the panel members' answers. Games can cover aspects of gender, race, or any other cultural marker of the users' choice. Currently, we have a working version and we are in the Beta testing phase.
			</div>
			<div class="box">
				<img src="images/credits.png" alt="Credits" />
				<br/><br/>
				<strong>Instructor</strong><br />
				<div class="creditbox">
					Amy Bruckman
				</div>
				<br /><br />
				<strong>Developers</strong><br />
				<div class="creditbox">
				Sam Brown<br />
				Henry Dooley<br />
				Gaurav Mathur<br />
				Kyle Stafford
				</div>
			</div>
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
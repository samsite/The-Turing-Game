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
}

if($user == null || $user->HasAdminPrivileges() == false)
{
	header('Location: index.php');
	exit(0);
}

if($user != null)
{
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
		<script type="text/javascript" src="javascript/admin.js"></script>
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/popup.css" />
		<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.8.18.custom.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/admin.css" />
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
							<li id="previouspg">PREVIOUS QUESTIONS</li>
							<li id="suggestpg">SUGGEST QUESTION</li>
							<li id="aboutpg">ABOUT</li>
							<li class="selected" id="adminpg">ADMIN</li>
						</ol>
					</td>
					<td><img src="images/navr.png" /></td>
				</tr>
			</table>
		</div>
		<div id="content">
			<div id="adminmenucontainer">
				<ol id="adminmenu">
					<li class="adminselected" id="a_userpg">Users</li>
					<li id="a_playedpg">Played Questions</li>
					<li id="a_suggestpg">Suggested Questions</li>
					<li id="a_responsepg">Responses</li>
				</ol>
			</div>
			<div id="admincontent">
				<div id="contentlist">
					<strong><span id="listfiltertitle"></span></strong><br />
					<input id="listfilter" class="textbox" type="text"></input>
					<a id="listsearch" class="button">Search</a>
					<br /><br /><br /><br /><br />
					<strong><span id="listresultstitle"></span></strong>
					<div id="listresults">
					</div>
				</div>
				<div id="userEditor">
					<a href="#" id="ue_return"><< Return to search</a>
					<br /><br /><br />
					<h1 id="ue_name">Test User</h1>
					<div class="editorContent">
						<br /><br />
						<h2>User Information</h2>
						<div class="sep"></div>
						<div class="info infoL">
							Alias:<br />
							ID:<br />
							Banned:<br />
							Admin:<br />
							Join Date:<br />
						</div>
						<div class="info">
							<span id="ue_alias">Test Alias</span><br />
							<span id="ue_id">51242323</span><br />
							<span id="ue_banned">No</span><br />
							<span id="ue_admin">No</span><br />
							<span id="ue_joined">April 1, 1984 @ 5:15 PM</span><br />
						</div>
						<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<h2>Actions</h2>
						<div class="sep"></div>
						<a id="ue_a_ban" class="button hbutton">Ban User</a>
						<a id="ue_a_admin" class="button hbutton">Make Admin</a>
					</div>
				</div>
				<div id="questionEditor">
					<a href="#" id="qe_return"><< Return to search</a>
					<br /><br /><br />
					<h1 id="qe_question">This is a question?</h1>
					<div class="editorContent">
						<br /><br />
						<h2>Question Information</h2>
						<div class="sep"></div>
						<div class="info infoL">
							ID:<br />
							User ID:<br />
							Ranking:<br />
							Date Posted:<br />
						</div>
						<div class="info">
							<span id="qe_id">Test Alias</span><br />
							<span id="qe_uid">51242323</span><br />
							<span id="qe_ranking">No</span><br />
							<span id="qe_date">No</span><br />
						</div>
						<br /><br /><br /><br /><br /><br /><br /><br />
						<h2>Actions</h2>
						<div class="sep"></div>
						<a id="qe_a_play" class="button hbutton">Play</a>
					</div>
				</div>
				<div id="responseEditor">
					<a href="#" id="re_return"><< Return to search</a>
					<br /><br /><br />
					<h1 id="re_response">This is response text</h1>
					<div class="editorContent">
						<br /><br />
						<h2>Response Information</h2>
						<div class="sep"></div>
						<div class="info infoL">
							ID:<br />
							Question ID:<br />
							User ID:<br />
							Honest:<br />
							Date Posted:<br />
							Votes:<br />
							Rating:<br />
						</div>
						<div class="info">
							<span id="re_id">142</span><br />
							<span id="re_qid">25</span><br />
							<span id="re_uid">122135423</span><br />
							<span id="re_honest">No</span><br />
							<span id="re_date">April 3, 2010 @ 5:15 PM</span><br />
							<span id="re_votes">13</span><br />
							<span id="re_rating">1.5</span><br />
						</div>
						<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
						<h2>Actions</h2>
						<div class="sep"></div>
						<a id="re_a_delete" class="button hbutton">Delete</a>
					</div>
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
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>The Turing Game</title>
		<link rel="stylesheet" type="text/css" href="css/reset.css" />
		<link rel="stylesheet" type="text/css" href="css/common.css" />
		<link rel="stylesheet" type="text/css" href="css/index.css" />
	</head>
	<body>
		<div id="fb-root"></div>
		<script>includeFacebookSDK(document,'script','facebook-jssdk');</script>
		<div id="header">
			<br /><br />
			<center><img src="images/titletext.png" alt="The Turing Game" /></center
		</div>
		<div id="content">
			<div class="box">
				<img src="images/banned.png" alt="Question of the Day" />
				<br/><br/>
				<span class="subHeading">You have been banned from the Turing Game for misconduct.</span>
			</div>
		</div>
	</body>
</html>
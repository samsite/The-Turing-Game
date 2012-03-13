<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// This script is executed after Facebook has logged the user out on
// their servers

// Includes
require_once 'include.php';

require_once 'model/facebook.php';

$logoutURL = FacebookHelper::GetLogoutURL();
FacebookHelper::Destroy();

// Redirect to index.php
header("Location: index.php");

?>
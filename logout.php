<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// This script is executed first to initiate the logout process.

// Includes
require_once 'include.php';

require_once 'model/facebook.php';

$logoutURL = FacebookHelper::GetLogoutURL();
FacebookHelper::Destroy();

// Redirect to Facebook logout which redirects to index.php
header("Location: ${logoutURL}");

?>
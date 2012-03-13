<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once ROOT_DIR.'/model/users.php';

$user = User::FetchCurrentUser();

if($user != null)
	echo json_encode($user->GetAlias());

?>
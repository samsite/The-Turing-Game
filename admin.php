<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

// Includes
require_once 'include.php';

require_once 'misc/uihelp.php';
require_once 'model/users.php';

$user = User::FetchCurrentUser();

if($user == null || $user->HasAdminPrivileges() == false)
	exit(0);
	
?>

<?php
StartBlock("Search", true);
?>

<LABEL for="firstname">Search: </LABEL>
		  <INPUT id="searchString" type="text">
		  
<select id="searchFor">
	<option value="Users">Users</option>
	<option value="Selected Questions">Selected Questions</option>
	<option value="Suggested Questions">Suggested Questions</option>
	<option value="Flagged Responses">Flagged Responses</option>
	
</select>

<select id="resultsPerPage">
	<option value="10">10</option>
	<option value="25">25</option>
</select>

<INPUT id="search" type="submit" value="Search"><BR>

<?php
EndBlock();
?>

<br /><br />

<?php
StartBlock("Results", false);
?>
<div id="Results"></div>
<?php
EndBlock();
?>

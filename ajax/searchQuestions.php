<?php

require_once 'include.php';

require_once ROOT_DIR.'/model/users.php';
require_once ROOT_DIR.'/model/questions.php';
require_once ROOT_DIR.'/model/jsonEncodable.php';

$user = User::FetchCurrentUser();
if($user != null and
   $user->HasAdminPrivileges() and
   isset($_POST['str']) and
   isset($_POST['suggested']))
{
	echo JSONEncoder::EncodeArray(Question::Find($_POST['str'], $_POST['suggested']));
}
else
{
	// redirect to 404 or 403 here if conditions fail
}

?>
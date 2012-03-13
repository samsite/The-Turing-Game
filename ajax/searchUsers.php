<?php

require_once 'include.php';

require_once ROOT_DIR.'/model/users.php';
require_once ROOT_DIR.'/model/jsonEncodable.php';

$user = User::FetchCurrentUser();
if($user != null and
   $user->HasAdminPrivileges() and
   isset($_POST['str']))
{
	echo JSONEncoder::EncodeArray(User::Find($_POST['str']));
}
else
{
	// redirect to 404 or 403 here if conditions fail
}

?>
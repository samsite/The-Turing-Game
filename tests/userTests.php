<?php

include_once '../model/database.php';
include_once '../model/users.php';

echo '<strong>Accessor Method Tests:</strong><br/><br />';

echo Database::GetConnID();

$arr = User::FetchAll();
array_push($arr, User::FetchByID(1297110596));

for($i = 0; $i < count($arr); $i++)
{
	echo $arr[$i]->GetID();
	echo '<br/>';
	echo $arr[$i]->GetAlias();
	echo '<br/>';
	echo $arr[$i]->IsBanned();
	echo '<br/>';
	echo $arr[$i]->GetJoinDate();
	echo '<br/>';
	echo $arr[$i]->GetLastLoginDate();
	echo '<br/>';
	echo '<br/>';
}

echo '<strong>Create Tests:</strong><br/><br />';

$newUsr = User::Create(123,'This is an alias',false, 'Sam Brown', 0);

echo '<strong>Modify Tests:</strong><br/><br />';

$newUsr->SetAlias('this is a new alias');
$newUsr->Ban();
$newUsr->CommitChanges();

echo '<strong>Remove Tests:</strong><br/><br />';

$newUsr->Remove();

?>

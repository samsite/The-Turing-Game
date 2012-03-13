<?php

/////////////////////////////////////////////////////////////////////////
// Team Backpack: Sam Brown, Henry Dooley, Gaurav Mathur, Kyle Stafford
// CS 4911 Senior Design for Amy Bruckman
// The Turing Game
/////////////////////////////////////////////////////////////////////////

$servers = array(
	'default' => array(
		'host'     => '127.0.0.1',
		'database' => 'backpackschema',
		'username' => 'turinggame',
		'password' => '[turing]game!',
		'type'     => 'mysql'
	)
);

$facebookSettings = array(
	'appId'          => '193140624094915',
	'secret'         => '5e3d1bab04170d5e66bcf58076984509',
	'scope'          => 'email',
	'loginRedirect'  => 'http://128.61.105.227/',
	'logoutRedirect' => 'http://128.61.105.227/logoutRedirect.php'
);

?>
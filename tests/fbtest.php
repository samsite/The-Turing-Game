<?php

include_once 'include.php';
include_once 'model/facebook.php';
include_once 'model/responses.php';

Response::CalculateResponseScores();

/*@session_start();

echo 'Session before:<br/>';
var_dump($_SESSION);

FacebookHelper::Destroy();
echo '<br /><br /><br />';

echo 'Session after facebook destroy:<br/>';
var_dump($_SESSION);

FacebookHelper::Reload();
echo '<br /><br /><br />';

echo 'Session after facebook reload:<br/>';
var_dump($_SESSION);*/

?>
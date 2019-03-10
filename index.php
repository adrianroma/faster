<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include ('action.php');
include ('factory.php');
$action = new action();

$action->getUrl();

$module = $action->rewrite($action->uri);

$start = new factory($action,$module['module']);

?>

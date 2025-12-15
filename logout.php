<?php
session_start();
$_SESSION = Array();

print_r($_SESSION);
$url = 'http://' . $_SERVER['HTTP_HOST']; // Get the server
$url .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
header('Location: '.$url);
?>

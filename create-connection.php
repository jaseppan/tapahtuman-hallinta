<?php
// Create connection

include("db.php");

$mysqli = new mysqli($servername, $username, $password, $dbname);

if(mysqli_connect_errno()) {
	  echo "Tietokantayhteys ei onnistunut ";
	  exit();
}

// Change character set to utf8
mysqli_set_charset($mysqli,"utf8"); 
?>

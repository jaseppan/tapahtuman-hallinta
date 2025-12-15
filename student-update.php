<?php
checkPermission(2);
// Päivitetään _seminarman_application

$sql = "UPDATE so_seminarman_application SET first_name='" . $_POST['first_name'] . "', last_name='" . $_POST['last_name'] . "', email='" . $_POST['email'] . "' WHERE `id` = " . $_GET['id'];

if($mysqli->query($sql) === false) {
  $error = $mysqli->mysqli_error;
} else {
  $affected_rows = $mysqli->affected_rows;
}

//Päivitetään lisätiedot

$loop_start = 3; // Kohta josta $_POST arrayssa alkaa so_seminarman_fields_values -taulua koskevat arvot
$keys = array_keys($_POST);

for ($x = 0 + $loop_start; $x <= count($_POST) - 2; $x++) {
	$tmp = $keys[$x];

	$sql = "UPDATE so_seminarman_fields_values SET value = '" . $_POST[$tmp]  . "' WHERE applicationid = " . $_GET['id'] . " AND field_id = " . $tmp;
	echo $sql . "<br />";

	if($mysqli->query($sql) === false) {
	  echo ('Tietojen päivitys epäonnistui');
	} else {
	  $affected_rows = $mysqli->affected_rows;
	}
}

?>

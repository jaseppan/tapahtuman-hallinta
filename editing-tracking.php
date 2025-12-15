<?php
// Tarkistetaan onko henkilön muokkaus varattu
$sql = "SELECT * FROM `so_js_event_manager_editing_tracking` WHERE `user_id` = " . $_GET['id'];

$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$editing_id = $row["id"];
		$editing_user_id = $row["user_id"];
		$admin_id = $row["admin_id"];
		$editing_starting_time =  $row["starting_time"];						
	}
}

if( !isset($admin_id) && $admin_id !== $_SESSION['admin_id'] ) {
	$timestamp = time();	
	$editingMsg = "Käyttäjän <b>" . $user->name[0] . "</b> tietojen editointi on varattu käyttöösi seuraavaksi viideksi minuutiksi tai kunnes olet <b>tallentanut</b> tiedot";

	if(!$editing_id) {
		$sql = "INSERT INTO `so_js_event_manager_editing_tracking` (user_id, admin_id, starting_time) VALUES (" . $_GET['id'] . ", " . $_SESSION['admin_id'] . ", " . $timestamp . ")";
		if ($mysqli->query($sql) === TRUE) {
			echo $editingMsg;
		}
	} else {
		$age = $timestamp - $editing_starting_time;
		// Jos varaus on vanha
		if($age > 300) {
			// Poistetaan vanha varaus
			$sql = "DELETE FROM `so_js_event_manager_editing_tracking` WHERE id = " . $editing_id;
			$mysqli->query($sql);
			// Lisätään uusi
			$sql = "INSERT INTO `so_js_event_manager_editing_tracking` (user_id, admin_id, starting_time) VALUES (" . $_GET['id'] . ", " . $_SESSION['admin_id'] . ", " . $timestamp . ")";
			if ($mysqli->query($sql) === TRUE) {
				echo $editingMsg;
			}
		} else {
			// Jos varaus on voimassa, niin ilmoitetaan ja pysäytetään scripti
			$waitingTime = 300 - $age;
			die("Käyttäjän <b>" . $user->name[0] . "</b> tietojen editointi on varattu vielä " . $waitingTime . " sekuntia.");
		}
	}
}

?>

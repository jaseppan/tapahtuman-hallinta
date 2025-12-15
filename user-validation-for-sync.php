<?php

/********************** Tarkistetaan on nimi/sähköposti osoite jo tallennettu **********************/

// Muodostetaan kysely henkilöistä

$sql = "SELECT name, email, id FROM `so_users` WHERE `name` = '" . $names[0] . "' AND `email` = '" . $emails[0] . "' ";
for($x = 1; $x < count($names); $x++) {
	$sql .= "OR `name` = '" . $names[$x] . "' AND `email` = '" . $emails[$x] . "' ";
}

// Suoritetaan kysely

$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$saved_ids[] = $row["id"];
		$saved_names[] = $row["name"];
		$saved_emails[] = $row["email"];		
	}
}

// Erotellaan päivitettävät ja lisättävät henkilötiedot

if($saved_names) {
	if($preventReport!='true' && $_GET['sync-method']=='complete') {
		echo "<p><b>Seuraavien henkilöiden tiedot päivitetään:</b></p><p>";
	}
	for($x = 0; $x < count($saved_names); $x++) {
		$tmpFingerPrint = $saved_names[$x] . "," . $saved_emails[$x];
		$z = array_search($tmpFingerPrint,$fingerPrint);
		if($z || $z == 0) {
			if($preventReport!='true'   && $_GET['sync-method']=='complete') {
				echo $saved_names[$x] . " (" . $emails[$z] . ")<br>";
			}
			unset($names[$z]);
			unset($emails[$z]);
			unset($fingerPrint[$z]);			
			$names = array_values($names);
			$emails = array_values($emails);
			$fingerPrint = array_values($fingerPrint);	
			unset($z);	
		}
	}
	if($preventReport!='true'   && $_GET['sync-method']=='complete') {
		echo "</p>";
	}
}
if ($names) {
	echo "<p><b>Seuraavat henkilöt lisätään tietokantaan:</b></p>";
	for($x = 0; $x < count($names); $x++) {
 		echo $names[$x] . " (" . $emails[$x] . ")<br>"; 
	}
} else {
	echo "<p><b>Ei lisättäviä henkilöitä</b></p>";
	die();
}

?>

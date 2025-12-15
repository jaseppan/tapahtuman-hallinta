<?php

return;
// Tuodaan esiintyjään linkitettyjen tapahtumien indeksit
$ind = array_search(23, $profile->field_id);
if($ind) {
	$band = $profile->value[$ind];
} else {
	$band = "";
}

if(strpos($band,"|")) {
	$bands = explode('|',$band);
	$sql = "SELECT * FROM `so_js_event_manager_band_event_link` WHERE `band_name` = '" . $bands[0] . "'";
	for ($i = 1; $i < count($bands); $i++) {
		$sql .= "OR `band_name` = '" . $bands[$i] . "'";
	}
} else {
	$sql = "SELECT * FROM `so_js_event_manager_band_event_link` WHERE `band_name` = '" . $band . "'";
}


$result = $mysqli->query($sql);	
$event_id = array();

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$event_id[] = $row["event_id"];
	}
} 

// Tuodaan saatujen tapahtumien nimi, ajankohta ja paikka 

if( count($event_id) == 0) 
	echo "<p>Konserttitietoja ei määritelty esiintyjälle " . $band . ". Täytä tiedot käsin tai luo tapahtuma-esiintyjä -linkki</p>";

$sql = "SELECT `summary`, `dtstart`, `location` FROM `so_jevents_vevdetail` WHERE `evdet_id` = " . $event_id[0];

if(count($event_id) > 0) {
  for($x = 1; $x < count($event_id); $x++) {
    $sql .= " OR `evdet_id` = " . $event_id[$x];
  }
}

$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$event_name[] = $row["summary"];
		$event_time[] = $row["dtstart"];
		$event_location[] = $row["location"];
	}
} else {
	echo "Konserttitietoja ei määritelty esiintyjälle " . $band . ". Täytä tiedot käsin tai luo tapahtuma-esiintyjä -linkki";
}

// Tiedot tekstiksi


$eventText = "<p>Konsertti: " . $event_name[0] . "<br>Aika: " . strftime("%-d.%-m.%Y", $event_time[0]) . "<br>Paikka: " .  $event_location[0] . "<p>";
for($y = 1; $y < count($event_id); $y++) {
	$eventText .= "<p>Konsertti: " . $event_name[$y] . "<br>Aika: " . strftime("%-d.%-m.%Y", $event_time[$y]) . "<br>Paikka: " .  $event_location[$y] . "<p>";
}

mysqli_close($mysqli);
?>

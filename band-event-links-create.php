<?php
checkPermission(2);
include ("get-event-band-links.php");

function array_search_all( $array, $name ){
    foreach( $array as $item ){
        if ( is_array( $item ) && isset( $item['name'] )){
            if ( $item['name'] == $name ){
                return $item;
            }
        }
    }
    return FALSE;
}
// Tuodaan järjestelmässä olevat yhtyeet arrayhyn $all_band

$sql = "SELECT * FROM `so_ajaxregister_fields` WHERE `id` = 23";

$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$bands_values[] = $row["value"];
	}
} else {
	echo "Ei tuloksia KENTTÄ";
	die();
}

foreach($bands_values as $value) {
	$tmp[] = json_decode($value,true);	
}

for($x = 0; $x < count($tmp[0]); $x++) {
	$all_bands[] = $tmp[0][$x]['value']; 
}


// Yhtyeet joille on valittu tapahtumia
$choosen_bands = array_keys($_POST);
	
// Käydään läpi kaikki yhtyeet
foreach($all_bands as $band) {
	/******** Jos ko. yhtye on jo tallennetuissa **********/
	if(in_array($band,$linked_band_name)) {
		$current_band_link_ids = array_keys($linked_band_name, $band);
		
		// Yhtyeeseen kytketyt tapahtumat muuttujaan $band_event_link	
		foreach($current_band_link_ids as $current_band_link_id) {
			$band_event_link[] = $linked_event_id[$current_band_link_id];
		}

		// Käydään läpi valinnat
		for($x = 0; $x < count($_POST[$band]); $x++) {	
			// Jos ko. yhtye - tapahtuma -linkkiä ei ole, niin lisätään
			if(!in_array($_POST[$band][$x],$band_event_link)) {
				$sql_update .= "INSERT INTO `so_js_event_manager_band_event_link` (`event_id`, `band_name`) VALUES (" . $_POST[$band][$x] . ", '" . $band . "'); ";
			} 	
		}
		
		// Käydään läpi tallennetut linkit ja poistetaan ellei linkkiä ole valinnoissa
		for($x = 0; $x < count($band_event_link); $x++) {	
			if(!in_array($band_event_link[$x],$_POST[$band])) {
				$sql_update .= "DELETE FROM `so_js_event_manager_band_event_link` WHERE `event_id` = " . $band_event_link[$x] . " AND `band_name` = '" . $band . "'; ";
			} 	
		}				
		unset($current_band_link_ids);
		unset($band_event_link);
	/*******, mutta jos ko. yhtyettä ei ole tallennetuissa ******/
	} else {
		// jos, ko. yhtye on valituissa
		if(in_array($band,$choosen_bands)) {
			// Lisätään yhtye-esiintyjä -linkki
			for($x = 0; $x < count($_POST[$band]); $x++) {
				$sql_update .= "INSERT INTO `so_js_event_manager_band_event_link` (`event_id`, `band_name`) VALUES (" . $_POST[$band][$x] . ", '" . $band . "'); ";
			}
		}	
	}
}
   
if ($mysqli->multi_query($sql_update) === TRUE) {
    echo "New records created successfully";
} else {
    echo "Error";
}

echo "<head><meta http-equiv='refresh' content='0; url=index.php?page=band-events-links'></head>";

?>

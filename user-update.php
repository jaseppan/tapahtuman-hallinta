<?php
/* Tämä tiedosto päivittää user-edit.php -lomakkeella annetut käyttäjä ja profiilitiedot */

checkPermission(2);
include('get-fields.php');


// Kaikki saadut arvot merkkijonoiksi.
$keys = array_merge(array_keys(array_slice($_POST,0,2)), $fields->id);
//$keys = array_keys($_POST);

for($x = 0; $x < count($keys); $x++) {
	$value = isset($_POST[$keys[$x]]) ? $_POST[$keys[$x]] : '';
	// echo $x . ". " . $_POST[$keys[$x]] . ": " . $value . "<br>";
	if(is_array($value)) {
		$values[$keys[$x]] = mysqli_real_escape_string($mysqli,implode("|",$value)); 					
	} else {
		$values[$keys[$x]] = mysqli_real_escape_string($mysqli,$value);
	}
}

// Käydään läpi kaikki saadut arvot. Jos arvon key on numeraalinen, niin ohjataan profiilin tietoihin _ajaxregister_field_values, muuten käyttäjän tietoihin (_users)

$profile_delete_keys = [];

for($x = 0; $x < count($keys); $x++) {
	// Ei numeerinen -> user	
	if (!is_numeric($keys[$x])) {
		$user_keys[] = $keys[$x];
		$user_values[] = $values[$keys[$x]];
	} else {
		// Profiili-tieto
		// Tarkistetaan onko tieto jo tietokannassa olemassa jossain muodossa 
		// ja muodostetaan erilliset kyselyt päivittämistä ja lisäämistä varten

		$sql = mysqli_query($mysqli, "SELECT * FROM so_ajaxregister_field_values WHERE field_id=" . $keys[$x] . " AND user_id =" . $_GET['id']);
		
		
		if(mysqli_num_rows($sql) > 0){
			if(!$values[$keys[$x]]) {
				// "TIETO ON TALLENNETTU, MUTTA SITÄ EI OLE SYÖTTEESSÄ -> se poistetaan;
				$profile_delete_keys[] = $keys[$x];
				$profile_delete_values[] = $values[$keys[$x]];
			} else {
				// TIETO ON TALLENNETTU JA SE ON SYÖTTEESSÄ -> se päivitetään;
				$profile_update_keys[] = $keys[$x];
				$profile_update_values[] = $values[$keys[$x]];
			}
		}else{
			/// TIETO PUUTTUU -> se lisätään
			$profile_insert_keys[] = $keys[$x];
			$profile_insert_values[] = $values[$keys[$x]];
		}
	}
}

/****************************** Kyselyjen muodostaminen ********************************************/

// Kysely: käyttäjätietojen päivitys
$sql = "UPDATE  `so_users` SET " . $user_keys[0] . "= '" . $user_values[0] . "'";
if(count($user_keys)>1){
	for($x = 1; $x < count($user_keys); $x++) {
		$sql .= ", " . $user_keys[$x] . "= '" . $user_values[$x] . "'";
	}
}
$sql .= " WHERE `id` = " . $_GET['id'] .";";


// Kysely: profiilitietojen päivitys
for($x = 0; $x < count($profile_update_keys); $x++) {
	$sql .= "UPDATE  `so_ajaxregister_field_values` 
	SET value = '" . $profile_update_values[$x] . "' 
	WHERE field_id = " . $profile_update_keys[$x] . " AND user_id = " . $_GET['id'] .";";
}


// Kysely: profiilitietojen lisäys
for($x = 0; $x < count($profile_insert_keys); $x++) {
	if($profile_insert_values[$x]) {
		$sql .= "INSERT INTO  `so_ajaxregister_field_values` (user_id, field_id, value) 
		VALUES (" . $_GET['id'] . ", " . $profile_insert_keys[$x] . ", " . "'" . $profile_insert_values[$x] . "');";
	}
}

// Kysely: profiilitietojen poisto
for($x = 0; $x < count($profile_delete_keys); $x++) {
	$sql .= "DELETE FROM `so_ajaxregister_field_values` 
	WHERE field_id = " . $profile_delete_keys[$x] . " AND user_id = " . $_GET['id'] .";";
}

// Poistetaan henkilö editoinnin seurannasta 
$sql .= "DELETE FROM `so_js_event_manager_editing_tracking` WHERE user_id = " . $_GET['id'] . ";";

$cumulative_rows = 0;
// Suoritetaan kyselyt
if(mysqli_multi_query($mysqli,$sql)){
    do{
        $cumulative_rows += mysqli_affected_rows($mysqli);
    } while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
}
if($error_mess=mysqli_error($mysqli)){echo "Virhe tietokantaan tallentamisessa";}
echo "Cumulative Affected Rows: $cumulative_rows";

/********** Tulostukset debugausta varten **************

echo $mysqli->affected_rows;
echo sql;
var_dump($values);
var_dump($_POST);
/**/


?>
<head><meta http-equiv="refresh" content="0; url=index.php?page=user-view&id= <?php echo $_GET['id']; ?> " />





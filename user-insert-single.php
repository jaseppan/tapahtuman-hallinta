<?php

checkPermission(2);
include('user-insert.php');
echo "<a href='index.php?page=user-add-single'>Lisää uusi henkilö</a>";
// Kaikki saadut arvot merkkijonoiksi.
//$keys = array_keys(array_slice( $_POST,2 ));
$keys = array_keys($_POST);

for($x = 0; $x < count($_POST); $x++) {
	if( isset($keys[$x]) && isset($_POST[$keys[$x]]) ) {
		$value = $_POST[$keys[$x]];
		if(is_array($value)) {
			$values[$keys[$x]] = mysqli_real_escape_string($mysqli,implode("|",$value)); 					
		} else {
			$values[$keys[$x]] = mysqli_real_escape_string($mysqli,$value);
		}
	}
}

// echo '<pre>';
// var_dump($_POST);
// echo '</pre>';
// echo '<pre>';
// var_dump($keys);
// echo '</pre>';
// echo '<pre>';
// var_dump($values);
// echo '</pre>';
// exit();

// Käydään läpi kaikki saadut arvot. Jos arvon key on numeraalinen, niin ohjataan profiilin tietoihin _ajaxregister_field_values, muuten käyttäjän tietoihin (_users)

for($x = 0; $x < count($_POST); $x++) {
	// Ei numeerinen -> user	
	if (isset($keys[$x]) && is_numeric($keys[$x]) && isset($values[$keys[$x]])) {
		$profile_insert_keys[] = $keys[$x];
		$profile_insert_values[] = $values[$keys[$x]];
	}
}

$sql = "";
// Kysely: profiilitietojen lisäys
for($x = 0; $x < count($profile_insert_keys); $x++) {
	if($profile_insert_values[$x]) {
		$sql .= "INSERT INTO  `so_ajaxregister_field_values` (user_id, field_id, value) 
		VALUES (" . $last_id . ", " . $profile_insert_keys[$x] . ", " . "'" . $profile_insert_values[$x] . "');";
	}
}

$cumulative_rows = 0;
// Suoritetaan kyselyt
if(mysqli_multi_query($mysqli,$sql)){
    do{
        $cumulative_rows += mysqli_affected_rows($mysqli);
    } while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
}
if($error_mess=mysqli_error($mysqli)){echo "Virhe käyttäjän profiilitietojen tallentamisessa";}

$_GET['id'] = $last_id;
include('user-view.php');

?>

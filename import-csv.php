<?php  
// Haetaan tarjolla olevat kentät
include('get-fields.php');


$sql = "select MAX(id) from so_users";
$result = $mysqli->query($sql);
$data = mysqli_fetch_array($result);

foreach($fields->label as $value) {
	$fieldLabel[] = filterText($value);	
}

function showHelp() {
	global $fieldLabel;
	echo '<div id="flip-1">OHJEET (TUTUSTU!)</div>';
	echo '<div id="panel-1">';
	echo "<p>Voit tallentaa Excel taulukon csv-tiedostona, jonka voit tuoda Tapahtuman hallinnan tietokantaan tällä työkalulla.</p>";
	echo "<p>Jotta tietojen tuominen onnistuisi, taulukon kaksi ensimmäistä saraketta on oltava seuraavat:</p><p><i>Nimi<br>Sähköposti</i></p>";
	echo "<p>Jotta tallennettavat tiedot ovat yhteneväisiä, tallennettavat nimet pitää olla muodossa <i>Etunimi Sukunimi</i></p>";
	echo "<p>Sarakkeet on otsikoitava alla luetelluilla otsikoilla, mutta kaikkia otsikoita ei välttämättä tarvitse olla. Voit esim. tuoda taulukon otsikoilla: Nimi, Sähköposti, Asema, Tehtävä, Puhelin.</p><p>Mahdolliset otsikot siis ovat:</p>";
	echo "<p><i>";
	foreach($fieldLabel as $value) {
		echo $value . ", ";
	}
	echo "</i></p>";
	echo "<p>Sarakkeiden arvojen, jotka syötetään henkilön lisäämisessä ja tietojen muokkauksessa pudotusvalikolla tai muulla valikolla on vastattava valittavana olevia luokkia. Esim. päivämäärien tulee olla muodossa <i>VVVV-KK-PP</i>. Työkalu ei (toistaiseksi) tarkista onko syötettävät tiedot oikeassa muodossa, joten ole tarkkana. Sen sijaan työkalu tarkistaa onko taulukossa olevat henkilöt jo tietokantaan tallennettuna ja tiedot tuodaan vain henkilöistä, joita ei ole vielä tietokannassa</p>";
	echo "<hr>";
	echo "</div>";
	echo "</div>";
}

if (isset($_FILES['csv']['size']) && $_FILES['csv']['size'] > 0) { 

    //get the csv file 
    $file = $_FILES['csv']['tmp_name']; 
    $handle = fopen($file,"r"); 
	
	// CSV-tiedosto arrayhyn
	do { 
        if ($data[0]) { 
            $completeCsv[] = ($data);
        } 
    } while ($data = fgetcsv($handle,1000,",","'")); 
    // 
	
	// Tarkistetaan data
	
	if($completeCsv[0][0]=='Nimi') {
		$colId[0] = 'name';		
	} else {
		$error .= "Ensimmäisessä sarakkeessa on oltava nimet muodossa Etunimi Sukunimi otsikolla \"Nimi\"<br>";
	}			

	if($completeCsv[0][1]=='Sähköposti') {
		$colId[1] = 'email';		
	} else {
		$error .= "Ensimmäisessä sarakkeessa on oltava sähköpostiosoite otsikolla \"Sähköposti\"<br>";			
	}

	for($x = 2; $x < count($completeCsv[0]); $x++) {
		$tmp = array_search($completeCsv[0][$x], $fieldLabel);
		if($tmp) {
			$colId[$x] = $fields->id[$tmp];		
		} else {
			$error .= "Otsikko " . $completeCsv[0][$x] . " on virheellisessä muodossa.<br>";	
		}
	}


	// Käydään läpi jokainen rivi ja muodostetaan user- ja profile-objektit

	for($x = 1; $x < count($completeCsv); $x++) {	
		$tmpUserId = $x + 10000000;
		for($y = 0; $y < count($completeCsv[$x]); $y++) {
			$userData[$colId[$y]] = $completeCsv[$x][$y];
			if($y < 2) {
				$user->id[] = $tmpUserId;
				if ($y == 0) {
					$user->name[] = $completeCsv[$x][$y];
				} elseif ($y == 1) {
					$user->email[] = $completeCsv[$x][$y];
				}
			} else {
				$profile->user_id[] = $tmpUserId;
				$profile->field_id[] = $colId[$y];
				$profile->value[] = $completeCsv[$x][$y];
			}
			
		}
		
	}

	include('user-insert.php');

	if($names) {
		echo "<p><b>Lisätään lisättyjen kurssilaisten yhteys-, majoittumis-, ja ruokailutiedot.</b><p>";
		unset($saved_ids);
		unset($saved_names);
		unset($saved_emails);

		// Haetaan juuri tallennetut henkilöt
		$sql = "SELECT name, email, id FROM `so_users` WHERE `name` = '" . $names[0] . "' AND `email` = '" . $emails[0] . "' ";
		for($x = 1; $x < count($names); $x++) {
			$sql .= "OR `name` = '" . $names[$x] . "' AND `email` = '" . $emails[$x] . "' ";
		}
		$result = $mysqli->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$saved_ids[] = $row["id"];
				$saved_names[] = $row["name"];
				$saved_emails[] = $row["email"];
			}		
		}
	}

	

	// Haetaan käyttäjät user-objektista ja määritellään id:t tietokantaan tallennettujen mukaisiksi
	for($x = 0; $x < count($saved_names); $x++) {
		$listNameTmp = array_search_all($saved_names[$x],$user->name);
		$listEmailTmp = array_search_all($saved_emails[$x],$user->email);
		// Määritellään id, jos sekä nimi, että sähköpostiosoite ovat uniikkeja tai ensimmäinen löydetty nimi ja sähköpostiosoite samoja
		if($listNameTmp[0] == $listEmailTmp[0]) {
			$choosen[] = $listNameTmp[0];	
			$tmp = array_search_all($user->id[$choosen[$x]],$profile->user_id);
			$profileValueIds[] = $tmp;
			for($y = 0; $y < count($tmp); $y++) {
				$profile->user_id[$profileValueIds[$x][$y]] = $saved_ids[$x];
			}
			$user->id[$choosen[$x]] = $saved_ids[$x];
		// Muuten ristiintaulukoidaan nimet ja sähköposti osoitteet ja etsitään yhteiset		
		} else {		
			for($y = 0; $y < count($listNameTmp); $y++) {
				for($z = 0; $z < count($listEmailTmp); $z++) {
					// Määritellää id, kun löytyy vastaavat arvot
					if($listNameTmp[$y] == $listEmailTmp[$z]) {
						$choosen[] = $listNameTmp[0];	
						$tmp = array_search_all($user->id[$choosen[$x]],$profile->user_id);
						$profileValueIds[] = $tmp;
						for($y = 0; $y < count($tmp); $y++) {
							$profile->user_id[$profileValueIds[$x][$y]] = $saved_ids[$x];
						}
						$user->id[$choosen[$x]] = $saved_ids[$x];
					} 	
				}
			}
		}
	}
	
	/**************************** Lisätään valitut profiilitiedot tietokantaan ********************************/

	for($x = 0; $x < count($choosen); $x++) {
		for($y = 0; $y < count($profileValueIds[$x]); $y++) {
			 if($profile->value[$profileValueIds[$x][$y]]) {
				$value = $mysqli->real_escape_string($profile->value[$profileValueIds[$x][$y]]);
			 	$sql = "INSERT INTO  `so_ajaxregister_field_values` (user_id, field_id, value) 
				VALUES (" . $profile->user_id[$profileValueIds[$x][$y]] . ", " . $profile->field_id[$profileValueIds[$x][$y]] . ", " . "'" . $value . "');";
				echo $sql . "<br>";
				// Suoritetaan kyselyt
				if (mysqli_query($mysqli, $sql)) {
				} else {
						$errorMsgProfileInsert .= "Arvon <b>" . $value . "</b> tallentaminen henkilölle " . $saved_names[$x] . " epäonnistui (ID: " . $profile->field_id[$profileValueIds[$x][$y]] . ")<br>";
				}
			}
		}
	}



	



    //redirect 
    header('Location: import.php?success=1'); die; 

} 

?>
<div style="width: 600px; margin-left: auto; margin-right: auto;">
<form action="" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
  <p>Valitse tiedosto: </p> 
  <input name="csv" type="file" id="csv" /><br>
  <input type="submit" name="Submit" value="Submit" /> 
</form> 

<?php 
if (isset($_GET['success']) && $_GET['success']) { 
	echo "<b>Your file has been imported.</b><br><br>"; 
} elseif(isset($error) && $error) {
	showHelp();
	echo $error;
} else {
	showHelp();
}
?>
</div>



<?php checkPermission(2); ?>
<div class="narrow">
<h1>Kurssilaisten tietojen synkronointi</h1>

<?php

// Jos synkronointi menetelmää ($_GET['sync-method']) ei ole määrilty
if(!$_GET['sync-method']) {?>
<p >Tämä ohjelma synkronoi tiedot kurssi-ilmoittautumisista henkilötietokantaan, jolloin kurssilaisten yhteys-, majoittumis- ja ruokailutiedot ovat käytettävissä tulostuksissa ja listoissa. Aiemmin tuotujen henkilöiden tiedot ja niihin tehdyt muutokset säilyvät.</p>
<p><a href="index.php?page=student-data-syncronizer&sync-method=insert-only" class="mylinkclass withanimation" onclick="waitingMsg()">Lisää puuttuvat henkilöt</a></p>
<p>Tapahtuman jälkeen voit poistaa ilmoittautumistiedot henkilötietokannasta. Huom.: Vaatii erillisen tunnistautumisen</p>
<p><a href="index.php?page=student-data-syncronizer&sync-method=delete">Poista kurssilaisten profiilitiedot</a></p>
</div>	
<?php die(); }
echo '<div id="loading"><div id="loading_anim"></div></div>';

// SUORA PÄÄSY POISTOON KEHITYSTÄ VARTEN
$_POST['user']="superadmin";
$_POST['pw']="superadmin";

// Jos synkronointi menetelmä on delete
if($_GET['sync-method']=='delete') {
	if($_POST['user'] && $_POST['pw']) {
		if($_POST['user']=="superadmin" && $_POST['pw']=="superadmin" ) {

			$sql = "SELECT user_id FROM `so_js_event_manager_course_users`";
		
			$result = $mysqli->query($sql);	

			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$userId[] = $row["user_id"];
				}
			} else {
				echo "Ei kurssilaisia dokumentoiduissa kurssikäyttäjissä";
			}

			$sql = "SELECT user_id FROM `so_ajaxregister_field_values` WHERE value ='kurssilainen'";
		
			$result = $mysqli->query($sql);	

			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$userId[] = $row["user_id"];
				}
			} else {
				echo "Ei kurssilaisia dokumentoiduissa dokumentoiduista ajax_register -tietokannassa";
				die();
			}
			
			// Poistetaan profiilitiedoista
			$sql = "";
			for($x = 0; $x < count($userId); $x++) {
				$sql .= "DELETE FROM `so_ajaxregister_field_values` WHERE `user_id` = " . $userId[$x] .  ";";
			}

			// Poistetaan käyttäjistä
			for($x = 0; $x < count($userId); $x++) {
				$sql .= "DELETE FROM `so_users` WHERE `id` = " . $userId[$x] .  ";";
			}

			// Tyhjennetääntään lista kurssilaisista
			$sql .= "TRUNCATE TABLE `so_js_event_manager_course_users`";

			$cumulative_rows = 0;

			// Suoritetaan kyselyt
			if(mysqli_multi_query($mysqli,$sql)){
				do{
					$cumulative_rows += mysqli_affected_rows($mysqli);
				} while(mysqli_more_results($mysqli) && mysqli_next_result($mysqli));
			}
			if($error_mess=mysqli_error($mysqli)){echo "Error: $error_mess";}
			echo "Cumulative Affected Rows: $cumulative_rows";
			die();
		} else {
			echo "<p>Väärä käyttäjänimi tai salasana. Yritä uudestaan</p>";	
			echo '<form action="" method="POST">';
			echo '<p><label>Käyttäjä: </label><input type="text" name="user"/><p>';
			echo '<p><label>Salasana: </label><input type="text" name="pw"/><br><p>';
			echo '<p><input type="submit" name="submit" value="varmista"></p>';
			echo '</form>';
			die();	
		}	
	} else {
		echo '<form action="" method="POST">';
		echo '<p><label>Käyttäjä: </label><input type="text" name="user"/><p>';
		echo '<p><label>Salasana: </label><input type="text" name="pw"/><br><p>';
		echo '<p><input type="submit" name="submit" value="varmista" onclick="waitingMsg()"></p>';
		echo '</form>';
		die();
	}
}

/**************************** Comlete and Insert Methods ***************************************/

$preventReport = $_GET['prevent-report'];

include('get-student-data.php'); // Hakee opiskelijoiden tiedot user- ja profile-objekteihin
include('user-insert.php'); // Lisää käyttäjät. Mikäli käyttäjiä on tallennettuna, niin lisätään vain puuttuvat

/* Yritetään käyttäjien lisäämistä jolloin saadaan kaikkien tallennettujen kurssilaisten:
 * - nimet: $saved_names 
 * - sähköpostiosoitteet: $saved_emails
 * - id:t $saved_ids
*/

// Insert only profiles of missing users

if($_GET['sync-method']=='insert-only' && $names) {
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

// Jos insert-only tai complete
if($_GET['sync-method']=='insert-only' && $names || $_GET['sync-method']=='insert-only' && $names) {
	for($x = 0; $x < count($choosen); $x++) {
		for($y = 0; $y < count($profileValueIds[$x]); $y++) {
			 if($profile->value[$profileValueIds[$x][$y]]) {
				$value = $mysqli->real_escape_string($profile->value[$profileValueIds[$x][$y]]);
			 	$sql = "INSERT INTO  `so_ajaxregister_field_values` (user_id, field_id, value) 
				VALUES (" . $profile->user_id[$profileValueIds[$x][$y]] . ", " . $profile->field_id[$profileValueIds[$x][$y]] . ", " . "'" . $value . "');";
				// Suoritetaan kyselyt
				if (mysqli_query($mysqli, $sql)) {
				} else {
						$errorMsgProfileInsert .= "Arvon <b>" . $value . "</b> tallentaminen henkilölle " . $saved_names[$x] . " epäonnistui (ID: " . $profile->field_id[$profileValueIds[$x][$y]] . ")<br>";
				}
			}
		}
	}
}

/******** Lisätään tallennetut user_id:t kurssilaislistaan, josta ne haetaan poistettaessa ************************/

$saved_ids = array_unique($saved_ids);

// Jos insert-only tai complete
if($_GET['sync-method']=='insert-only' && $names || $_GET['sync-method']=='insert-only' && $names) {
	for($x = 0; $x < count($saved_ids); $x++) {
	 	$sql = "INSERT INTO  `so_js_event_manager_course_users` (user_id) VALUE (" . $saved_ids[$x] . ");";
		// Suoritetaan kyselyt
		if (mysqli_query($mysqli, $sql)) {
		} else {
				$errorMsgProfileInsert2CourseUsers .= "Henkilön <b>" . $saved_names[$x] . "</b> listaaminen kurssi-ilmoittautumisista tuotuihin käyttäjiin epäonnistui<br>";
		}
	}
}

if($preventReport!='true') {
	if($errorMsgProfileInsert) {
		echo "<p>" . $errorMsgProfileInsert . "</p>";
	} 
    if($errorMsgProfileInsert2CourseUsers) {
		echo "<p>" . $errorMsgProfileInsert2CourseUsers . "<p>";
		echo "<p style'color:red'>HUOM! Poistettaessa kurssilaiset käyttäjistä tai täydellistä synkronointia tehtäessä henkilöt, joita ei ole listattu kurssi-ilmoittautumisista tuotuihin käyttäjiin epäonnistui.";
	}
	if(!$errorMsgProfileInsert2CourseUsers || !$errorMsgProfileInsert) {
		echo "Kurssilaisten tiedot synkronoitu onnistuneesti";
	}
}

?>
</div>

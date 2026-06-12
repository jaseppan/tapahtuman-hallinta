<?php

/********************** Tarkistetaan on nimi/sähköposti osoite jo tallennettu **********************/

$errorCounter = 0;
$noteMsg = "";
$errorMsg = "";

// Muodostetaan kysely nimistä

$sql = "SELECT * FROM `so_users` WHERE `name` = '" . $names[0] . "' ";
for($x = 1; $x < count($names); $x++) {
	$sql .= "OR `name` = '" . $names[$x] . "' ";
}

// Suoritetaan kysely nimistä

$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$saved_names[] = $row["name"];		
	}
}

// Muodostetaan kysely sähköpostiosoitteista

foreach($emails as $value) {
	if($value != "-") {
		$realEmails[] = $value;	
	}
}

$sql = "SELECT * FROM `so_users` WHERE `email` = '" . $realEmails[0] . "' ";
for($x = 1; $x < count($emails); $x++) {
	$sql .= "OR `email` = '" . $realEmails[$x] . "' ";
}

// Suoritetaan kysely sähköpostiosoitteista
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$saved_emails[] = $row["email"];		
	}
}

// Sallitaan sähköpostiosoitteiden tuplaantuminen, mutta ei nimien tuplaantumista. Sähköpostiosoitteet saattavat olla samoja eri henkilöillä, mutta nimi ei saisi tuplaantua.
/* if($value != "-") {
	if(isset($saved_emails) && $saved_emails) {
		if(count($saved_emails) > 1) {
			$errorMsg .= "Sähköpostiosoitteet " . $saved_emails[0];
			if(count($saved_emails) > 2) {
				for($x = 1; $x < count($saved_emails)-1; $x++) {
					$errorMsg .= ", " . $saved_emails[$x];	
				}
			}
			$errorMsg .= " ja " . end($saved_emails);
			$errorMsg .= " ovat jo tietokannassa<br />";
			++$errorCounter;
		} else {
			$errorMsg = "Sähköpostiosoite " . $saved_emails[0];
			$errorMsg .= " on jo tietokannassa<br />";
			++$errorCounter;
		}	
	}
} */

// Huomautuksen ohitus jos validoitu aikaisemmin
if( !isset($_GET["status"]) || $_GET["status"] != "validated") {
	if(isset($saved_names) && $saved_names) {
		if(count($saved_names) > 1) {
			$noteMsg .= "Nimet " . $saved_names[0];
			if(count($saved_names) > 2) {
				for($x = 1; $x < count($saved_names)-1; $x++) {
					$errorMsg .= ", " . $saved_names[$x];	
				}
			}
			$noteMsg .= " ja " . end($saved_names);
			$noteMsg .= " ovat jo tietokannassa. Ethän tallenna samaa henkilö kahta kertaa!<br />";
		} else {
			$noteMsg .= "Nimi " . $saved_names[0];
			$noteMsg .= " on jo tietokannassa. Ethän tallenna samaa henkilö kahta kertaa!<br />";
		}	
	}
}

/***************************** Syötteen oikeellisuuden validointi ************************************/

// Nimi puuttuu
if(!$names[0]) {
	$errorMsg .= "Nimi puuttuu<br />";
	++$errorCounter;
}

// Tarkiste ovatko listat saman pituisia
if(count($names) > 1 || count($emails) > 1) {
	if(count($names) != count($emails)) {
		$errorMsg .= "Nimi- ja sähköpostilistat ovat eripituisia.<br />";
		++$errorCounter;
	}
}

// Validoidaan sähköpostiosoitteet
foreach($emails as $email) {
	if($email != '-') {
		if(!$email) {
			$errorMsg .= "Sähköpostiosoite puuttuu. Ellei sähköpostiosoitetta ole tai se ei ole tiedossa, niin merkitse se viivalla<br />";
			++$errorCounter;
		} else {
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		    	$errorMsg .= "Sähköpostiosoite ($email) on virheellisessä muodossa.<br />";
			++$errorCounter;	    
			}
		}	
	}
}

// Huomautus
if($noteMsg) {
	echo "<h3>Huomautus</h3>" . $noteMsg;
}

// Ilmoitetaan virheistä, jos niitä on
if( !empty($errorMsg) ) {
	if($errorCounter > 1) {
		echo "<h3>Virheitä lomakkeessa</h3>";
	} else {
		echo "<h3>Virhe lomakkeessa</h3>";
	}
	echo "<p>" . $errorMsg . "<p>";
	if($errorCounter > 1) {
		echo "<a style=\"text-decoration: underline; \" onclick=\"goBack()\">Palaa takaisin ja korjaa virheet</a></p>";
	} else {
		echo "<a style=\"text-decoration: underline; \" onclick=\"goBack()\">Palaa takaisin ja korjaa virhe</a></p>";
	}
	die();	
}

if($noteMsg) {?>
	<form action="index.php?page=user-insert&status=validated" method="POST">
		<input type="hidden" name="name" value="<?php echo $_POST['name']?>"/>
		<input type="hidden" name="email" value="<?php echo $_POST['email']?>"/>
		<input type="submit" name="submit" value="Tallennan joka tapauksessa">
	</form>

	<?php die("<a onclick=\"goBack()\">< Palaan takaisin ja muutan tietoja</a></p>");
}
?>

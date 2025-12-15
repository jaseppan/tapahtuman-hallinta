<?php
checkPermission(2);

include('function-random.php');

/* Jos nimi (nimet) ja sähköpostiosoitte(et) on saatu lomakkeelta 
 * Käytetään lisättäessä käyttäjiä user-add.php -lomakkeella
 */

$time_offset = 10800;
$now = date('Y-m-d H:i:s', time() + $time_offset);

if($_POST['name'] && $_POST['email']) {
	$names = explode(",",$_POST['name']);
	$emails = explode(",",$_POST['email']);

	// Trimmataan
	for($x = 0; $x < count($names); $x++) {
		 $names[$x] = mysqli_real_escape_string($mysqli,trim($names[$x]));
	}

	for($x = 0; $x < count($emails); $x++) {
		$emails[$x] = trim($emails[$x]);
	}

	// Validoidaan

	require("user-validation.php");
}


/* Jos nimi (nimet) ja sähköpostiosoitte(et) ovat user-objektista 
 * Käytetään synkronoitaessa kurssilaisten ilmoittautumistietoja käyttäjiin tiedostolla student-data-syncronizer.php
*/

if( isset($user) && $user) {

	// Tarkistetaan ja poistetaan dublikaatit

	for($x = 0; $x < count($user->name); $x++) {
		$fingerPrintRaw[] = $user->name[$x] . "," . $user->email[$x];
	}

	$fingerPrint = array_unique($fingerPrintRaw);

	include_once('function-array-search-all.php');
	
	if(!$postInsert || !$preventReport) {
	// Ilmoitetaan dublikaateista
		for($x = 0; $x < count($fingerPrint); $x++) {
			$applicationCountOfPerson = count(array_search_all($fingerPrint[$x], $fingerPrintRaw));
			if($applicationCountOfPerson > 1) {
				$multipleApplicationsMsg = "true"; 
				$multipleApplicationsList .= "<li>Henkilö <b>" . $user->name[$x] . "</b> sähköpostiosoitteella <b>" . $user->email[$x] . "</b> on ilmoittautunut " . $applicationCountOfPerson . " kertaa.</li>";
			
			}
		}
	}
    
	if($multipleApplicationsMsg=="true") {
		echo "<p><b>Moninkertaiset ilmoittautumiset</b></p>";
		echo "<p>" . $multipleApplicationsList . "</p>";
		echo "<p style='color:red'>Huom. Tarkista, että useammin ilmoittautuneiden tiedot ovat oikein. <br>Mikäli ilmoittautumiset koskevat samoja kursseja, niin poista ylimääräiset ilmoittautumiset.</p>";
		unset ($multipleApplicationsList);
	}


	foreach($fingerPrint as $value) {
		$tmp = explode(",",$value);
		$names[] = $tmp[0];
		$emails[] = $tmp[1];
	}
	
	// Trimmataan
	for($x = 0; $x < count($names); $x++) {
		 $names[$x] = mysqli_real_escape_string($mysqli,trim($names[$x]));
	}

	for($x = 0; $x < count($emails); $x++) {
		$emails[$x] = mysqli_real_escape_string($mysqli,trim($emails[$x]));
	}

	// Validoidaan

	require("user-validation-for-sync.php");
}

/******************** Käyttäjän / Käyttäjien tallentaminen *******************/

require('function-user-name-gen.php');
$userHash = '';

if(isset($names) && $names) {
	// Käyttäjät user-tiedostossa (eli batchina kurssi-ilmoittautumisista)
	if(isset($user) && $user) {
		$sql = "INSERT INTO so_users (name, username, email, password, block, sendEmail, registerDate, lastvisitDate, params, lastResetTime, resetCount, requireReset) VALUES ('" . $names[0] . "', '" . userNameGen($names[0], "-") . "', '" . $emails[0] . "', '" . $userHash . "', 0 , 0, '" . $now . "', '" . $now . "', '{}', '" . $now . "', '0','0')";
		for($x = 1; $x < count($names); $x++) {
			// Salasana ja päiväys
			$psw = bin2hex(openssl_random_pseudo_bytes(20));
			$salt = random(rand(20,40));
			$userHash = md5($psw.$salt);
			$now = date("Y-m-d h:m:s");
			// Kysely
			$sql .= ", ('" . $names[$x] . "', '" . userNameGen($names[$x], "-") . "', '" . $emails[$x] . "', '" . $userHash . "', 0 , 0, '" . $now . "', '0000-00-00 00:00:00', '{}', '0000-00-00 00:00:00', '0','0') ";
		}
	// Jos saatu lomakkeelta
	} else {
		$sql = "INSERT INTO so_users (name, username, email, password, block, sendEmail, registerDate, lastvisitDate, params, lastResetTime, resetCount, requireReset) VALUES ('" . $names[0] . "', '" . userNameGen($names[0], $emails[0]) . "', '" . $emails[0] . "', '" . $userHash . "', 0 , 0, '" . $now . "', '" . $now . "', '{}', '" . $now . "', '0','0')";
		for($x = 1; $x < count($names); $x++) {
			// Salasana ja päiväys
			$psw = bin2hex(openssl_random_pseudo_bytes(20));
			$salt = random(rand(20,40));
			$userHash = md5($psw.$salt);
			$now = date("Y-m-d h:m:s");
			// Kysely
			$sql .= ", ('" . $names[$x] . "', '" . userNameGen($names[$x], $emails[$x]) . "', '" . $emails[$x] . "', '" . $userHash . "', 0 , 0, '" . $now . "', '" . $now . "', '{}', '" . $now . "', '0','0') ";
		}
	}

	if ($mysqli->query($sql) === TRUE) {
		echo "<p>Käyttäjät tallennettu onnistuneesti<p>";
		$last_id = mysqli_insert_id($mysqli);
	} else {
		echo "Virhe käyttäjän tallentamisessa tietokantaan ";
	}
}

?>

<?php
/* TODO
 * - Tuo ruokavalio
 * - Luo yhteenveto aterioista: Ateria, osallistuja määrä, ruokavaliot 
 */

checkPermission(1); 

include("hidden_persons.php");
include('function-date-name-translator.php');

function ticketWriter($name, $meal) {
	global $price;
	global $mealType;
	$divider = strpos($meal, ' ');

	if($divider) {	
		$meal = substr($meal,0,$divider);
	}
	if($meal!='aamiainen') {
		$meal = 'ateria';	
	}
	if($meal == $mealType) {
		
		if($meal=='aamiainen' || $meal=='Aamiainen') {
			if($price) {
				$meal = 'Aamiainen | Breakfast (' . $price . ' €)';
			} else {
				$meal = 'Aamiainen | Breakfast';
			}
		} else {
			if($price) {
				$meal = 'Ateria | Meal (' . $price . ' €)';
			} else {
				$meal = 'Ateria | Meal';
			}
		}	
		$ticket = "<img src=\"text-logo.png\" / alt=\"SOMMELO\"><br><b>RUOKALIPPU</b><br>" . $name . "<br><b>" . $meal . "</b>";
	} else {
		$ticket = "";		
	}
	return $ticket;
}

function cellWriter($value, $bgcolor) {
	
	$cell = '<td align="center" style="border: 1px solid gray;padding: 10px 0 10px 0; line-height: 19.27px;">&nbsp;<br>' . $value . '<br>&nbsp;</td>';
	return $cell;
}

include ("function-get-users-data-by-id.php");
include ("function-get-profile-text-simple.php");
$showGroups = isset($_GET['show-groups']) ? $_GET['show-groups'] : false;


/***************************** Kysytään ateriatyyppi, ryhmän rajaus ja lipun hinta, jos ei määritelty ******************************/

if(!isset($_POST['meal-type']) || !$_POST['meal-type']) {
	if(isset($_POST['position']) && $_POST['position']) {
		echo "Ei valittua ateriatyyppiä.";
	}
	// Haetaan asemat
	$sql = "SELECT `value` FROM `so_ajaxregister_fields` WHERE id = 20";
	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$position_values[] = $row["value"];				
		}
	} else {
		echo "Ei tuloksia KENTTÄ";
		die();
	}
	$position_values = json_decode($position_values[0]);
	
	// Haetaan nimet
	
	
	
	// Tulosta lomake
	echo '<H2>Ruokaliput ryhmälle</H2>';
	echo '<a href="http://www.sommelo.net/tapahtuman-hallinta/index.php?page=meal-ticket-dev&textareasize=large">ruokaliput henkilölle</a><br />';
	echo '<form action="" method="post" enctype="multipart/form-data" >';
	echo '<p><select name="meal-type">';
	echo '<option value="">VALITSE ATERIATYYPPI</option>';
	echo '<option value="aamiainen">Aamiainen</option>';
	echo '<option value="ateria">Ateria</option>';
	echo '</select></p>';
	echo '<p><b>Valitse ryhmä, jolle luodaan ruokaliput</b></p>';
	echo '<p><select name="position">';
	echo '<option value="manual">Käsin täytettävä</option>';	
	echo '<option value="kaikki">Kaikki</option>';
	foreach($position_values as $value){
		if($value->value==$_POST['position']){
			echo '<option value="' . $value->value . '" selected>' . filterText($value->text) . '</option>';
		} else {
			echo '<option value="' . $value->value . '">' . filterText($value->text) . '</option>';
		}
	}
	echo '</select></p>';
	
	
	
	echo '<p><b>Määrittele lippun hinta (Pelkkä numero. Jos ilmaisaterioita, niin jätä tyhjäksi)</b></p>';
	echo '<p><input type="text" name="hinta"></p>';
	echo '<input type="submit" name="submit" value="Seuraava">';
	echo '</form>';
	echo '</p>';
	die();
}

global $mealType;
$mealType = $_POST['meal-type']; 
$price = $_POST['hinta'];
$html = '';

/******************************** Haetaan tiedot **********************************/
if($_POST['position']=='manual'){
	$html = '<table>';
	$html .= '<tr>';	
	for($x = 0; $x < 30; $x++) {
		$ticket = ticketWriter('______________________', $mealType);
		$html .= cellWriter($ticket,'#FFFFFF');
		if(fmod($x,5) == 4) {
			$html .= "</tr><tr>";
		}
	}
} else {
	// Haetaan tarjottavat ateriat
	$sql = "SELECT value FROM so_ajaxregister_fields WHERE id = 34";
    $result = $mysqli->query($sql);

	
    if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$field_values[] = $row["value"];				
		}
	} else {
		echo "Ei tuloksia KENTTÄ";
		die();
	}
	
	include( 'student-data-2-profile-transform-list.php' );
	$field_values = json_decode($allMealValues);
	
	
	// Haetaan yhtyeiden nimet

	$sql = "SELECT `value` FROM `so_ajaxregister_fields` WHERE id = 23";
	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$band_fields[] = $row["value"];				
		}
	} else {
		echo "Ei tuloksia KENTTÄ";
		die();
	}

	$band_fields = json_decode($band_fields[0]);

	// Haetaan henkilöiden ateriatiedot

	$sql = "SELECT `user_id`, `value`, `field_id` FROM `so_ajaxregister_field_values` WHERE `field_id` = 34 OR `field_id` = 33 AND `value` = 'kylla'";
	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if(!in_array($row["user_id"],$hidden_persons_db)){ 
				$user_id[] = $row["user_id"];
				if ($row['field_id'] == 34) { 	
						$diners[$row["user_id"]] = $row["value"];
				} else {
					$yesDiners[] = $row["user_id"];
				}
			}
		}
	} else {
		echo "Ei tuloksia USER ID";
		die();
	}

	// Haetaan henkilöiden asema

	$position = getUsersDataById($user_id, 20);

	// Haetaan henkilöiden tehtävä

	$job = getUsersDataById($user_id, 59);

	// Haetaan henkilöiden yhtye

	$band = getUsersDataById($user_id, 23);

	// Haetaan henkilöiden saapumisajat

	$arrDate = getUsersDataById($user_id, 32);

	// Haetaan henkilöiden lähtöajat

	$depDate = getUsersDataById($user_id, 31);

	// Haetaan henklöiden nimet

	$sql = "SELECT `id`, `name` FROM `so_users` WHERE `id` = " . $user_id[0];
	for($x = 1; $x < count($user_id); $x++) {
		$sql .= " OR `id` = " . $user_id[$x];
	}

	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$name[$row["id"]] = $row["name"];
		}	
	} else {
		echo "Ei tuloksia NAME";
		die();
	}

	/*************************** Muokkaa tiedot *************************/

	//setlocale(LC_TIME, 'fi_FI');

	// Nimet muotoon Sukunimi, Etunimi ja aakkostetaan

	include('function-name-format-changer.php');

	$key = array_keys($name);

	for($x = 0; $x < count($key); $x++) {
		if($name[$key[$x]]) {
			if( isset($position[$key[$x]]) || $_POST['position'] || $position[$key[$x]] == $_POST['position'] || $_POST['position'] == 'kaikki') {
				$name[$key[$x]] = $position[$key[$x]] . ' | ' . $name[$key[$x]];
			}
		} 
	}

	// Pudotetaan joukosta piilotetut henkilöt ja aakkostetaan
	include('hidden_persons.php');

	for($x = 0; $x < count($name); $x++) {
		if(in_array($user_id[$x], $hidden_persons)) {
			unset($name[$user_id[$x]]);
			unset($user_id[$x]);
		}
	}

	asort($name);
	$key = array_keys($name);

	// Puretaan nimet arrayhyn

	for($x = 0; $x < count($key); $x++) {
		$name[$key[$x]] = explode(" | ", $name[$key[$x]]);
	}

	/* Muokataan otsikot. Tuloksena muuttujat:
	 * $date, $colspan, $meal*/

	$colspanCounter = 0;
	for($x = 0; $x < count($field_values); $x++) {
		// 1. Päivämäärät
		if (!preg_match('/(\d+(\.\d+)*)/', $field_values[$x]->text, $tmp )) {
			// Could not find a matching number in the data - handle this appropriately
		} else {
			$dateString = $tmp[1] . "." . date('Y');
			$dateExact[] = date_format(date_create_from_format('j.n.Y', $dateString), 'Y-m-d');		
			$dateView[] = dateNameTranslator(date_format(date_create_from_format('j.n.Y', $dateString), 'D')) . " " . date_format(date_create_from_format('j.n.Y', $dateString), 'j.n.');
			$date[] = $tmp[1];
			if($x > 0 && $date[$x] !== $date[$x-1]) {
				$colspan[] = $colspanCounter;
				$colspanCounter = 1;
			} else {
			
				$colspanCounter++;
			}
		}
		// 2. Ateriat
		$meals[] = substr(trim($field_values[$x]->text), 0, 1);
		$mealValues[] = $field_values[$x]->value;
	}

	$colspan[] = $colspanCounter;
	$date = array_values(array_unique($date));
	$dateView = array_values(array_unique($dateView));

	/* Muokataan ateriatiedot:
	 * Mikäli valittu kyllä, mutta aterioita ei ole määritelty niin, merkitään kaikki ateriat oleskeluajalta
	 */

	for($x = 0; $x < count($yesDiners); $x++) {
		if(is_array($dinerKeys) &&  !in_array($yesDiners[$x], $dinerKeys)) {
			$diners[$yesDiners[$x]] = 'all';
		}	
	}

	// Määritellään Taulukon ominaisuuksia

	// solujen leveys

	$colspanWhole = count($mealValues)+5; // Kokotaulukon leveys
	$cellWidthUnit = 100/$colspanWhole;



	/************************** Muodostetaan taulukko **********************************/

	$html .= '<table style="border: 1px solid gray;">';
	$html .= "<tbody>";

	// Merkitään henkilöiden ateriat taulukkoon
	$cellCounter = 0;
	$html .= "<tr>";
	for($x = 0; $x < count($key); $x++){
		if($position[$key[$x]]==$_POST['position'] || $_POST['position']=='kaikki') {	
		
			// Käydään läpi ateriat
			for($y = 0; $y < count($mealValues); $y++){


				$ticket = false;

				// Merkitään ateria, jos kyseinen ateria löytyy henkilön ilmoittautumistiedoista
				if(strpos($diners[$key[$x]], $mealValues[$y]) !== false) {
					$ticket = ticketWriter(end($name[$key[$x]]), $mealValues[$y]);
					
					if(!empty($ticket)) {
						$html .= cellWriter($ticket,'#FFFFFF');
						$cellCounter++;
					}
				// Jos ateriatietona all
				} elseif(strpos($diners[$key[$x]],'all') || $diners[$key[$x]]=='all') { // LISÄÄ SAAPUMIS- JA POISTUMISPÄIVÄEHTO
					// Ellei tietoa saapumis- ja/tai lähtöpvästä
					if(!isset($arrDate[$key[$x]]) || !$arrDate[$key[$x]] || !isset($depDate[$key[$x]]) || !$depDate[$key[$x]]) {
						$missingData[$key[$x]] = $name[$key[$x]];
					} else {
						// Merkitään ateria, jos henkilö on paikalla kyseisenä pvä:nä			
						if(strtotime($arrDate[$key[$x]]) <= strtotime($dateExact[$y]) && strtotime($depDate[$key[$x]]) >= strtotime($dateExact[$y])) {  		
							//$ticket = ticketWriter(end($name[$key[$x]]), $mealValues[$y]);
							if(!empty($ticket)) {
								//$html .= cellWriter($ticket,'#FFFFFF');
								//$cellCounter++;
							}
					  	}
					}
				} 
				// Vaihdetaan riviä joka viidennen solun jälkeen
				if($ticket && fmod($cellCounter,5) == 0 && $cellCounter > 0) {
					$html .= "</tr><tr>";
				}
			}
		}
	}
	$html .= "</tr>";
	$html .= "</tbody>";
	$html .= "</table>";

	//var_dump($html);
	//exit();

	/*************************** Tulostetaan *********************************/

	// Ilmoitus, jos puutteellisia ruokailutietoja

	$fileName = "ruokailulista-". date('Ymd', time()) . "-klo-" . date('G-i', time()). ".pdf";
	$docTitle = "RUOKAILULISTA";

	if($missingData) {
		echo "<p><b>Seuraavien henkilöiden ruokailutiedot ovat puutteellisia:</b></p><p>";
		foreach ($missingData as $value) {
			if(is_array($value)) {
				echo "<li>" . end($value) . "</li>";
			} else {
				echo "<li>" . $value . "</li>";
			}
		}
		echo "</p>";
		echo "<p style='color: red;'>Tarkista edellämainittujen henkilöiden saapumis-, poistumispäivämäärät tai ateriat mikäli henkilö ruokailee vain yksittäisillä aterioilla.";
	}
}

$fileName = "ruokaliput-". date('Ymd', time()) . "-klo-" . date('G-i', time()). ".pdf";
$docTitle = "RUOKALIPUT " . date('Y', time());

echo "<h2>Ruokaliput ryhmälle \"" . $_POST['position'] . "\"</h2>";

echo "<form action='pdf-templates/tickets/pdf-creator.php' method='post' target='_blank'>";
echo "<textarea name='html'>" . $html . "</textarea>";
echo '<input type="hidden" name="doc-title" value="' . $docTitle . '"/>';
echo '<input type="hidden" name="file-name" value="' . $fileName . '"/>';
echo "<p><input type='submit' name='submit' value='Luo pdf'></p>";
echo "</form>";
?>

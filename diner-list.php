<?php
/* TODO
 * - Tuo ruokavalio
 * - Luo yhteenveto aterioista: Ateria, osallistuja määrä, ruokavaliot 
 */

checkPermission(1); 
include("hidden_persons.php");
include('function-date-name-translator.php');
include('syncronation-msg.php');

function cellWriter($value, $bgcolor) {
	if($bgcolor) {
		$cell = '<td align="center" bgcolor="' . $bgcolor . '" style="border-top: 1px solid gray;">' . $value . '</td>';
	} else {
		$cell = '<td align="center" style="border-top: 1px solid gray;">' . $value . '</td>';
	}
	return $cell;
}

include ("create-connection.php");
include ("function-get-users-data-by-id.php");
include ("function-get-profile-text-simple.php");
$showGroups = isset($_GET['show-groups']) ? $_GET['show-groups'] : false;

/******************************** Haetaan tiedot **********************************/

// Haetaan tarjottavat ateriat
$sql = "SELECT `value` FROM `so_ajaxregister_fields` WHERE id = 34";
$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$field_values[] = $row["value"];				
	}
} else {
	echo "Ei tuloksia KENTTÄ";
	die();
}

$field_values = json_decode($field_values[0]);

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

// Haetaan henkilöiden ruokavalio

$diet = getUsersDataById($user_id, 43);

// Haetaan henkilöiden nimet

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

if($showGroups == 'true') {
	for($x = 0; $x < count($key); $x++) {
		if($name[$key[$x]]) {
			if ($position[$key[$x]] == 'henkilokunta') {
				$name[$key[$x]] = 'HENKILÖKUNTA | ' . nameFormatChanger($name[$key[$x]]);
			} elseif ($position[$key[$x]] == 'taiteilija') {
				$tmp = 'ESIINTYJÄT | ';
				if ($band[$key[$x]]) {
					$tmpBand = getProfileTextSimple($band[$key[$x]], $band_fields);
					$tmp .= $tmpBand . " | ";
				}
				$name[$key[$x]] = $tmp . nameFormatChanger($name[$key[$x]]);
			} elseif ($position[$key[$x]] == 'opettaja') {
				$name[$key[$x]] = 'OPETTAJAT | ' . nameFormatChanger($name[$key[$x]]);	
			} elseif ($position[$key[$x]] == 'talkoolainen') {
				$name[$key[$x]] = 'TALKOOLAISET | ' . nameFormatChanger($name[$key[$x]]);		
			} elseif ($position[$key[$x]] == 'kurssilainen') {
				$name[$key[$x]] = 'KURSSIT | ' . $job[$key[$x]] . " | " . nameFormatChanger($name[$key[$x]]);			
			} else {
				$name[$key[$x]] = 'MUUT | ' . nameFormatChanger($name[$key[$x]]);
			}
		}
	}
} else {
	for($x = 0; $x < count($key); $x++) {
		if($name[$key[$x]]) {
			$name[$key[$x]] = nameFormatChanger($name[$key[$x]]);
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
		$dateView[] = dateNameTranslator(date_format(date_create_from_format('j.n.Y', $dateString), 'D')) . date_format(date_create_from_format('j.n.Y', $dateString), ' j.n.');
		$date[] = $tmp[1];
		if($x > 0 && $date[$x] !== $date[$x-1]) {
			$colspan[] = $colspanCounter;
			$colspanCounter = 1;
		} else {
			
			$colspanCounter++;
		}
	}
	// 2. Ateriat
	$meal[] = substr(trim($field_values[$x]->text), 0, 1);
	$mealValue[] = $field_values[$x]->value;
}
$colspan[] = $colspanCounter;
$date = array_values(array_unique($date));
$dateView = array_values(array_unique($dateView));

/* Muokataan ateriatiedot:
 * Mikäli valittu kyllä, mutta aterioita ei ole määritelty niin, merkitään kaikki ateriat oleskeluajalta
 */

$dinerKeys = array_keys($diners);

for($x = 0; $x < count($yesDiners); $x++) {
	if(!in_array($yesDiners[$x], $dinerKeys)) {
		$diners[$yesDiners[$x]] = 'all';
	}	
}

// Määritellään Taulukon ominaisuuksia

// solujen leveys

$colspanWhole = count($mealValue)+5; // Kokotaulukon leveys
$cellWidthUnit = 100/$colspanWhole;

// solujen taustavärit

$bgInd = 0;
$cellcolor = array('#EEEEEE','#FFFFFF');

/************************** Muodostetaan taulukko **********************************/

$fileName = "ruokailijalista-". date('Ymd', time()) . "-klo-" . date('G-i', time()). ".pdf";
$docTitle = "RUOKAILIJALISTA " . date('Y', time());

$html = "<h1>" . $docTitle . "</h1>";
$html .= "<p>Luotu: " . date('j.n.Y', time()) . " klo " . date('G:i', time()) . "</p>";

$html .= '<table id="table" class="table" style="border: 1px solid gray;">';
$html .= "<tbody>";

// Päivämääräotsikot
$html .= '<tr bgcolor="#AAAAAA">';
$html .= '<td width="' . $cellWidthUnit*4 . '%" style="border-right: 1px solid black;"></td>'; // Tyhjä sarake nimille
for($x = 0; $x < count($date); $x++){
	$html .= '<td align="center" width="' . $cellWidthUnit*$colspan[$x] . '%" colspan="' . $colspan[$x] . '" style="border-left: 1px solid black;border-right: 1px solid black;">' . $dateView[$x] . '</td>';
}
$html .= "<td></td>"; // Tyhjä sarake aterioille per henkilö
$html .= "</tr>";

// Ateriaotsikot
$html .= '<tr style="font-weight: bold;">';
$html .= "<td>Nimi</td>"; // Tyhjä sarake nimille
for($x = 0; $x < count($meal); $x++){
	// Määritellään joka toiselle päivälle eri taustaväri
	if($x>0 && $dateExact[$x] != $dateExact[$x-1]) {
		$bgInd = fmod($bgInd+1,2);
	}
	$html .= '<td width="' . $cellWidthUnit . '%" align="center" bgcolor="' . $cellcolor[$bgInd] . '">' . $meal[$x]	 . "</td>";
}
$html .= '<td bgcolor="#CCCCCC" align="center">SUM.</td>'; // Tyhjä sarake aterioille per henkilö
$html .= "</tr>";

// Ruokailijoiden ateriat
$personMealCounter = 0;
for($y = 0; $y < count($mealValue); $y++){
	$mealCounter[$mealValue[$y]] = 0;
}
$bgInd = 0;

// Merkitään henkilöiden ateriat taulukkoon

if($showGroups == 'true') {
	$html .= '<tr><td colspan="' . $colspanWhole . '" bgcolor="#AAAAAA">' . $name[$key[0]][0] . '</td></tr>';
	if(count($name[$key[0]])>2) {
		$html .= '<tr><td colspan="' . $colspanWhole . '" bgcolor="#AAAAAA">' . $name[$key[0]][1] . '</td></tr>';
	}
}

for($x = 0; $x < count($key); $x++){
	if($showGroups == 'true') {
		// Asema muuttuu
		if($x > 0 && $name[$key[$x]][0] != $name[$key[$x-1]][0]) {
			$html .= '<tr><td colspan="' . $colspanWhole . '" bgcolor="#AAAAAA">' . $name[$key[$x]][0] . '</td></tr>';
		}
		// Jos tehtävä/kurssi/yhtye muuttuu
		if($x > 0 && count($name[$key[$x]])>2 && $name[$key[$x]][1] != $name[$key[$x-1]][1]) {
			$html .= '<tr><td colspan="' . $colspanWhole . '" bgcolor="#AAAAAA">' . $name[$key[$x]][1] . '</td></tr>';	
		}
	}	
	$html .= "<tr>";
	$html .= '<td style="border-top: 1px solid gray">' . end($name[$key[$x]]) . '</td>';
	// Käydään läpi ateriat
	for($y = 0; $y < count($mealValue); $y++){
		// Määritellään joka toiselle päivälle eri taustaväri
		if($y>0 && $dateExact[$y] != $dateExact[$y-1]) {
			$bgInd = fmod($bgInd+1,2);
		}
		// Merkitään ateria, jos kyseinen ateria löytyy henkilön ilmoittautumistiedoista
		if(strpos($diners[$key[$x]], $mealValue[$y]) !== false) {
			$html .= cellWriter('x',$cellcolor[$bgInd]);
			$personMealCounter++;
			$mealCounter[$mealValue[$y]] = $mealCounter[$mealValue[$y]]+1; 
		// Jos ateriatietona all
		} elseif(strpos($diners[$key[$x]],'all') || $diners[$key[$x]]=='all') { // LISÄÄ SAAPUMIS- JA POISTUMISPÄIVÄEHTO
			// Ellei tietoa saapumis- ja/tai lähtöpvästä
			if( !isset($arrDate[$key[$x]]) || !$arrDate[$key[$x]] || !isset($depDate[$key[$x]]) || !$depDate[$key[$x]]) {
				$html .= cellWriter('',$cellcolor[$bgInd]);
				$missingData[$key[$x]] = $name[$key[$x]];
			} else {
				// Merkitään ateria, jos henkilö on paikalla kyseisenä pvä:nä			
				if(strtotime($arrDate[$key[$x]]) <= strtotime($dateExact[$y]) && strtotime($depDate[$key[$x]]) >= strtotime($dateExact[$y]) && !$diners[$key[$x]]) {  
					$html .= cellWriter('x',$cellcolor[$bgInd]);
					//$html .= cellWriter('',$cellcolor[$bgInd]);
					$personMealCounter++;
					$mealCounter[$mealValue[$y]] = $mealCounter[$mealValue[$y]]+1; 
				} else {
					$html .= cellWriter('',$cellcolor[$bgInd]);
				}
			}
		} else {
			$html .= cellWriter('',$cellcolor[$bgInd]);		
		}
	}
	$html .= cellWriter($personMealCounter,'#CCCCCC');
	$personMealCounter = 0;
	$html .= "</tr>";
}


// Ruokailijoiden määrä per ateria
$html .= "<tr>";
$html .= '<td style="font-weight: bold;border-top: 1px solid gray;">SUM.</td>';
$bgInd = 0;
for($y = 0; $y < count($mealValue); $y++){
	if($y>0 && $dateExact[$y] != $dateExact[$y-1]) {
		$bgInd = fmod($bgInd+1,2);
	}
	$html .= cellWriter($mealCounter[$mealValue[$y]],$cellcolor[$bgInd]);
}
	$html .= cellWriter(array_sum($mealCounter),'#CCCCCC');

$html .= "</tbody>";
$html .= "</table>";

$html2 = "<h1>Ruokavaliot</h2>";
$html2 .= "<p>";
$key = array_keys($diet);
if($diet) {
	for($x = 0; $x < count($diet); $x++) {
		if(isset($key[$x]) && isset($key[$x][0]) && $name[$key[$x]][0]) {
			$html2 .= $name[$key[$x]][0] . ": " . $diet[$key[$x]] . "<br>";
		}
	}
} else {
	$html2 .= "Ei ruokavalioita.";
}
$html2 .= "</p>";

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

echo "<h2>Lista ruokailun valvontaa varten</h2>";
if($showGroups == 'true') {
	echo "<a href='index.php?page=diner-list&textareasize=large'>Piilota ryhmät</a>";
} else {
	echo "<a href='index.php?page=diner-list&textareasize=large&show-groups=true'>Näytä ryhmät</a>";
}
echo "<form action='pdf-templates/diner-list-1/pdf-creator.php' method='post' target='_blank'>";
echo "<textarea name='html'>" . $html . "</textarea>";
echo "<textarea name='html2'>" . $html2 . "</textarea>";
echo '<input type="hidden" name="doc-title" value="' . $docTitle . '"/>';
echo '<input type="hidden" name="file-name" value="' . $fileName . '"/>';
echo "<p><input type='submit' name='submit' value='Luo pdf'></p>";
echo "</form>";
?>
<p style="margin:10px 0 0"><input type="button" onclick="tableToExcel('table', 'Ruokailut')" value="Lataa tiedosto exceliin"></p>
<?php echo "<div style='display: none;'>" . $html . "</div>";


?>

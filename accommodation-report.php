<div id="info-view">
<?php
checkPermission(1); 

include('syncronation-msg.php');

$_GET['profile-filter-field']=28;
$_GET['profile-filter']='kylla';
// $_GET['selected-columns']='30 32 31';
$_GET['selected-columns']='52 53 32 31';

include ('function-get-profile-text.php');
include ('function-name-format-changer.php');
include('get-accommodation-data.php');

//$fields_id = $fields->id; // kenttien id:t
//$fields_label = $fields->label;	// kenttien labelit?>

<?php 
/********************* Käyttäjien majoitustiedot (saapumis-, ja lähtöpäivä sekä paikka omiin arrayhin ******************/

for ($x = 0; $x <= count($user->name); $x++) {
	// ShowProfile -funktioon tarvittavia muuttujia
	if(isset($user->id[$x])) {

		$profileIds = array_keys(preg_grep("/" . $user->id[$x] . "/A", $profile->user_id)); // KÄYTTÄJÄN PROFIILITIETOJEN ID:T 
	
		foreach ($profileIds as $value) {
			$profile_field_id[] = $profile->field_id[$value]; 
			$profile_value[] = $profile->value[$value];
		}		
		/*echo $user->name[$x] . ": <br>";
		print_r($profile_field_id);
		echo "<br>";
		print_r($profile_value);
		echo "<br>";
		echo "<br>";*/
		
		if($user->name[$x]) {
			$name[$x] = nameFormatChanger($user->name[$x]);
		}
		// Saapumispäivät arrayhyn
		$dateId = array_search(32, $profile_field_id);
		if($dateId) {
			$date = $profile_value[$dateId];
			$arrDates[$x] = $date;
			$arrTimestamps[$x] = strtotime($date);
		}
		if($dateId==0) {
			$date = $profile_value[$dateId];
			$arrDates[$x] = $date;
			$arrTimestamps[$x] = strtotime($date);
		}
		unset($dateId);
	
		// Poistumispäivät arrayhyn
		$dateId = array_search(31, $profile_field_id);
		if($dateId) {
			$date = $profile_value[$dateId];
			$depDates[$x] = $date;
			$depTimestamps[$x] = strtotime($date);
		}
		if($dateId==0) {
			$date = $profile_value[$dateId];
			$arrDates[$x] = $date;
			$arrTimestamps[$x] = strtotime($date);
		}
		unset($dateId);
		
	
		// Majoituspaikka arrayhyn
		if(array_search(52, $profile_field_id)){
			$placeId = array_search(52, $profile_field_id);
			$places[$x] = getProfileText($profile_value[$placeId], $fields, 2);	
		} else {
			$places[$x] = "Paikkaa ei määritelty";
		}
	
		// Huone/luokka
		if(array_search(53, $profile_field_id)){
			$roomId = array_search(53, $profile_field_id);
			$rooms[$x] = $profile_value[$roomId];
		} else {
			$rooms[$x] = "";
		}
		
		// Poistetaan kierrosta varten luodut muuttujat
		unset($profile_field_id);
		unset($profile_value);
	}
} 

/****************** Muodostetaan array, jossa on kaikki päivät ensimmäisestä viimeiseen majoituspäivään ****************/

// 1. Haetaan alku ja loppupäivät
$startDate = min($arrDates);
$endDate = max($depDates);

// 2. Lasketaan majoituspäivien määrä
$date1Timestamp = strtotime($startDate);
$date2Timestamp = strtotime($endDate);
$difference = $date2Timestamp - $date1Timestamp;
$dateCount = $difference/86400; // ERO PÄIVISSÄ

// 3. Muodostetaan array
$tmpDate = $startDate;
for ($x = 0; $x < $dateCount; $x++) {
	$allDates[] = $tmpDate;
	$tmpDate = date('Y-m-d', strtotime($tmpDate . " +1 days"));
}
$allDates[] = $endDate;


/***********************************************************************************************************************
 * Käydään läpi kaikki päivät, tutkistaan ketkä majoittuvat ko. päivänä ja sijoitetaan majoittujat arrayhyn 
 * accData['paikka']['aika']
***********************************************************************************************************************/

$accData = [];
for ($x = 0; $x < count($allDates); $x++) {
	for($y = 0; $y <= count($user->name); $y++) {
		if(isset($arrTimestamps[$y]) && $arrTimestamps[$y]) {
			if(strtotime($allDates[$x]) >= $arrTimestamps[$y] &&  strtotime($allDates[$x]) <= $depTimestamps[$y]) {
				//echo $user->name[$y] . " majoittuu " . $allDates[$x] . " paikka: " . $places[$y] . " saapuu " . $arrDates[$y] . "<br>";			
				if($name[$y]) {
					if($rooms[$y]) {
						$accData[$places[$y]][$allDates[$x]][] = "Luokka/huone " . $rooms[$y] . ": " . $name[$y]; 
					} else {
						$accData[$places[$y]][$allDates[$x]][] = $name[$y]; 
					}
				}
			}
		}		
	}
}

// Järjestetään tiedot: paikka, aika, nimet

asort($accData);
$placeKeys = array_keys($accData);

for($x = 0; $x < count($accData); $x++) {
	ksort($accData[$placeKeys[$x]]);
	$dateKeys = array_keys($accData[$placeKeys[$x]]);
	for($y = 0; $y < count($accData[$placeKeys[$x]]); $y++) {
			asort($accData[$placeKeys[$x]][$dateKeys[$y]]);
	}
}



// Tulostetaan

$fileName = "majoitustiedot-". date('Ymd', time()) . "-klo-" . date('G-i', time()). ".pdf";
$docTitle = "MAJOITUSTIEDOT";

$html = "<h1>" . $docTitle . "</h1>";
$html .= "<p>Luotu: " . date('j.n.Y', time()) . " klo " . date('G:i', time()) . "</p>";
for($x = 0; $x < count($accData); $x++) {
	$counter = 0;
	$html .= "<h2>" . $placeKeys[$x] . "</h2>";
	$html .= "<table id=\"table\" class=\"table\" border=\"1\" cellpadding=\"4\">";
	$html .= "<tr><th width=\"90\"><b>Päiväys</b></th><th width=\"90\"><b>Hlö-määrä</b></th><th width=\"450\"><b>Majoittujat</b></th></tr>";
	$dateKeys = array_keys($accData[$placeKeys[$x]]);
	for($y = 0; $y < count($dateKeys); $y++) {
		$html .= "<tr>";
		$html .= "<td width=\"90\">";
		$html .= date_format(date_create($dateKeys[$y]), 'd.m.Y');
		$html .= "</td>";
		$html .= "<td width=\"90\" align=\"center\">";
		$html .= count($accData[$placeKeys[$x]][$dateKeys[$y]]);
		$counter = $counter + count($accData[$placeKeys[$x]][$dateKeys[$y]]);
		$html .= "</td>";
		$html .= "<td>";
		for($z = 0; $z < count($accData[$placeKeys[$x]][$dateKeys[$y]]); $z++) {
			$html .= $accData[$placeKeys[$x]][$dateKeys[$y]][$z] . "<br>";
		}	
		$html .= "</td>";
		$html .= "</tr>";	
	}
	$html .= "</table>";
	$html .= "<p>kaikkiaan " . $counter . " majoitusvuorokautta</p>";
}
?>

<form action="pdf-templates/accommodation-report/pdf-creator.php" method="POST" target="_blank">
	<textarea name='html'>
	<?php echo $html; ?>
	</textarea>
	<input type="hidden" name="doc-title" value="<?php echo $docTitle; ?>"/>
	<input type="hidden" name="file-name" value="<?php echo $fileName; ?>"/>
	<input type="submit" name="submit" value="Luo pdf"/>
</form>


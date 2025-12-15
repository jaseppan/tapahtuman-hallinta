<?php

if(!$user) {
	include_once ("stdObject.php");
	$user = new stdObject();
	$profile = new stdObject();
	$fields = new stdObject();
}

// Alla olevassa tiedostossa on $minId, joka on määrittelee, mitkä kurssit tuodaan.
include ('student-data-2-profile-transform-list.php');	

// Haetaan sähköpostit ja kurssien ja hakemusten id:t

$sql = "SELECT id, course_id, email FROM `so_seminarman_application` WHERE course_id > " . $minId . " AND published = 1";	
$result = $mysqli->query($sql);	
	if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$tmpUserId[] = $row["id"]+10000000;
		$tmpUserEmail[] = $row["email"];
		$course_id[] = $row["course_id"]; // HAE TÄMÄN PERUSTEELLA KURSSIN NIMI JOKA TULEE TEHTÄVÄKOHTAAN
	}
} else {
	echo "tietoja taulusta so_seminarman_application ei saatu";
}

$minAppId = min($tmpUserId)-10000000;

// Haetaan kurssit

$sql = "SELECT id, title, alias FROM `so_seminarman_courses` WHERE id > " . $minId;
$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$id_of_course[] = $row["id"];
		$title_of_course[] = $row["title"];
		$alias_of_course[] = $row["alias"];
	}
} else {
	echo "0 results";
}

//Haetaan lisätiedot
$select = array_keys($idTransformList);

$sql = "SELECT * FROM `so_seminarman_fields_values` WHERE field_id = 29 AND applicationid >= " . $minAppId;
for($x = 0; $x < count($select); $x++) {
	$sql .= " OR field_id = " . $select[$x] . " AND applicationid >= " . $minAppId;	
}

$result = $mysqli->query($sql);	

/* Tuodaan ilmoittautumistiedot user- ja profile-objekteihin. 
 * Samalla arvot muututaan yhteneväisiksi profiilitietojen kanssa.
 * ilmoittautumis- (_seminarman_fields_values) ja profiilitietojen (_ajaxregister_fields) id:t löytyvät tiedoston student-data-2-	profile-transform-list.php kommentista.
*/

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		
		// KURSSILAISEN NIMI			
		if($row["field_id"]==29) {
			$z = array_search($row["applicationid"]+10000000,$tmpUserId);
			$user->id[] = $tmpUserId[$z];
			$user->email[] = $tmpUserEmail[$z];
			$user->name[] = $row["value"];

			$profile->user_id[] = $row["applicationid"]+10000000;
			$profile->field_id[] = 20;
			$profile->value[] = "kurssilainen";

			$chosen_course = array_search($course_id[$z], $id_of_course);
			$profile->user_id[] = $row["applicationid"]+10000000;
			$profile->field_id[] = 59;
			$profile->value[] = filterText($title_of_course[$chosen_course]);
			
		// JOS ATERIAT
		} elseif($row["field_id"]==19) {
			if($row["value"]=="Ei ruokailupakettia" || $row["value"]=="No catering" || !$row["value"]){
				$profile->user_id[] = $row["applicationid"]+10000000;
				$profile->field_id[] = 33;
				$profile->value[] = "ei";
			} else {
				$profile->user_id[] = $row["applicationid"]+10000000;
				$profile->field_id[] = 33;
				$profile->value[] = "kylla";
				if($row["value"]=="5 day breakfast, lunch and supper (80 €)" || $row["value"]=="5 pv aamupala, lounas, iltaruoka (80 €)"){
					$profile->user_id[] = $row["applicationid"]+10000000;
					$profile->field_id[] = 34;
					$profile->value[] = $allMeals;
				}
				if($row["value"]=="5 pv lounas (30 €)" || $row["value"]=="5 days lunch (30 €)") {
					$profile->user_id[] = $row["applicationid"]+10000000;
					$profile->field_id[] = 34;
					$profile->value[] = $lunches;			
					
				}
			}
		
		// JOS MAJOITUS
		} elseif ($row["field_id"]==21) {
			if($row["value"]=="Ei" || $row["value"]=="No") {
				$profile->user_id[] = $row["applicationid"]+10000000;
				$profile->field_id[] = 28;
				$profile->value[] = "ei";										
			} elseif ($row["value"]=="Kyllä" || $row["value"]=="Yes"){
				$profile->user_id[] = $row["applicationid"]+10000000;
				$profile->field_id[] = 28;
				//$profile->value[] = aamiainen 26.6.|lounas 26.6.|paivallinen 26.6.|aamiainen 27.6.|lounas 27.6.|paivallinen 27.6.|aamiainen 28.6.|lounas 28.6.|paivallinen 28.6.|aamiainen 29.6.|lounas 29.6.|paivallinen 29.6.|aamiainen 30.6.|lounas 30.6.|paivallinen 30.6.|aamiainen 1.7.|lounas 1.7.|paivallinen 1.7.|aamiainen 2.7.	
				$profile->user_id[] = $row["applicationid"]+10000000;
				$profile->field_id[] = 52;
				$profile->value[] = "kontion-koulu";									
			}
		
		// SAAPUMISPÄIVÄ
		} elseif ($row["field_id"]==16) {
			$profile->user_id[] = $row["applicationid"]+10000000;
			$profile->field_id[] = 32;
			// Tarkistetaan päiväyksen formaatti ja muutetaan tarvittaessa muotoon VVVV-MM-DD
			if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$row["value"])){
				$profile->value[] = $row["value"];
			} else {
				$profile->value[] = date_format(date_create_from_format('d.m.Y', $row["value"]), 'Y-m-d');
			}
		// LÄHTÖPÄIVÄ
		} elseif ($row["field_id"]==17) {
			$profile->user_id[] = $row["applicationid"]+10000000;
			$profile->field_id[] = 31;
			// Tarkistetaan päiväyksen formaatti ja muutetaan tarvittaessa muotoon VVVV-MM-DD
			if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$row["value"])){
				$profile->value[] = $row["value"];
			} else {
				$profile->value[] = date_format(date_create_from_format('d.m.Y', $row["value"]), 'Y-m-d');
			}
		} else {
			$profile->user_id[] = $row["applicationid"]+10000000;
			$profile->field_id[] = $idTransformList[$row["field_id"]];
			$profile->value[] = $row["value"];
		}
	}
} else {
	echo "0 results";
}

// LISÄTÄÄN ASEMAT PROFIILITIETOIHIN
for($x = 0; $x < count($user->name); $x++) {			
	$profile->user_id[] = $user->id[$x];
	$profile->field_id[] = 20;
	$profile->value[] = "kurssilainen";
}


?> 

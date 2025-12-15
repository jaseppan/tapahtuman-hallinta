<?php

checkPermission(1); 
include_once ("stdObject.php");
include("hidden_persons.php");

$user = new stdObject();
$profile = new stdObject();
$fields = new stdObject();

/********************************* Tuo profiilin kentät*********************************************/

// Rajataan kentät, jos rajaus määritelty selected-columns -parametrissä
if($_GET['selected-columns']){

	// Luetaan selected-columns -parametrin arvot arrayhyn
	$column_display = explode(" ", $_GET['selected-columns']);

	// Muodostetaan kysely
	$sql = "SELECT * FROM `so_ajaxregister_fields` WHERE `published` = 1 AND `id` = " . $column_display[0];
	for ($y = 1; $y <= count($column_display)-1; $y++) {
		$sql .= " OR `id` = " . $column_display[$y];
	}
	$sql .= " ORDER BY `ordering`";

} else {
	$sql = "SELECT * FROM `so_ajaxregister_fields` WHERE `published` = 1 ORDER BY `ordering`";
}

$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$fields->id[] = $row["id"];
		$fields->type[] = $row["type"];
		$fields->label[] = $row["label"];
		$fields->value[] = $row["value"];				
	}
} else {
	echo "Ei tuloksia KENTTÄ";
	die();
}


/************************************************ Tuo profiilin tiedot ********************************************/
$sql = "SELECT * FROM `so_ajaxregister_field_values`";

// jos rajauksia
if($_GET['selected-columns'] || $_GET['profile-filter'] || $_GET['id']) {
	$sql .= " WHERE";	
}

// Rajataan tiedot, jos rajaus määritelty selected-columns -parametrissä
if($_GET['selected-columns']){
	// Muodostetaan kysely
	$sql .= " `field_id` = " . $column_display[0];
	// Jos lisäksi profile-filter
	
	if($_GET['profile-filter']) {
		$filterData = profileFilter($_GET['profile-filter'],$_GET['profile-filter-field'], $mysqli);
		$sql .= " AND " . $filterData;
	}

	for ($y = 1; $y <= count($column_display)-1; $y++) {
		$sql .= " OR `field_id` = " . $column_display[$y];
		// Jos lisäksi profile-filter
		if($_GET['profile-filter']) {
			// kysely rajatuilla henkilöillä	
			$sql .= " AND " . $filterData;
		}
	}
}

// jos id määritelty
if (isset($_GET['id']) && $_GET['id']) {
	if($_GET['selected-columns']) {
		$sql .= " AND";	
	}	
	$sql .= " `user_id` = " . $_GET['id'];
}

// jos profile-filter määritelty
if($_GET['profile-filter'] && !$_GET['selected-columns']) {
	// Haetaan ensin rajauksella, jotta saadaan henkilön id
	$filterData = profileFilter($_GET['profile-filter'],$_GET['profile-filter-field'], $mysqli);
	$sql .= " " . $filterData;
}

// Haku tietokannasta
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$profile->id[] = $row["id"];			
		$profile->user_id[] = $row["user_id"];
		$profile->field_id[] = $row["field_id"];
		$profile->value[] = $row["value"];
	}
}

/****************************************** Tuo käyttäjät ****************************************/

$sql = "SELECT * FROM `so_users` WHERE ";
if($_GET['profile-filter']) {
	$filtered_users = array_unique($profile->user_id);
	foreach($filtered_users as $filtered_user) {
		// Lisätään hakuun jos ei ole piilotetuissa
		if(!in_array($filtered_user, $hidden_persons)) {
			$sql .= "`id` = " . $filtered_user;
			if ($filtered_user != end($filtered_users)) {
				$sql .= " OR ";
			}
		}
	}
} else {
	if ($_GET['id']) {	
		$sql .= "`id` = " . $_GET['id'];
	// Ellei hakua suodateta id:n tai profile-filterillä, niin näytetään kaikki paitsi piilotetut henkilöt
	} else {		
		foreach ($hidden_persons as $hidden_person) {
			$sql .= "`id` != " . $hidden_person; 
			if ($hidden_person != end($hidden_persons)) {
				$sql .= " AND ";
			}	
		}
	}
}


$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$user->id[] = $row["id"];
		$user->name[] = $row["name"];
		$user->email[] = $row["email"];			

	}
}

?>


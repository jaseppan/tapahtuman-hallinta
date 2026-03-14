<?php

include_once ("stdObject.php");
include("hidden_persons.php");

$user = new stdObject();
$profile = new stdObject();
$fields = new stdObject();

///include('get-student-data.php');

/********************************* Tuo profiilin kentät*********************************************/

// Rajataan kentät, jos rajaus määritelty selected-columns -parametrissä
if(isset($_GET['selected-columns']) && $_GET['selected-columns'] || isset($_SESSION['privileges']) && $_SESSION['privileges'] == 1){
	// Luetaan selected-columns -parametrin arvot arrayhyn
	if($_SESSION['privileges']==1) {
		$allowed_data = array(20,59,18);
		if($_GET['selected-columns']) {
			$tmp = explode(" ", $_GET['selected-columns']);
			foreach($tmp as $value) {
				if(in_array($value, $allowed_data)){
					$column_display[] = $value;
				}			
			}
		} else {
			$column_display = $allowed_data;
		}
	} else {
		$column_display = explode(" ", $_GET['selected-columns']);
	}

	// Muodostetaan kysely
	$sql = "SELECT * FROM `so_ajaxregister_fields` WHERE `published` = 1 AND `id` = " . $column_display[0];
	for ($y = 1; $y <= count($column_display)-1; $y++) {
		$sql .= " OR `published` = 1 AND `id` = " . $column_display[$y];
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
		$fields->validation[] = $row["validation"];
		$fields->dependency[] = $row["dependency"];
		$fields->dependency_state[] = $row["dependency_state"];	
						
	}
} else {
	//echo "Ei tuloksia KENTTÄ";
	//die();
}

/************************************************ Tuo profiilin tiedot ********************************************/
$sql = "SELECT * FROM `so_ajaxregister_field_values`";
$tail = '';

// jos id määritelty
if (isset($_GET['id']) && $_GET['id']) {
	if(isset($_GET['selected-columns']) && $_GET['selected-columns']) {
		$tail .= " AND";	
	}	
	$tail .= " `user_id` = " . $_GET['id'];
} else {

	// Rajataan tiedot, jos rajaus määritelty selected-columns -parametrissä
	if( isset($_GET['selected-columns']) && $_GET['selected-columns'] ){
		// Muodostetaan kysely
		$tail .= " `field_id` = " . $column_display[0];
		// Jos lisäksi profile-filter
		
		if($_GET['profile-filter']) {
			$filterData = profileFilter($_GET['profile-filter'],$_GET['profile-filter-field'], $mysqli,$hidden_persons);
			$tail .= " AND " . $filterData;
		}
	
		for ($y = 1; $y <= count($column_display)-1; $y++) {
			$tail .= " OR `field_id` = " . $column_display[$y];
			// Jos lisäksi profile-filter
			if($_GET['profile-filter']) {
				// kysely rajatuilla henkilöillä	
				$tail .= " AND " . $filterData;
			}
		}
	}

	
	// jos profile-filter määritelty
	if( isset($_GET['profile-filter']) && $_GET['profile-filter'] && !isset($_GET['selected-columns']) || !$_GET['selected-columns']) {
		// Haetaan ensin rajauksella, jotta saadaan henkilön id
		$filterData = profileFilter($_GET['profile-filter'],$_GET['profile-filter-field'], $mysqli,$hidden_persons);
		$tail .= " " . $filterData;
	}

}

// jos rajauksia
if( !empty( $tail ) ) {
	$sql =  $sql . " WHERE" . $tail;	
}

// DEBUG: Kirjoita SQL-kysely ja -tulos error-logiin
error_log('PROFILE QUERY SQL: ' . $sql);

// Haku tietokannasta

$result = $mysqli->query($sql);
error_log('PROFILE QUERY RESULTS: ' . ($result ? $result->num_rows : 'ERROR') . ' rows');
if ($result && $result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$profile->id[] = $row["id"];			
		$profile->user_id[] = $row["user_id"];
		$profile->field_id[] = $row["field_id"];
		$profile->value[] = $row["value"];
	}
	error_log('PROFILE ARRAYS POPULATED: user_id count=' . count($profile->user_id));
} else {
	error_log('PROFILE QUERY FAILED OR NO RESULTS');
}

/****************************************** Tuo käyttäjät ****************************************/

$sql = "SELECT * FROM `so_users` WHERE ";
if(isset($_GET['profile-filter']) && $_GET['profile-filter']) {
	$filterData = str_replace("user_id","id",$filterData);
	$sql .= " " . str_replace("user_id","id",$filterData) . " ";
} else {
	if (isset($_GET['id']) && $_GET['id']) {	
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

// var_dump($user);

?>
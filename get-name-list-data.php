<?php

include_once ("stdObject.php");
include_once ("function-get-profile-text.php");
$noPosition = new stdObject();

// Ellei asemaa ole määritelty niin haetaan kaikki muut joiden asemaa ei ole määritelty
$sql = "SELECT user_id FROM `so_ajaxregister_field_values` WHERE `field_id` = 20";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$tmp_user_id[] = $row["user_id"];		
	}
}
$tmp_user_id = array_unique(array_merge($tmp_user_id,$hidden_persons));

$sql = "SELECT * FROM `so_users` WHERE  id != " . $tmp_user_id[0];
unset($tmp_user_id[0]);
foreach ($tmp_user_id as $tmp){
	$sql .= " AND id != " . $tmp;
}

$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$noPosition->id[] = $row["id"];
		$noPosition->name[] = $row["name"];
		$noPosition->email[] = $row["email"];			
	}
} 

/****************************************** Tuo muut samassa asemassa olevat henkilöt ****************************************/

$positionTitle = getProfileText($profile->value[array_search(20, $profile->field_id)], $fields, 0);

if($positionTitle){
	//$positionTitle = getProfileText($profile->value[array_search(20, $profile->field_id)], $fields, 0);
	$otherUsers = new stdObject();
	$filtered = profileFilter2($profile->value[array_search(20, $profile->field_id)], 20, $mysqli, 'id', $hidden_persons);
	$sql = "SELECT * FROM `so_users` WHERE " . $filtered;

	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$otherUsers->id[] = $row["id"];
			$otherUsers->name[] = $row["name"];
			$otherUsers->email[] = $row["email"];			
		}
	}
}

/******************************************** Jos henkilö majoittuu tuo muut majoittujat ************************************/
if($profile->value[array_search(28, $profile->field_id)]=="kylla") {
	$boarder = new stdObject();
	$filtered = profileFilter2('kylla', 28, $mysqli,'id', $hidden_persons);
	$sql = "SELECT * FROM `so_users` WHERE " . $filtered;
	//print_r($sql);
	$result = $mysqli->query($sql);
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$boarder->id[] = $row["id"];
			$boarder->name[] = $row["name"];
			$boarder->email[] = $row["email"];			
		}
	}
}
	
/******************************************** Jos henkilö ruokailee tuo muut ruokailijat ************************************/
if($profile->value[array_search(33, $profile->field_id)]=="kylla") {
	$diner = new stdObject();
	$filtered = profileFilter2('kylla', 33, $mysqli, 'id', $hidden_persons);
	$sql = "SELECT * FROM `so_users` WHERE " . $filtered;

	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$diner->id[] = $row["id"];
			$diner->name[] = $row["name"];
			$diner->email[] = $row["email"];			
		}
	}
}
/**************************************** Jos henkilö menee Vienaan tuo muut Vienaan menijät ************************************/
if($profile->value[array_search(45, $profile->field_id)]=="kylla") {
	$vienaJourneyParticipant = new stdObject();
	$filtered = profileFilter2('kylla', 45, $mysqli, 'id', $hidden_persons);
	$sql = "SELECT * FROM `so_users` WHERE " . $filtered;
	
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$vienaJourneyParticipant->id[] = $row["id"];
			$vienaJourneyParticipant->name[] = $row["name"];
			$vienaJourneyParticipant->email[] = $row["email"];			
		}
	}
}

?>

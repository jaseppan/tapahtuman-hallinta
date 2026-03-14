<?php

require('function-array-remove.php');

/************************** Funktio, jolla suodatetaan sisältö annetulla hakuehdolla ****************/
function profileFilter($filter, $filter_field, $mysqli, $hidden_persons = []) {
	// Haetaan ensin rajauksella, jotta saadaan henkilön id
	$tmpsql = "SELECT * FROM `so_ajaxregister_field_values` WHERE `value` = '" . $filter . "'";
	if($filter_field) {
		$tmpsql .= " AND `field_id` = " . $filter_field;
	}

	$result = $mysqli->query($tmpsql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$profile_id[] = $row["user_id"];			
		}
	} 

	// Poistetaan piilotetut henkilöt
	if($profile_id) {
		foreach($hidden_persons as $blackListItem){
		  $profile_id = array_remove($profile_id,$blackListItem,true);
		}
	}
	// kysely rajatuilla henkilöillä - käytetään IN() operaattoria OR ketjun sijaan
	$sql = "`user_id` IN (" . implode(",", $profile_id) . ")";
	return $sql;
}

/* Dynaamisempi versioFunktiosta:
 * tuotettavan kyselyn sarake on määriteltävissä $column -parametrillä.
 */

function profileFilter2($filter, $filter_field, $mysqli, $column, $hidden_persons) {
	// Haetaan ensin rajauksella, jotta saadaan henkilön id
	$tmpsql = "SELECT * FROM `so_ajaxregister_field_values` WHERE `value` = '" . $filter . "'";
	if($filter_field) {
		$tmpsql .= " AND `field_id` = " . $filter_field;
	}

	$result = $mysqli->query($tmpsql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$profile_id[] = $row["user_id"];			
		}
	} 

	// Poistetaan piilotetut henkilöt
	foreach($hidden_persons as $blackListItem){
	  $profile_id = array_remove($profile_id,$blackListItem,true);
	}

	// kysely rajatuilla henkilöillä - käytetään IN() operaattoria OR ketjun sijaan
	$sql = "`" . $column . "` IN (" . implode(",", $profile_id) . ")";
	return $sql;
}

?>

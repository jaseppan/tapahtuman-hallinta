<?php

checkPermission(1); 

function getUserList($mysqli,$orderBy = 'name') {
	
	include_once ("stdObject.php");
	include("hidden_persons.php");

	$user = new stdObject();

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
    
	$sql .= " ORDER BY " . $orderBy;


	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$user->id[] = $row["id"];
			$user->name[] = $row["name"];
			$user->email[] = $row["email"];			

		}
	}
	
	return $user;
}


?>

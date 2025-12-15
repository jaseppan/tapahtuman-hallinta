<?php

include_once ("stdObject.php");
include('hidden_persons.php');
$users = new stdObject();
$sql_hidden_part = '';
if ($hidden_persons) {
	foreach ($hidden_persons as $hidden_person) {
		$sql_hidden_part = " AND `id` != " . $hidden_person; 
	}	
}

/******************************************  profiilihaku ****************************************/

$sql = "SELECT `user_id` FROM `so_ajaxregister_field_values` WHERE `value`LIKE '%" . $_POST['search'] . "%'";
$sql .=  $sql_hidden_part;

$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$user_id[] = $row["user_id"];		
	}
}

if( isset($user_id) && $user_id ) {
	$sql = "SELECT * FROM `so_users` WHERE `id` = " . $user_id[0];
	if( is_array( $user_id ) && $user_id > 1 ) {
		for($x = 1; $x < count($user_id); $x++) {
			$sql .= " OR `id` = " . $user_id[$x];
			$sql .=  $sql_hidden_part;
		}
	} 
	
	$result = $mysqli->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$users->id[] = $row["id"];
			$users->name[] = $row["name"];
			$users->email[] = $row["email"];			
		}
	}
}

/******************************************  henkilöhaku ****************************************/

$sql = "SELECT * FROM `so_users` 
WHERE name LIKE '%" . $_POST['search'] . "%' 
OR email LIKE '%" . $_POST['search'] . "%' ";
$sql .=  $sql_hidden_part;

$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$users->id[] = $row["id"];
		$users->name[] = $row["name"];
		$users->email[] = $row["email"];			
	}
}

?>

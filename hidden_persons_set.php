<?php
checkPermission(1);
include("hidden_persons.php");

if($_POST['submit']) {
	unset($_POST['submit']);
	$keys = array_keys($_POST);
	for($x = 0; $x < count($_POST); $x++) {
		if(in_array($keys[$x],$hidden_persons_db) && $_POST[$keys[$x]]==0) { 
			$sql_delete = "DELETE FROM `so_js_event_manager_hidden_persons` WHERE `user_id` = " . $keys[$x] . "; ";
		}
		if(!in_array($keys[$x],$hidden_persons_db) && $_POST[$keys[$x]]==1) {
			$sql_insert = "INSERT INTO `so_js_event_manager_hidden_persons` (`user_id`) VALUES (" . $keys[$x] . "); ";	
		}
	}		
}
if( isset($sql_insert) ) {
	if ($mysqli->multi_query($sql_insert) === TRUE) {
		echo "New records created successfully";
	} else {
		echo "Error";
	}
}

if(isset($sql_delete)) {
	if ($mysqli->multi_query($sql_delete) === TRUE) {
		echo "New records created successfully";
	} else {
		echo "Error";
	}
}

mysqli_close($mysqli);

echo "<head><meta http-equiv='refresh' content='0; url=index.php?page=manage_listed_users'></head>";
?>



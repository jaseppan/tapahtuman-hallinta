<?php

function getUsersDataById($user_id, $field_id) {
	global $mysqli;
	if(is_array($user_id)) {
		$sql = "SELECT `user_id`, `value` FROM `so_ajaxregister_field_values` WHERE `field_id` = " . $field_id . " AND `user_id` = " . $user_id[0];
		for($x = 1; $x < count($user_id); $x++) {
			$sql .= " OR `field_id` = " . $field_id . " AND `user_id` = " . $user_id[$x];
		}
	} else {
		$sql = "SELECT `user_id`, `value` FROM `so_ajaxregister_field_values` WHERE `field_id` = " . $field_id . " AND `user_id` = " . $user_id;
	}

	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$value[$row["user_id"]] = $row["value"];					
		}
	}
	return $value;
}

?>

<?php

$hidden_persons_hardcoded = array(216, 217, 231, 237);

$sql = "SELECT user_id FROM `so_js_event_manager_hidden_persons`";

$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$hidden_persons_db[] = $row["user_id"];					
	}
}

$hidden_persons = array_unique(array_merge($hidden_persons_hardcoded, $hidden_persons_db));

?>



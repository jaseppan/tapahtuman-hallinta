<?php

$sql = "SELECT * FROM `so_js_event_manager_band_event_link`";

$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$linked_event_id[] = $row["event_id"];
		$linked_band_name[] = $row["band_name"];					
	}
}

?>



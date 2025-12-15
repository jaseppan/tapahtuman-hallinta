<?php
/********************************* Tuo profiilin kent�t*********************************************/

include_once ("stdObject.php");
$fields = new stdObject();


// Rajataan kent�t, jos rajaus m��ritelty selected-columns -parametriss�
if(isset($_GET['selected-columns']) && $_GET['selected-columns']){

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
		$fields->validation[] = $row["validation"];
		$fields->dependency[] = $row["dependency"];
		$fields->dependency_state[] = $row["dependency_state"];	
						
	}
} else {
	echo "Ei tuloksia KENTT�";
	die();
}

// Override meals

include ('student-data-2-profile-transform-list.php');
$fields->value['21'] = $allMealValues;

?>
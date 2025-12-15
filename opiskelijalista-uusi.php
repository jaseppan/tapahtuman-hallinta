<h3>Ilmoittautuneet opiskelijat</h3>
<?php
	checkPermission(2);
	include ('student-data-2-profile-transform-list.php');

	// Configuration 
	//$minId = 6; //tämän vuoden kurssien pienin id-arvo

	// Haetaan otsikot
	
	$sql = "SELECT id, name FROM `so_seminarman_fields`";
	mysql_query("SET NAMES 'utf8'", $mysqli);
	$result = $mysqli->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			$field_id[] = $row["id"];
			$field_name[] = $row["name"];
		}
	} else {
		echo "0 results";
		die();
	}

	// Haetaan hakemukset
	
	$sql = "SELECT id, course_id, email, published FROM `so_seminarman_application` WHERE course_id>" . $minId;	
	$result = $mysqli->query($sql);	
		if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$student_id[] = $row["id"];
			$course_id[] = $row["course_id"];
			$email[] = $row["email"];
			$published[] = $row["published"];
			/*echo $row["applicationid"] . ", ";
			echo $row["user_id"] . ", ";
			echo $row["field_id"] . ", ";
			echo $row["value"] ." ||";*/
		}
	} else {
		echo "tietoja taulusta so_seminarman_application ei saatu";
		die();
	}
	
	// Haetaan kurssit
	
	$sql = "SELECT id, title, alias FROM `so_seminarman_courses` WHERE id>" . $minId;
	$result = $mysqli->query($sql);	
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$id_of_course[] = $row["id"];
			$title_of_course[] = $row["title"];
			$alias_of_course[] = $row["alias"];
		}
	} else {
		echo "0 results";
		die();
	}
	
	//Haetaan ilmoittautumistiedot
	$sql = "SELECT * FROM `so_seminarman_fields_values`";	
	$result = $mysqli->query($sql);	
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$applicationid[] = $row["applicationid"];
			$user_id[] = $row["user_id"];
			$field_id[] = $row["field_id"];
			$field_value[] = $row["value"];
			/*echo $row["applicationid"] . ", ";
			echo $row["user_id"] . ", ";
			echo $row["field_id"] . ", ";
			echo $row["value"] ." ||";*/
		}
	} else {
		echo "0 results";
		die();
	}

	// Tulostetaan otsikot
	echo "<table>";
	$counter["total"] = 0;
	foreach($alias_of_course as $value) {
		$counter[$value] = 0;
	}
	
	// Tulostetaan opiskelijalista
	for ($x = 0; $x <= count($id_of_course); $x++) {
		$courseIds = array_keys(preg_grep("/" . $id_of_course[$x] . "/A", $course_id));		
		for ($y = 0; $y <= count($courseIds); $y++) {
			// JATKA
			if($id_of_course[$x] == $course_id[$y] && $published[$y] == 1){
				$counter["total"] = $counter["total"]+1;
				
				$counter["total"] = $counter["total"]+1;
				echo "<tr>";
				echo "<td><a class='viewlink' href='index.php?page=student-view&id=" . $student_id[$y] . "'>Näytä</a><br />";
				echo "<a class='editlink' href='index.php?page=student-edit&id=" . $student_id[$y] . "'>Muokkaa</a></td>";
				echo "<td>" . $counter["total"] . "</td>";
				echo "<td>" . $title_of_course[$x] . "</td>";
				echo "<td>" . $email[$y] . "</td>";
				for ($z = 0; $z <= count($applicationid); $z++) {
					if($applicationid[$z] == $student_id[$y]){
						echo "<td>" . $field_value[$z] . "</td>";
					}
				}
				echo "</tr>";
			}
		}	
	} 
	echo "</table>";
	
	
	$mysqli->close();

?>

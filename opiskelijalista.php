<?php checkPermission(2);?>
<h3>Ilmoittautuneet opiskelijat</h3>

<?php
	
	// Include needed stuff
	include ('student-data-2-profile-transform-list.php');
	include('function-array-search-all.php');

	// Configuration HUOM! määrittele $minId tiedostossa student-data-2-profile-transform-list.php
	// $minId = 25; //tämän vuoden kurssien pienin id-arvo 

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

	$minApplicationId = min($student_id);
	
	//Haetaan ilmoittautumistiedot
	$sql = "SELECT * FROM `so_seminarman_fields_values` WHERE applicationid >= " . $minApplicationId;	
	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$applicationid[] = $row["applicationid"];
			$user_id[] = $row["user_id"];
			$field_id_appl[] = $row["field_id"];
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


	

	$needed = array_search_all($minApplicationId, $applicationid);

	//$needed_field_ids = array_search_all 
	echo '<p>Huom! Tämän taulukon tiedot on tuotu suoraan ilmoittautumisista eivätkä ole suoraan käytettävissä tulostuksissa. Opiskelijoiden tiedot saa automaattisiin tulostuksiin synkronoimalla kurssilaisten tiedot (Työkalut > Synkronoi kurssilaisten tiedot).</p>';?>
	<p style="margin:10px 0 0"><input type="button" onclick="tableToExcel('Tapahtuman_hallinta_lista')" value="Lataa tiedosto"></p>
	<?php 
	// Tulostetaan otsikot
	echo "<div class='table-container'>";
	echo "<table id='table' class='table'>";
	$counter["total"] = 0;
	foreach($alias_of_course as $value) {
		$counter[$value] = 0;
	}
	// Luodaan otsikkorivi
	echo '<thead>';
	echo '<tr>';
	echo '<th width="100px"></th>';
	echo '<th>Nro</th>';
	echo '<th>Kurssi</th>';
	echo '<th>Sähköpostiosoite</th>';
	echo '<th>Katuosoite</th>';	
	echo '<th>Postinumero</th>';
	echo '<th>Postitoimipaikka</th>';
	echo '<th>Maa</th>';
	echo '<th>Puhelinnumero</th>';
	echo '<th>Saapumispäivä</th>';
	echo '<th>Lähtöpäivä</th>';
	echo '<th>Syntymäaika</th>';
	echo '<th>Ruokailupaketti</th>';
	echo '<th>Erityisruokavalio</th>';
	echo '<th>Varaan koulumajoituksen (10€ / yö):
</th>';
	echo '<th>Taitotaso</th>';
	echo '<th>Soitin</th>';
	echo '<th>Vuokraan soittimen (hinta 5€/päivä):</th>';
	echo '<th>Varaus</th>';
	echo '<th>Toiveet kurssiin liittyen</th>';
	echo '<th>Kurssilaisen nimi</th>';
	echo '</thead>';
	echo '<tbody>';
	// Tulostetaan opiskelijalista
	for ($x = 0; $x <= count($id_of_course); $x++) {		
		for ($y = 0; $y <= count($course_id); $y++) {
			if($id_of_course[$x] == $course_id[$y] && $published[$y] == 1){
				$counter["total"] = $counter["total"]+1;
				echo "<tr>";
				echo "<td><a class='viewlink' href='index.php?page=student-view&id=" . $student_id[$y] . "'>Näytä</a><br />";
				echo "<a class='editlink' href='index.php?page=student-edit&id=" . $student_id[$y] . "'>Muokkaa</a></td>";
				echo "<td>" . $counter["total"] . "</td>";
				echo "<td>" . $title_of_course[$x] . "</td>";
				echo "<td>" . $email[$y] . "</td>";
				$needed = array_search_all($student_id[$y], $applicationid);
				for ($z = 0; $z < count($needed); $z++) {
					echo "<td>" . $field_value[$needed[$z]] . "</td>";
				}/*
				for ($z = 0; $z < count($applicationid); $z++) {
					if($applicationid[$z] == $student_id[$y]){
						echo "<td>" . $field_value[$z] . "</td>";
					}
				}*/
				echo "</tr>";
			}
		}	
	} 
	echo '</tbody>';
	echo "</table>";
	echo '</div>';
	
	
	$mysqli->close();

?>

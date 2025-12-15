<?php
	checkPermission(2);
	$id = $_GET['id'];

	// Haetaan hakemukset
	$sql = "SELECT id, first_name, last_name, pricegroup, price_per_attendee, price_total, comments, date, course_id, email, published FROM `so_seminarman_application` WHERE `id` = " . $id;	
	$result = $mysqli->query($sql);	
		if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$student_id = $row["id"];
			$first_name = $row["first_name"];
			$last_name = $row["last_name"];
			$pricegroup = $row["pricegroup"];
			$price_per_attendee = $row["price_per_attendee"];
			$price_total = $row["price_total"];
			$comments = $row["comments"];
			$date = $row["date"];
			$course_id[] = $row["course_id"];
			$email = $row["email"];
			$published[] = $row["published"];
		}
	} else {
		echo "tietoja taulusta so_seminarman_application ei saatu";
		die();
	}

	//Haetaan lisätiedot
	$sql = "SELECT * FROM `so_seminarman_fields_values` WHERE `applicationid` = " . $id;	
	$result = $mysqli->query($sql);	
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$applicationid[] = $row["applicationid"];
			$user_id[] = $row["user_id"];
			$field_id[] = $row["field_id"];
			$field_value[] = $row["value"];
		}
	} else {
		echo "0 results";
		die();
	}

	//Haetaan lisätiedojen otsikot
	$sql = "SELECT id, name FROM `so_seminarman_fields`";	
	$result = $mysqli->query($sql);	
	
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$field_label_id[] = $row["id"];
			$field_label_name[] = $row["name"];
		}
	} else {
		echo "0 results";
		die();
	}
	
	mysqli_close($mysqli);
?> 

<!--Tulosta lomake-->
<div id="info-view">
	<div class="tool-menu">
		<ul>
		<li><a href="index.php?page=student-edit&id=<?php echo $id; ?>">Muokkaa</a></li>
		<li><a onclick="goBack()">Palaa takaisin</a></li>
		</ul>
	</div>
	<div class="info-div">
		<p>Huotaja: <br /><span class="info"><?php echo $first_name; ?> <?php echo $last_name; ?></span></p>
		<p>Sähköpostiosoite: <br /><span class="info"><?php echo $email; ?></span></p> 

		<?php for ($x = 0; $x <= count($field_value); $x++) {
			$tmp = $field_id[$x];
			$tmp_field_id = array_search($tmp,$field_label_id); ?>
			<p><?php echo $field_label_name[$tmp_field_id]; ?>: <br /><span class="info"><?php echo $field_value[$x]; ?></span></p>
		<?php } ?>
	</div>
</div>

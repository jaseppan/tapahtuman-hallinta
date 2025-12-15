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
	
?> 

<!--Tulosta lomake-->


<form action="index.php?page=student-update&id=<?php echo $id ?>" method="POST">
	<div class="form-group">
		<div class="form-label">
			<label>Huotaja: </label>
		</div>
		<div class="form-field">
			<input type="text" name="first_name" value="<?php echo $first_name; ?>">	
			<input type="text" name="last_name" value="<?php echo $last_name; ?>">
		</div>
	</div>	
	<div class="form-group">
		<div class="form-label">
			<label>Sähköpostiosoite: </label> 
		</div>
		<div class="form-field">
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
	</div>
	<?php for ($x = 0; $x <= count($field_value); $x++) {
	$tmp = $field_id[$x];
	$tmp_field_id = array_search($tmp,$field_label_id); ?>
	<div class="form-group">
		<div class="form-label">
			<label><?php echo $field_label_name[$tmp_field_id]; ?>: </label>
		</div>
		<div class="form-field">
			<input type='text' name='<?php echo $field_label_id[$tmp_field_id];?>' value = '<?php echo $field_value[$x]; ?>'>
		</div>
	</div>
	<?php } ?>
	<input type="submit" name="submit" value="Tallenna">	
	<button onclick="goBack()">Palaa takaisin</button>

</form>



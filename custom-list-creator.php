<div id="search">
<?php
/******************** Tuodaan tiedot järjestelmässä olevista kentistä *************************/
checkPermission(1); 

$sql = "SELECT * FROM `so_ajaxregister_fields` WHERE `published` = 1 ORDER BY `ordering`";

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
	echo "Ei tuloksia KENTTÄ";
	die();
}

mysqli_close($mysqli);

/*************** Luodaan oikea näkymä sen mukaan mitä tietoja on saatu ******************/

if(!$_GET['submit'] && !$_GET['create']) {
	echo "<div class='search-form'><form action='' method='GET'";
	echo "<label>Rajattava piirre</label><br>";
	echo "<select name='profile-filter-field'>";
	$y = 0;
	for($x=0; $x < count($fields->label); $x++) {
		if($fields->type[$x]=="select" || $fields->type[$x]=="checkbox"  || $fields->type[$x]=="radios") {
			echo "<option value='" . $x . "'>" . filterText($fields->label[$x]) . ", id: " . $fields->id[$x] . "</option>";
			$y++;
		}
	}
	echo "<select>";
	echo "<input type='hidden' name='page' value='custom-list-creator'/>";
	echo "<input type='submit' value='Seuraava' name='submit'/>";
	echo "</form></div>";
} elseif (!$_POST['profile-filter']) {
	$featureId = $_GET['profile-filter-field'];
	$values = json_decode($fields->value[$featureId]);
	echo "<div class='search-form'><form action='' method='POST'";
	echo "<input type='hidden' name='page' value='custom-list-creator'/>";
	if($values == 1) {
		echo "<input type='hidden' name='profile-filter' value='1'/>";
	} else {
		echo "<label>Rajaava arvo</label><br>";
		echo "<select name='profile-filter'>";
		for($x=0; $x < count($values); $x++) {
			echo "<option value='" . filterText($values[$x]->value) . "'>" . filterText($values[$x]->text) . "</option>";
		}
		echo $featureId;
		echo "</select><br>";
	}
	echo "<br>Näytettävät kentät<br>";
	echo "<select multiple name='selected-columns[]'>";
	for($x=0; $x < count($fields->label); $x++) {
		echo "<option value='" . $fields->id[$x] . "'>" . filterText($fields->label[$x]) . "</option>";
	}
	echo "</select><br>";
	echo "<input type='hidden' name='profile-filter-field' value='" . $fields->id[$featureId] . "'/>";
	echo "<input type='hidden' name='profile-display' value='show'/>";
	echo "<input type='submit' value='Luo lista' name='create'/>";
	echo "</form></div>";
} if ($_POST['create']){
	$selectedColumns = implode('+', $_POST['selected-columns']);
	$link = 'http://' . $_SERVER['HTTP_HOST'] . '/tapahtuman-hallinta/index.php?page=user-list&profile-filter-field=' . $_POST['profile-filter-field'] . '&profile-filter=' . $_POST['profile-filter'] .  '&selected-columns=' . $selectedColumns . '&profile-display=show';
	echo "Linkki listaasi: <a href='" . $link . "'>" . $link . "</a>";
}

?>
</div>

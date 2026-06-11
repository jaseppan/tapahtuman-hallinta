<?php 
checkPermission(1); ?>
<div class="narrow">
<h1>Nimilappujen luonti</h1>
<?php
include('syncronation-msg.php');
include ("function-name-format-changer.php");

echo "<p><span style='color:red'>Huom!</span> Nimilappuun merkitään henkilön nimi ja tehtävä. Puuttuvan tehtävän voit käydä lisäämässä henkilön tiedoissa</p>";

/******************************* Valitaan ryhmä *******************************/
if(!$_POST){
	// Haetaan asemat
	$sql = "SELECT `value` FROM `so_ajaxregister_fields` WHERE id = 20";
	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$field_values[] = $row["value"];				
		}
	} else {
		echo "Ei tuloksia KENTTÄ";
		die();
	}
	$field_values = json_decode($field_values[0]);

	// Tulosta lomake
	echo '<p><b>Valitse ryhmä, joille luodaan nimikyltit</b></p>';
	echo '<form actio="" method="post">';
	echo '<p><select name="position">';
	echo '<option value="all">Kaikki</option>';
	foreach($field_values as $value){
		echo '<option value="' . $value->value . '">' . filterText($value->text) . '(' . $value->value . ')' . '</option>';
	}
	echo '</select></p>';
	echo '<input type="submit" name="submit" value="Seuraava">';
	echo '</form>';
	echo '</p>';
/******************************* Valitaan henkilöt *******************************/
} elseif($_POST['position'] || $_POST['person-view']){
	if($_POST['position']) {
		// Jos ryhmävalinnassa valittu kaikki, niin haetaan henkilöiden user_id:t	
		if($_POST['position']=='all') {
			$sql = "SELECT `id`, `name` FROM `so_users`";	
		// Muuten haetaan ryhmään kuuluvien henkilöiden user_id:t	
		} else {
		
			$sql = "SELECT user_id FROM `so_ajaxregister_field_values` WHERE `field_id` = 20 AND `value` = '" . $_POST['position'] . "'";
			$result = $mysqli->query($sql);	

			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$user_id[] = $row["user_id"];				
				}
			} else {
				echo "Ei tuloksia USER ID";
				die();
			}
			// Haetaan henklöiden nimet

			// HUOM! Järjestelmässä (todennäköisesti kurssilaisten synkronoinnissa) joku virhe jonka, takia sama tieto saattaa tallentua useamman kerran. Alla oleva rivi poistaa ylimääräiset. Korjaa virhe ja poista tämä rivi! 	
			$user_id = array_values(array_unique($user_id));

			$sql = "SELECT `id`, `name` FROM `so_users` WHERE `id` = " . $user_id[0];
			for($x = 1; $x < count($user_id); $x++) {
				$sql .= " OR `id` = " . $user_id[$x];
			}

		}

		$result = $mysqli->query($sql);	

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$name[$row["id"]] = $row["name"];
				if($_POST['position']=='all') {
					$user_id[] = $row["id"];			
				}				
			}
		} else {
			echo "Ei tuloksia USER ID";
			die();
		}

		// Haetaan henkilöiden tehtävä / kurssi
		if($_POST['position']=='all') {
			$sql = "SELECT `user_id`, `value` FROM `so_ajaxregister_field_values` WHERE `field_id` = 59";
		} else {
			$sql = "SELECT `user_id`, `value` FROM `so_ajaxregister_field_values` WHERE `user_id` = " . $user_id[0] . " AND `field_id` = 59";
		}
		for($x = 1; $x < count($user_id); $x++) {
			$sql .= " OR `user_id` = " . $user_id[$x] . " AND `field_id` = 59";
		}
		$result = $mysqli->query($sql);	

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$job[$row["user_id"]] = $row["value"];				
			}
		} else {
			// echo "Ei tuloksia USER JOB";
			//die(mysqli_error($mysqli));
		}

		// Pudotetaan joukosta piilotetut henkilöt ja listataan näyttävät henkilöt ja aakkostetaan
		include('hidden_persons.php');

		for($x = 0; $x < count($user_id); $x++){
			if( !empty($name[$user_id[$x]]) && isset($user_id[$x]) && !in_array($user_id[$x], $hidden_persons)) {
				$c_job = isset($job[$user_id[$x]]) ? $job[$user_id[$x]] : '';
				$person[] = nameFormatChanger($name[$user_id[$x]]) . "|" . $name[$user_id[$x]] . "|" . $c_job;
			}
		}
		sort($person);
	
		// Muodostetaan listat option value ja näyttöä varten
		for($x = 0; $x < count($person); $x++){
			$tmp = explode('|', $person[$x]);
			// Lisää kurssi näyttöön jos se on olemassa
			$display = $tmp[0];
			if(!empty($tmp[2])) {
				$display .= " (" . $tmp[2] . ")";
			}
			$personView[] = $display;
			$personPost[] = $tmp[1] . "|" . $tmp[2];
		}
	} else {
		$personView = unserialize($_POST['person-view']);		
		$personPost = unserialize($_POST['person-post']);

	}

	// Tulostataan henkilön valinta -lomake
		
	?>
	<h3>Valitse henkilöt joille tehdään nimilappu</h3>
	<form action="" method="post">
		<p>
		<input type="button" id="select_all" name="select_all" value="Valitse kaikki">
		<input type="button" id="deselect_all" name="deselect_all" value="Poista kaikki valinnat">	
		</p>
		<select name="persons[]" multiple size=50 style='height: 40%;' id='person-list'>
	
		<?php 
	
		if($_GET['selected']=='all') {
			$selected = 'selected';
		}
		for($x = 0; $x < count($personView); $x++){
			echo "<option value=\"" . $personPost[$x] . "\" " . $selected . ">" .  $personView[$x] . "</option>";
		}?>
		</select>
		<br><input type="radio" name="template" value="1" checked/>1-rivinen
		<input type="radio" name="template" value="2"/>2-rivinen
		<br><input type="submit" name="create-table" value="Seuraava">
	</form>

<?php } elseif($_POST['create-table']) {
	$i = 0;
	$fileName = "nimilaput-". date('Ymd', time()) . "-klo-" . date('G-i', time()). ".pdf";
	$docTitle = "NIMILAPUT";
	$background = 'background-image: url(\"nameplate-background-image.png\")';
	$table .= "<tr>";
	for($x = 0; $x < count($_POST['persons']); $x++){
		$tmp = explode("|", $_POST['persons'][$x]);
		if(!$tmp[1]){
			$tmp[1] = "&nbsp;";
		}
		if($_POST['template'] == 1) {

			$table .= "<td style=\"line-height: 28px; height: 197px;\"><span style=\"font-size: 26px; line-height: 8px; text-align: left;\">" . $tmp[0] . "</span><br>
			<span style=\"font-size: 20px; line-height: 28px; text-align: left; \">" . (strpos($tmp[1], '–') !== false ? substr($tmp[1], 0, strpos($tmp[1], '–')) : $tmp[1]) . "</span></p></td>";
			/* $table .= "<td style=\"line-height: 23px;\">

					<span style=\"font-size: 25px; text-align: right; line-height:20px; margin-top: 10px; margin-bottom:35px; padding-bottom:0;  \">" . $tmp[0] . "</span><br>
					<span style=\"font-size: 18px; text-align: right; line-height:20px; margin-top: 30px; \">" . $tmp[1] . "</span>
	
			</td>"; */
			
		} elseif ($_POST['template'] == 2) {
			$table .= "<td style=\"line-height: 28px; height: 197px;\"><span style=\"font-size: 26px; line-height: 30px !important; text-align: left; margin-bottom: 8px\">" . $tmp[0] . "</span><br><br><br>
			<span style=\"font-size: 20px; line-height: 20px; text-align: left; \">" . $tmp[1] . "</span></p></td>";	
					/* $table .= "<td style=\"line-height: 23px;\">template 2

							<span style=\"font-size: 25px; text-align: right; line-height:20px; margin-top: 10px; margin-bottom:35px; padding-bottom:0;  \">" . $tmp[0] . "</span><br>
							<span style=\"font-size: 18px; text-align: right; line-height:20px; margin-top: 30px; \">" . $tmp[1] . "</span>
			
					</td>"; */
		
		}
		if($i & 1) {
			$table .= "</tr><tr>";
		}
		$i++;
	}
	$table .= "</tr>";
		
	echo "<form action = 'pdf-templates/nameplate/pdf-creator.php' method = 'post' target='_blank'>";
		echo "<textarea name='table'>" . "<table style=\"padding:20 10 20;\">" .  $table . "</table>" . "</textarea>";
		echo '<input type="hidden" name="doc-title" value="' . $docTitle . '"/>';
		echo '<input type="hidden" name="file-name" value="' . $fileName . '"/>';
		echo "<br><input type='submit' name='submit' value ='Luo pdf'>";
	echo "</form>";
}
?>
</div>

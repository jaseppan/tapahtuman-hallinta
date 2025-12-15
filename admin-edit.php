<?php checkPermission(3); 
$privilegesOptions = array('Rajattu','Managerointi','Täydet oikeudet');
if(!$_POST) { 
	// Lomake ?>
	<form action="" method="POST">	
	<p>Nimi (Etunimi Sukunimi)<br><input type="text" name="name" value="<?php echo $name[0] ?>"></p>
	<p>Käyttäjänimi<br><input type="text" name="user-name" value="<?php echo $user_name[0] ?>"></p>
	<p>Salasana<br><input type="password" name="password"></p>
	<p>Oikeudet<br><select name="privileges">
		<?php
		for ($x = 0; $x < count($privilegesOptions); $x++) {
			$value = $x+1;
			if($value == $privileges[0]) {
				$html .= '<option value="' . $value . '" selected>' . $privilegesOptions[$x] . '</option>';
			} else {
				$html .= '<option value="' . $value . '">' . $privilegesOptions[$x] . '</option>';
			}
		}
		echo $html;
		?>
	</select>
	</p>
	<input type="submit" name="submit" value="Tallenna">
	<form>
<?php 
} else {
	$salt = "alkSj_GFr?i23489!hf";
	$name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
	$userName = mysqli_real_escape_string($mysqli, trim($_POST['user-name']));
	if(is_numeric($_POST['privileges'])) {
		$privileges = $_POST['privileges'];
	} else {
		die('Mitä yrität?'); 
		unset($_POST);
	}

	// Tarkistetaan onko käyttäjänimi jo käytössä
	$sql = "SELECT * FROM `so_js_event_manager_users` WHERE `user_name` = '" . $userName . "' AND `id` != " . $_GET['id'];
	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		echo "Käyttäjänimi on varattu";
	} else {
		$sql = "UPDATE `so_js_event_manager_users` SET `name` = '" . $name . "', `user_name` = '" . $userName . "', `privileges` = " . $privileges;

		if($_POST['password']) {
			$password = md5($_POST['password'].$salt);
			$sql .= ", `password` = '" . $password . "'";
		}
		$sql .= " WHERE id = " . $_GET['id'];
		
	}

	// Suorita kysely
	if ($mysqli->query($sql) === TRUE) {
		//echo "Admin-käyttäjän " . $userName . ", " . $name .  " tiedot päivitetty onnistuneesti";
	} else {
		//echo "Virhe tietojen tallentamisessa";
	}
	echo '<meta http-equiv="refresh" content="0; url=index.php?page=admin-manager" />';
}
?>


<?php checkPermission(3); ?>
<h3>Lisää tapahtuman hallinnan admin-käyttäjä</h3>
<?php
if($_POST) {
	foreach($_POST as $value) {
		if(!$value){
			$error = "true";
		}	
	}
}

if(!$_POST || $error) {
	$psw = bin2hex(openssl_random_pseudo_bytes(4));	
	?>
	<form action="" method="POST">	
	<p>Nimi (Etunimi Sukunimi)<br><input type="text" name="name"></p>
	<p>Käyttäjänimi<br><input type="text" name="user-name" value="" autocomplete="off"></p>
	<p>Salasana<br><input type="password" name="password" autocomplete="off" value="<?php echo $psw; ?>"> <?php echo $psw; ?></p>
	<p>Oikeudet<br><select name="privileges">
		<option value="1">Rajattu</option>
		<option value="2">Managerointi</option>
		<option value="3">Täydet oikeudet</option>
	</select>
	</p>
	<input type="submit" name="submit" value="Lisää käyttäjä">
	<form>
<?php
} else {
	$salt = "alkSj_GFr?i23489!hf";
	$name = mysqli_real_escape_string($mysqli, trim($_POST['name']));
	$userName = mysqli_real_escape_string($mysqli, trim($_POST['user-name']));
	$password = md5($_POST['password'].$salt);
	if(is_numeric($_POST['privileges'])) {
		$privileges = $_POST['privileges'];
	} else {
		die('Mitä yrität?'); 
		unset($_POST);
	}

	// Tarkistetaan onko käyttäjänimi jo käytössä
	$sql = "SELECT * FROM `so_js_event_manager_users` WHERE `user_name` = '" . $userName . "'";
	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		echo "Käyttäjänimi on varattu";
	} else {
		$sql = "INSERT INTO `so_js_event_manager_users` (`name`, `user_name`, `password`, `privileges`)
	 	VALUES ('" . $name . "', '" . $userName . "', '" . $password . "', " . $privileges . ")";

		if ($mysqli->query($sql) === TRUE) {
			echo "Admin-käyttäjä " . $userName . " henkilölle " . $name .  " tallennettu onnistuneesti";
		} else {
			echo "Virhe tietojen tallentamisessa";
		}
	} 
	echo '<meta http-equiv="refresh" content="0; url=index.php?page=admin-manager" />';
}

?>

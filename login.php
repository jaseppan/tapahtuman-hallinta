<?php

function loginForm() {
	$loginForm = '<html>
			<header>
			<meta charset="UTF-8">
			<title>Tapahtuman hallinta</title>
			<link rel="stylesheet" type="text/css" href="styles/style.css"></header>';
	$loginForm .= '<div id="search">
	<h1 style="text-align:center;">Kirjautuminen</h1>
	<div class="search-form">';

	$loginForm .= '<form action="" method="post">';
	$loginForm .= '<p>Käyttäjänimi <input type="text" name="user-name"></p>';
	$loginForm .= '<p>Salasana <input type="password" name="password"></p>';
	$loginForm .= '<p><input type="submit" name="login-submit" value="OK"></p>';
	$loginForm .= '</form>';
	$loginForm .= '</div></div></header></html>';
	return $loginForm;
}

// Jos ei ole tunnistauduttu
if( !isset($_SESSION['login']) || !$_SESSION['login'] || $_SESSION['login']!== 'tunnistauduttuOikein') {
	// jos ei kirjauduttu tai tunnukset väärät
	if(!$_POST) {
		echo loginForm();
		die();
	} elseif($_POST['user-name'] && $_POST['password']) {
		$salt = "alkSj_GFr?i23489!hf";
		$userName = mysqli_real_escape_string($mysqli, trim($_POST['user-name']));
		$password = md5($_POST['password'].$salt);
		$sql = "SELECT * FROM `so_js_event_manager_users` WHERE `user_name` = '" . $userName . "' AND `password` = '" . $password . "'"; 
		$result = $mysqli->query($sql);	

		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$_SESSION['name'] = $row["name"];
				$_SESSION['privileges'] = $row["privileges"];
				$_SESSION['admin_id'] = $row["id"];	
			}
			$_SESSION['login'] = 'tunnistauduttuOikein';
		} else {
			echo "<p>Virhe tunnistautumisessa<p>";
			echo loginForm();
			die();
		} 
	}
}

?>


<?php

// Jos sähköpostiosoitetta ei ole niin generoidaan käyttäjänimi seuraavalla funktiolla
function userNameGen($name, $email) {
	if($email=="-") {
		$userName = strtolower(str_replace(' ', '', $name)) . rand(0,9) . rand(0,9) . rand(0,9) . rand(0,9);
		$userName = preg_replace('/[^A-Za-z0-9\-]/', '', $userName);
	} else {
		$userName = $email;
	}
	return($userName);
}

?>

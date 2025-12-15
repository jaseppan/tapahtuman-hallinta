<?php
/* Tällä functiolla, saa AJAX Registerillä rekisteröityneiden select-, chekcbox- tai radio -valinnoista (value) tekstit (tekstit):
 * $value = $profile->value[array_search(%ID%, $profile->field_id);
 * $fields = $fields;
 * Kun data on haettu tiedostolla get-user-data.php
 */

function getProfileTextSimple($value, $options) {
	for($x = 0; $x < count($options); $x++) {
		if($value == $options[$x]->value) {
			$text = $options[$x]->text;
			return $text;
		}
	}
}
?>

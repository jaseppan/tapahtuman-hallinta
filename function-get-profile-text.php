<?php
/* Tällä functiolla, saa AJAX Registerillä rekisteröityneiden select-, chekcbox- tai radio -valinnoista (value) tekstit (tekstit):
 * $value = $profile->value[array_search(%ID%, $profile->field_id);
 * $fields = $fields;
 * Kun data on haettu tiedostolla get-user-data.php
 */

function getProfileText($value, $fields, $fieldId) {
	$options = json_decode($fields->value[$fieldId],true);
	if( !$options ) {
		return;
	}
	for($x = 0; $x < count($options); $x++) {
		if(array_search($value, $options[$x])) {
			$text = $options[$x]['text'];
		}
	}
	return isset( $text) ? $text : '';
}
?>

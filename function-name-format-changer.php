<?php
function nameFormatChanger($name) {
	$ind = strrpos($name, " ");
	$newName = substr($name, $ind) . ", " . substr($name,0,$ind);
	return $newName;
}
?>

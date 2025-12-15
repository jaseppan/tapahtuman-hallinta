<?php
// Handle Easy Language tags

function filterText($text) {
	$regex = "#{lang fi}(.*?){\/lang}#is";
	$text = preg_replace($regex,'$1', $text);
	$regex = "#{lang [^}]+}.*?{\/lang}#is";
	$text = preg_replace($regex,'', $text);
	return $text;
}
?>

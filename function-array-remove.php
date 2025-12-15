<?php
function array_remove(array $array, $value, $strict=false){ 
	$a_Keys = array_keys($array,$value, $strict); 
	foreach($a_Keys as $s_Key) { 
	    unset($array[$s_Key]); 
	} 
	$array = array_values($array); // re-index array. 
	return $array; 
}
?>

<?php
function dateNameTranslator($origDate) {
	$finDate = array('Mon'=>'Ma', 'Tue'=>'Ti', 'Wed'=>'Ke', 'Thu'=>'To','Fri'=>'Pe','Sat'=>'La','Sun'=>'Su');
	$date = $finDate[$origDate];
	
	return $date;	
}
?>

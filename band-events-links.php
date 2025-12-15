<?php
checkPermission(2);
include ("create-connection.php");

function currentBandEventLink($band, $linked_band_name, $linked_event_id){
	$current_band_link_ids = array_keys($linked_band_name, $band);
	// Yhtyeeseen kytketyt tapahtumat muuttujaan $band_event_link	
	foreach($current_band_link_ids as $current_band_link_id) {
		$current_band_event_link[] = $linked_event_id[$current_band_link_id];
	}
	return $current_band_event_link;
}

/* Tuodaan tarvittava data: 
 - Ajax Registerin fieldit
 - JEventsin tapahtumatiedot
 - Yhtye - tapahtumalinkit
*/

// Tuodaan yhtyeet arrayhyn $band

$sql = "SELECT * FROM `so_ajaxregister_fields` WHERE `id` = 23";

$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$bands_values[] = $row["value"];
	}
} else {
	echo "Ei tuloksia KENTTÄ";
	die();
}

foreach($bands_values as $value) {
	$tmp[] = json_decode($value,true);	
}

for($x = 0; $x < count($tmp[0]); $x++) {
	$band[] = $tmp[0][$x]; 
}

// Tuodaan tapahtumat 

$sql = "SELECT * FROM `so_jevents_vevdetail` ORDER BY `dtstart`";

$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$event_id[] = $row["evdet_id"];
		$event_name[] = $row["summary"];
		$event_time[] = $row["dtstart"];
		$event_location = $row["location"];
	}
} else {
	echo "Ei tuloksia KENTTÄ";
	die();
}

// Valitaan tämän ja tulevien vuosien tapahtumat
for($x = 0; $x < count($event_time); $x++) {
	$event_year = strftime("%Y",$event_time[$x]);
	if($event_year == date("Y")) {
		$actual_events_id[] = $x;	
	}
}


// Tuodaan määritellyt linkit

$sql = "SELECT * FROM `so_js_event_manager_band_event_link`";

$result = $mysqli->query($sql);	

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$link_event_id[] = $row["event_id"];
		$link_band_name[] = $row["band_name"];
	}
}

// Luodaan lomake
?>
<p><a href="http://www.sommelo.net/administrator/index.php?option=com_ajaxregister&view=field&layout=edit&id=23">Lisää/poista/muokkaa esiintyjiä</a></p>
<h3>Esiintyjä - tapahtuma -linkit</h3>
	<div style="position:fixed; left:20px;">
	<?php for($x = 0; $x < count($band); $x++) { ?>
		<a href="#<?php echo $band[$x]['value']; ?>"><?php echo $band[$x]['text']; ?></a><br>		
	<?php } ?>	
	</div>
	<div style="width:550px; margin-left:auto; margin-right:auto">
	<p>Valitse tapahtumat joissa yhtyeet/esiintyjät esiintyvät.</p>
	<form action="index.php?page=band-event-links-create" method="POST">
	<?php for($x = 0; $x < count($band); $x++) { ?>
		<?php $selected = currentBandEventLink($band[$x]['value'], $link_band_name, $link_event_id); ?>
		<label id="<?php echo $band[$x]['value']; ?>"><b><?php echo $band[$x]['text']; ?></b></label><br>
		<select name="<?php echo $band[$x]['value']; ?>[]" multiple size="<?php echo round(count($actual_events_id)/2) ?>">
		<?php for($y = 0; $y < count($actual_events_id); $y++) { 
			if(in_array($event_id[$actual_events_id[$y]], $selected)){
				$selectedValue = "selected";
			} else {
				$selectedValue = "";
			}?>
		  	<option value="<?php echo $event_id[$actual_events_id[$y]]; ?>" <?php echo $selectedValue; ?>><?php echo $event_name[$actual_events_id[$y]]; ?></option>
		<?php } ?>
		</select><br>
		<hr>
	<?php } ?>
	</div>
	<div id="submit-button"><input type="submit" value="Tallenna muutokset" value="submit"></div>
</form>


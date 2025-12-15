<?php 
checkPermission(1);
/* Tämä skripti tuottaa käyttäjän tarkastelu näkymään (user-view) listan muista samassa asemassa olevista henkilöistä */

// TEE OMA MYSQL-HAKU
require ("get-name-list-data.php");
require ("function-name-format-changer.php");

if($noPosition){
	echo "<h3 style='color:red;'>Seuraavien henkilöiden asema pitää määritellä:</h3>";

	echo "<div id='nameListMenu'>";
		for ($x = 0; $x <= count($noPosition->name); $x++) {
			if( isset($noPosition->name[$x]) )
				$nameItems[] = "<tr><td>" . nameFormatChanger($noPosition->name[$x]) . "</td><td><a class='viewlink-small' href='index.php?page=user-view&id=" . $noPosition->id[$x] . "'>Näytä</a> | <a class='editlink-small' href='index.php?page=user-edit&id=" . $noPosition->id[$x] . "'>Muokkaa</a></td></tr>";
		} 

		sort($nameItems);

		echo "<table>";
		for ($x = 0; $x <= count($noPosition->name)-1; $x++) {	
			echo $nameItems[$x];
		} 
		echo "</table>";
	echo "</div>";
}


	
echo "<h2>SAMIKSET</h2>";
if($positionTitle){
	echo "<h3>" . filterText($positionTitle) . "</h3>";
} else {
	echo "<h3>Asemaa ei määritelty</h3>";
}

if( isset($otherUsers) ) {
	echo "<div id='nameListMenu'>";
		for ($x = 0; $x <= count($otherUsers->name); $x++) {
			if( isset($otherUsers->name[$x]) )
				$nameItems[] = "<tr><td>" . nameFormatChanger($otherUsers->name[$x]) . "</td><td><a class='viewlink-small' href='index.php?page=user-view&id=" . $otherUsers->id[$x] . "'>Näytä</a> | <a class='editlink-small' href='index.php?page=user-edit&id=" . $otherUsers->id[$x] . "'>Muokkaa</a></td></tr>";
		} 
	
		sort($nameItems);
	
		echo "<table>";
		for ($x = 0; $x <= count($otherUsers->name)-1; $x++) {	
			echo $nameItems[$x];
		} 
		echo "</table>";
	echo "</div>";
}


if(isset($boarder) && $boarder){
	echo "<h3>Majoittujat</h3>";
	echo "<div id='nameListMenu'>";
		for ($x = 0; $x <= count($boarder->name); $x++) {
			$nameItemsBoarder[] = "<tr><td>" . nameFormatChanger($boarder->name[$x]) . "</td><td><a class='viewlink-small' href='index.php?page=user-view&id=" . $boarder->id[$x] . "'>Näytä</a> | <a class='editlink-small' href='index.php?page=user-edit&id=" . $boarder->id[$x] . "'>Muokkaa</a></td></tr>";
		} 

		sort($nameItems);

		echo "<table>";
		for ($x = 0; $x <= count($boarder->name)-1; $x++) {	
			echo $nameItemsBoarder[$x];
		} 
		echo "</table>";
	echo "</div>";
}

if(isset($diner) && $diner){
	echo "<h3>Ruokailijat</h3>";
	echo "<div id='nameListMenu'>";
		for ($x = 0; $x <= count($diner->name); $x++) {
			$nameItemsDiner[] = "<tr><td>" . nameFormatChanger($diner->name[$x]) . "</td><td><a class='viewlink-small' href='index.php?page=user-view&id=" . $diner->id[$x] . "'>Näytä</a> | <a class='editlink-small' href='index.php?page=user-edit&id=" . $diner->id[$x] . "'>Muokkaa</a></td></tr>";
		} 

		sort($nameItems);

		echo "<table>";
		for ($x = 0; $x < count($diner->name); $x++) {	
			echo $nameItemsDiner[$x];
		} 
		echo "</table>";
	echo "</div>";
}

if(isset($vienaJourneyParticipant) && $vienaJourneyParticipant){
	echo "<h3>Vienaan menijät</h3>";
	echo "<div id='nameListMenu'>";
		for ($x = 0; $x < count($vienaJourneyParticipant->name); $x++) {
			$nameItemsViena[] = "<tr><td>" . nameFormatChanger($vienaJourneyParticipant->name[$x]) . "</td><td><a class='viewlink-small' href='index.php?page=user-view&id=" . $vienaJourneyParticipant->id[$x] . "'>Näytä</a> | <a class='editlink-small' href='index.php?page=user-edit&id=" . $vienaJourneyParticipant->id[$x] . "'>Muokkaa</a></td></tr>";
		} 

		sort($nameItems);

		echo "<table>";
		for ($x = 0; $x < count($nameItemsViena); $x++) {	
			echo $nameItemsViena[$x];
		} 
		echo "</table>";
	echo "</div>";
}

?>

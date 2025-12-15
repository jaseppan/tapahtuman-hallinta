<?php
	checkPermission(2);
	$id = $_GET['id'];
	$mysqliClosing = "false";
	require ("get-user-data.php");
	include('editing-tracking.php');
	require_once('function-input-writer.php');
	include ('student-data-2-profile-transform-list.php');
	$fields->value['23'] = $allMealValues;

?> 


<!--Tulosta lomake-->
<div id="info-view">
	<div class="tool-menu">
		<ul>
		<li><a href="index.php?page=user-view&id=<?php echo $id; ?>">Näytä</a></li>
		<li><a onclick="goBack()">Palaa takaisin</a></li>
		</ul>
	</div>
	<div class="info-div">
	<?php $action = "index.php?page=user-update&id=" . $_GET['id']; ?>
	<form action="<?php echo $action ?>" method="POST"> 
		<p>Nimi: <br /><span class="info"><input type="text" name = "name" value="<?php echo $user->name[0];?>" /></span></p>
		<p>Sähköpostiosoite: <br /><input type="text" name = "email" value="<?php echo $user->email[0];?>" /></p> 

		<?php for ($x = 0; $x < count($fields->id); $x++) {
			$tmp = $fields->id[$x];
			if($tmp==15) {
				echo '<div id="flip-1">YHTEYS- JA HENKILÖTIEDOT</div>';
				echo '<div id="panel-1">';
			}
			if($tmp==28) {
				echo '<div id="flip-2">MAJOITUSTIEDOT</div>';
				echo '<div id="panel-2">';
			}
			if($tmp==33) {
				echo "<pre>";
				echo "</pre>";
				echo '<div id="flip-3">RUOKAILUTIEDOT</div>';
				echo '<div id="panel-3">';
				echo '<p style="color: green;">Huom! "Jos Ruokailen Sommelon ruokalassa" kohdassa on valittuna "Kyllä" ja ellei yksittäisiä aterioita ole valittu, niin henkilö merkitään kaikille aterioille ajalla saapumispäivä - lähtöpäivä.</p>';
			}
			if($tmp==35) {
				echo '<div id="flip-4">PALKKIO- JA PANKKITIEDOT</div>';
				echo '<div id="panel-4">';
			}
			if($tmp==45) {
				echo '<div id="flip-5">VIENAN MATKA -TIEDOT</div>';
				echo '<div id="panel-5">';
			}
			if($tmp==50) {
				echo '<div id="flip-6">MUUT</div>';
				echo '<div id="panel-6">';
			}
			$profile_id = array_search($tmp,$profile->field_id);
			if ($profile->value[$profile_id] && $profile->field_id[$profile_id]==$tmp) {
				$fieldValue = $profile->value[$profile_id];
			}
			inputWriter($x);
			if($tmp==62 || $tmp==51 || $tmp==34 || $tmp==41 || $tmp==49 || $tmp==63) {
				echo '</div>';
			}		
			unset($fieldValue);
		} ?>
		<div id="submit-button"><input type="submit" value="Tallenna" value="submit"></div>	
	</form>
	</div>
</div>

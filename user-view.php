<?php
	checkPermission(1);
	require ("get-user-data.php");
	if (!is_numeric($_GET['id'])){
		die("Parametri virheellisessä muodossa");
	} else {
		$id = $_GET['id'];
	}

?> 

<!--Tulosta tiedot-->
<div id="info-view">
	<div class="tool-menu">
		<ul>
		<?php 
		if($_SESSION['privileges'] > 1) {
		?>
			<li><a href="index.php?page=user-edit&id=<?php echo $id; ?>">Muokkaa</a></li>
			<li><a href="index.php?page=contract-creator-form&id=<?php echo $id; ?>">Luo sopimus</a></li>
		<?php
		}
		?>
		<li><a onclick="goBack()">Palaa takaisin</a></li>
		</ul>
	</div>
	<div class="info-div">
		<p>Nimi: <br /><span class="info"><?php echo $user->name[0];?></span></p>
		<p>Sähköpostiosoite: <br /><span class="info"><?php echo $user->email[0];?></span></p> 
		<?php for ($x = 0; $x < count($fields->id); $x++) {
			$tmp = $fields->id[$x];
			if($_SESSION['privileges']>1) {
				if($tmp==15) {
					echo '<div id="flip-1">YHTEYS- JA HENKILÖTIEDOT</div>';
					echo '<div id="panel-1">';
				}
				if($tmp==28) {
					echo '<div id="flip-2">MAJOITUSTIEDOT</div>';
					echo '<div id="panel-2">';
				}
				if($tmp==33) {
					echo '<div id="flip-3">RUOKAILUTIEDOT</div>';
					echo '<div id="panel-3">';
					echo '<p>Huom! "Jos Ruokailen Sommelon ruokalassa" kohdassa on valittuna "Kyllä" ja ellei yksittäisiä aterioita ole valittu, niin henkilö merkitään kaikille aterioille ajalla saapumispäivä - lähtöpäivä.</p>';
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
			}
			
			$profile_id = array_search($tmp,$profile->field_id);
			?>
				<p><?php echo filterText($fields->label[$x]); ?>: <br />
				<?php if ($profile->value[$profile_id] && $profile->field_id[$profile_id]==$tmp) { ?><span class="info"><?php echo $profile->value[$profile_id]; ?></span><?php } else {?><span class="warning"><i>tieto puuttuu</i></span></p>
			<?php } 
			if($_SESSION['privileges']>1) {
				if($tmp==62 || $tmp==51 || $tmp==34 || $tmp==41 || $tmp==49 || $tmp==63) {
					echo '</div>';
				}
			}
		} ?>
		</div>
	</div>
	<div class="name-list">
		<?php include("name-list.php"); ?>
	</div>

</div>
	

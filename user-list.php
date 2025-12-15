<?php 
checkPermission(1);
require ("get-user-data.php");
include_once ('function-name-format-changer.php');

// Haetaan profile-filter-field:in otsikko
if(isset($_GET['profile-filter-field']) && $_GET['profile-filter-field']){
	$sql = "SELECT label FROM `so_ajaxregister_fields` WHERE `id` = " . $_GET['profile-filter-field'];
	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$countReportFieldLabel[] = $row["label"];
		}
	} else {
		echo "Ei tuloksia KENTTÄ";
		die();
	}
}
?>

<div id="list-menu">

<!-- PARAMETRIN KORVAUSTA VARTEN:
$params = $_GET;
$params['sd'] = "whateveryoulike";
$paramString = http_build_query($params);
-->

	<ul>
	<?php if(!isset($_GET['profile-display']) || !$_GET['profile-display']) { ?>
		<li><a href="<?php echo $_SERVER['REQUEST_URI']; ?>&profile-display=show">Näytä profiili</a></li>
	<?php } else { 
		$nurl = removeqsvar($_SERVER['REQUEST_URI'], 'profile-display'); ?>
		<li><a href="<?php echo $nurl ?>">Piilota profiili</a></li>
	<?php } ?>
	<li> | </li>
	<?php if(!isset($_GET['flip-name']) || !$_GET['flip-name']) { ?>
		<li><a href="<?php echo $_SERVER['REQUEST_URI']; ?>&flip-name=true">Käännä nimet</a></li>
	<?php } else { 
		$nurl = removeqsvar($_SERVER['REQUEST_URI'], 'flip-name'); ?>
		<li><a href="<?php echo $nurl ?>">Käännä nimet</a></li>
	<?php } ?>
	</ul>
	<p style="margin:10px 0 0"><input type="button" onclick="tableToExcel('Tapahtuman_hallinta_lista')" value="Lataa tiedosto"></p>
</div>

<?php

$personCount = "<span class='count-report'>Tietokannassa on <b>" . count($user->name) . "</b> henkilöä";
if(isset($_GET['profile-filter']) && $_GET['profile-filter']) {
	$personCount .= " suodatettuna ";
	if(isset($_GET['profile-filter-field']) && $_GET['profile-filter-field']) {
		$personCount .= "parametrin: <b>" . filterText($countReportFieldLabel[0]) . "</b>";
	}
	$personCount .= " arvolla <b>" . $_GET['profile-filter'] . "</b>";
} else {
	$personCount .= " kaikkiaan."; 
}
$personCount .= "</span>";

echo $personCount;

/*************** Funktiot *****************/

// Profiilisolut luodaan tällä funktiolla
function showProfile($fields_id, $fields_label, $profile_field_id, $profile_value, $user_name) {
	for ($x = 0; $x < count($fields_id); $x++) {
		$tmp = $fields_id[$x]; ?>
		<td class="ht">
		<?php if( is_array($profile_field_id) ) {

			$profile_id = array_search($tmp,$profile_field_id);
			?>
			<?php if(array_search($tmp,$profile_field_id) !== false || !$profile_field_id) { ?>
				<span class="info"><?php echo isset($profile_value[$profile_id]) ? $profile_value[$profile_id] : '';?>
				
			<?php } ?>
		<?php } ?>
		</td>
	<?php } 
	unset($profile_id);
}

// Parametrin poistaminen urlista
function removeqsvar($url, $varname) {
    return preg_replace('/([?&])'.$varname.'=[^&]+(&|$)/','$1',$url);
}	

// Taulukon luominen

$fields_id = $fields->id; // kenttien id:t
$fields_label = $fields->label;	// kenttien labelit?>

<div class="table-container">
<table id="table" class="table">

<!-- Luodaan otsikkorivi -->
<thead> 
<tr>
<th width="100px"></th>
<th>Nimi</th>
<th>Sähköposti</th>
<?php
if (isset($_GET['profile-display']) && $_GET['profile-display']) {
	for ($x = 0; $x < count($fields->id); $x++) { ?>
		<th><?php echo filterText($fields->label[$x]); ?></th>
	<?php }
} ?>
</tr>
</thead> 
<tbody> 
<?php for ($x = 0; $x <= count($user->name)-1; $x++) {

	// ShowProfile -funktioon tarvittavia muuttujia
	$profileIds = array_keys(preg_grep("/" . $user->id[$x] . "/A", $profile->user_id)); // KÄYTTÄJÄN PROFIILITIETOJEN ID:T 

	$profile_field_id = [];
	$profile_value = [];

	foreach ($profileIds as $value) {
		$profile_field_id[] = $profile->field_id[$value]; 
		$profile_value[] = $profile->value[$value];
	}		
	echo "<tr>";

	// Linkit tietojen hallintaan ja editointiin
		
	?> 
	<!-- Käyttäjän perustiedot -->
	<td>
		<?php 
		echo "<a class='viewlink' href='index.php?page=user-view&id=" . $user->id[$x] . "'>Näytä</a>";
		if($_SESSION['privileges']>1) {
			echo "|<a class='editlink' href='index.php?page=user-edit&id=" . $user->id[$x] . "'>Muokkaa</a>
			|<a class='deletelink' href='index.php?page=user-delete&id=" . $user->id[$x] . "'>Poista</a></span>";
			
		}
		?>
	</td>
	<!--<td class="ht">-->
	<td>	
		<?php
		if(isset($_GET['flip-name']) && $_GET['flip-name']=='true') {
			echo nameFormatChanger($user->name[$x]);
		} else {
			echo $user->name[$x];
		}
		/*echo "<span class='tooltip'>" . $user->name[$x] . "<br>";
		echo "<a class='viewlink' href='index.php?page=user-view&id=" . $user->id[$x] . "'>Näytä</a><br />";
		if($_SESSION['privileges']>1) {
			echo "<a class='editlink' href='index.php?page=user-edit&id=" . $user->id[$x] . "'>Muokkaa</a></span>";
		}*/
		?>		
	</td>
	<td><?php echo "<a href='mailto:'" . $user->email[$x] . "'>" . $user->email[$x] . "</a></td>";?>

	<!-- Näytetään profiili jos niin valittu -->
	<?php if(isset($_GET['profile-display']) && $_GET['profile-display'] == 'show') {

		showProfile($fields_id, $fields_label, $profile_field_id, $profile_value, $user->name[$x]);
	}

	// Poistetaan kierrosta varten luodut muuttujat
	unset($profile_field_id);
	unset($profile_value);

} ?>
</tbody>
</table>
</div>

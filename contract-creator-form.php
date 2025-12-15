<?php 
checkPermission(2);
$id = $_GET['id'];

require ("get-user-data.php");
require ("contract-creator-form-configuration.php");

// MÄÄRITTELE TARVITTAVAT PROFIILIARVOJEN ID:T contract-creator-form-configuration.php:SSÄ!!

$ind = array_search(20, $profile->field_id);
if(in_array(20, $profile->field_id)) {
	$position_value = $profile->value[$ind];
}else{
	die("Henkilön " . $user->name[0] . " asemaa ei ole määritelty. Sopimusta ei voida luoda. Valitse \"Muokkaa\" vasemmalla olevasta valikosta ja määrittele henkilön asema");	
}
unset($ind);

$ind = array_search(15, $profile->field_id);
if(in_array(15, $profile->field_id))  {
	$address = $profile->value[$ind];
}else{
	$address = "LÄHIOSOITE";
	$missingInfo .= "- Lähiosoite<br>";
}
unset($ind);

$ind = array_search(16, $profile->field_id);
if(in_array(16, $profile->field_id))  {
	$zipCode = $profile->value[$ind];
}else{
	$zipCode = "POSTINUMERO";
	$missingInfo .= "- Postinumero<br>";
}
unset($ind);

$ind = array_search(17, $profile->field_id);
if(in_array(17, $profile->field_id))  {
	$city = $profile->value[$ind];
}else{
	$city = "POSTITOIMIPAIKKA";
	$missingInfo .= "- Postitoimipaikka<br>";
}
unset($ind);

$ind = array_search(18, $profile->field_id);
if(in_array(18, $profile->field_id))  {
	$phone = $profile->value[$ind];
}else{
	$phone = "PUHELINNUMERO";
	$missingInfo .= "- Puhelinnumero <br>";
}
unset($ind);

$ind = array_search(32, $profile->field_id);
if(in_array(32, $profile->field_id))  {
	$dateOfArrival = $profile->value[$ind];
}else{
	$dateOfArrival = "SAAPUMIS-/SOPIMUKSENALKAMISPÄIVÄ";
	$missingInfo .= "- Saapumispäivä<br>";
}
unset($ind);

$ind = array_search(31, $profile->field_id);
if(in_array(31, $profile->field_id))  {
	$dateOfDeparture = $profile->value[$ind];	
}else{
	$dateOfDeparture .= "POISTUMIS-/SOPIMUKSENPÄÄTTYMISPÄIVÄ";
	$missingInfo .= "- Poistumispäivä<br>";
}
unset($ind);

$ind = array_search(59, $profile->field_id);
if(in_array(59, $profile->field_id))  {
	$task = $profile->value[$ind];
} else {
	$task = "TEHTÄVÄ";
	$missingInfo .= "- Tehtävä<br>";
}
unset($ind);

$ind = array_search(28, $profile->field_id);
if(in_array(28, $profile->field_id))  {
	$needAccommodation = $profile->value[$ind];
} else {
	$needAccommodation = "";
	$missingInfo .= "- Maijoitustarve<br>";
}
unset($ind);

$ind = array_search(35, $profile->field_id);
if(in_array(35, $profile->field_id)) {
	$feeMethod = $profile->value[$ind];
} else {
	$feeMethod = "";
	$missingInfo .= "- Palkkion maksu<br>";
}
unset($ind);

if(isset($_POST['fee']) && $_POST['fee']) { 
	$fee = $_POST['fee'];
} else {
	$fee = "PALKKIO";
}

if(isset($_POST['idNum']) && $_POST['idNum']) { 
	$idNum = $_POST['idNum'];
} else {
	$idNum = "Henkilötunnus";	
}	
	
if($feeMethod=="verokortti") {
	$ind = array_search(36, $profile->field_id);
	if($ind) {
		$bank = $profile->value[$ind];
	} else {
		$bank = "PANKKI";
		$missingInfo .= "- Pankki<br>";
	}
	unset($ind);


	$ind = array_search(39, $profile->field_id);
	if(in_array(39, $profile->field_id))  {
		$taxMun = $profile->value[$ind];
	} else {
		$taxMun = "VEROTUSKUNTA";
		$missingInfo .= "- Verotuskunta<br>";
	}
	unset($ind);

	$ind = array_search(40, $profile->field_id);
	if(in_array(40, $profile->field_id))  {
		$swift = $profile->value[$ind];
	} else {
		$swift = "SWIFT";
		$missingInfo .= "- SWIFT<br>";
	}
	unset($ind);


	$ind = array_search(38, $profile->field_id);
	if(in_array(38, $profile->field_id))  {
		$iban = $profile->value[$ind];
	} else {
		$iban = "IBAN";
		$missingInfo .= "- IBAN<br>";
	}
	unset($ind);
}


if(isset($missingInfo) && $missingInfo) {
	echo "<div class = 'redNotification'><p>Henkilöstä " . $user->name[0] . " on puuttuvia tietoja:</p><p>" . $missingInfo . "</p><p>Ole hyvä ja täydennä puuttuvat tiedot, jotta "  . $user->name[0] . " huomioidaan listauksissa. Erityisen tärkeitä ovat yhteys-, majoitus, ruokailu ja mahdolliseen Vienan matkaan liittyvät tiedot.<p></div>";
}

//echo $phone; var_dump($profile);

function preForm($idNum) {	
	$preForm = "<form action='' method='POST'>";
	$preForm .= "<label>Palkkio</label><br><input type='text' name='fee'/><br>";
	if($idNum=="true") {
		$preForm .= "<label>Henkilötunnus</label><br><input type='text' name='idNum'/><br>";
	}
	$preForm .= "<input type='submit' name='submit' value='Seuraava'>";
	$preForm .= "</form>";
	
	return $preForm;
} 
?>


<!--Tulosta lomake-->
<div id="info-view">
	<div class="tool-menu">
		<ul>
		<li><a href="index.php?page=user-view&id=<?php echo $id; ?>">Näytä</a></li>
		<li><a href="index.php?page=user-edit&id=<?php echo $id; ?>">Muokkaa</a></li>
		<li><a onclick="goBack()">Palaa takaisin</a></li>
		</ul>
	</div>
	<div class="info-div">	
		<h2>Täydennettävä sopimuslomale</h2>
		<p><a href="index.php?page=help#1_1">OHJEET</a></p>
		<?php echo $position_value; ?>
		<?php switch ($position_value) {
			case "henkilokunta":
				// LISÄÄ TÄHÄN PUUTTUVIEN TIETOJEN TSEKKAUS
				//if (!$_POST['fee'] || !$_POST['idNum']) {
				//	echo preForm('true');
				//} else {	
					include("pdf-templates/contract-staff/form.php");
				//}
			break;
			case "taiteilija":
				// LISÄÄ TÄHÄN PUUTTUVIEN TIETOJEN TSEKKAUS SEKÄ preForm
				//if($feeMethod=="verokortti" && !$_POST['fee'] || $feeMethod=="verokortti" && !$_POST['idNum']) {
				//	echo preForm('true');
				//} elseif ($feeMethod=="lasku" && !$_POST['fee']){
				//	echo preForm('false');
				//} else {	
					require("pdf-templates/contract-artist/get-conserts.php");
					require("pdf-templates/contract-artist/form.php");
				//}
			break;
			case "opettaja":
				// LISÄÄ TÄHÄN PUUTTUVIEN TIETOJEN TSEKKAUS SEKÄ preForm
				//if($feeMethod=="verokortti" && !$_POST['fee'] || $feeMethod=="verokortti" && !$_POST['idNum']) {
				//	echo preForm('true');
				//} elseif ($feeMethod=="lasku" && !$_POST['fee']){
				//	echo preForm('false');
				//} else {	
					// require("pdf-templates/contract-teacher/get-conserts.php");
					require("pdf-templates/contract-teacher/form.php");
				//}
			break;
			case "alustaja":
				// LISÄÄ TÄHÄN PUUTTUVIEN TIETOJEN TSEKKAUS SEKÄ preForm
				if($feeMethod=="verokortti" && !$_POST['fee'] || $feeMethod=="verokortti" && !$_POST['idNum']) {
					echo preForm('true');
				} elseif ($feeMethod=="lasku" && !$_POST['fee']){
					echo preForm('false');
				} else {	
					//require("pdf-templates/contract-artist/get-conserts.php");
					require("pdf-templates/contract-seminar-speaker/form.php");
				}
			}	
		?>

	</div>
</div>

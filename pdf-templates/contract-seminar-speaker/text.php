<?php
$position = "Alustaja";
$title = "ESIINTYMISSOPIMUS";

$x = 0;
$text[$x]['text'] = "Tämän esiintymissopimuksen sopijaosapuolet ovat " . $user->name[0] . " ja
Runolaulu-Akatemia (jäljempänä järjestäjä).";
$text[$x]['type'] = "textarea";

$x++;
$text[$x]['label'] = $x . ". Yhteystiedot";
if($feeMethod=="verokortti") {
	$text[$x]['text'] = "<p><b>Alustaja</b></p>";
	$text[$x]['text'] .= "<table>
	<tr><td colspan='2'>Nimi: " . $user->name[0] . "</td></tr>
	<tr><td colspan='2'>Osoite: " . $address . ", " . $zipCode . " " . $city . "</td></tr>
	<tr><td>Ammatti: Muusikko</td><td>Henkilötunnus: " . $idNum . "</td></tr>
	<tr><td>Pankin nimi: " . $bank . "</td><td>Verotuskunta: " . $taxMun . "</td></tr>
	<tr><td>SWIFT (BIC): " . $swift . "</td><td>IBAN: " . $iban . "</td></tr>
	</table>";
	$text[$x]['text'] .= "<p><b>Runolaulu-Akatemia</b><p>";	
	$text[$x]['text'] .= "<table>
	<tr>
	<td colspan='2'>Osoite: Pro Sommelo ry, Kainuuntie 99, 88900 Kuhmo</td></tr>
	<tr><td>Puhelinnumero: 044-250 1396</td><td>Sähköposti: sommelo@runolaulu.fi</td>
	</tr></table>";
	$text[$x]['type'] = "textarea";
} elseif($feeMethod=="lasku") {
	$text[$x]['text'] = "<p><b>Alustaja</b></p>
	<table>
	<tr><td colspan='2'>Nimi: " . $user->name[0] . "</td></tr>
	<tr><td colspan='2'>Osoite: " . $address . ", " . $zipCode . " " . $city . "</td></tr>
	<tr><td>Sähköposti: " . $user->email . "</td><td>Puhelinnumero: " . $phone . "</td></tr>
	</table>";	
	$text[$x]['text'] .= "<p><b>Runolaulu-Akatemia</b><p>
	<table><tr>
	<td colspan='2'>Laskutussoite: Pro Sommelo ry, Kainuuntie 99, 88900 Kuhmo</td></tr>
	<tr><td>Puhelinnumero: 044-250 1396</td><td>Sähköposti: sommelo@runolaulu.fi</td>
	</tr></table>";
	$text[$x]['type'] = "textarea";
}

$x++;
$text[$x]['label'] = $x . ". Sopimuksen tarkoitus ja sisältö";
$text[$x]['text'] = "<p>Tämä sopimus koskee alustusta Runolaulu-Akatemian järjestämässä seminaarissa. Jäljempänä sopimuksessa
siihen viitataan sanalla alustus:<br>[SEMINAARIN NIMI JA PÄIVÄMÄÄRÄT]</p><p>$eventText</p>";
$text[$x]['type'] = "textarea";

require $path . '/pdf-templates/general-texts/terms.php';
require $path . '/function-get-profile-text.php';
$bandName = getProfileText($band, $fields, 1);

$date['employer']['label'] = "Aika ja paikka (työnantaja)";
$date['employer']['text'] = date("j.n.Y") . ", Kuhmo";
$date['employer']['type'] = "text";
$date['employer']['name'] = "date_loc_employer";

$date['employee']['label'] = "Aika ja paikka (työntekijä)";
$date['employee']['text'] = date("j.n.Y") . ", Kuhmo";
$date['employee']['type'] = "text";
$date['employee']['name'] = "date_loc_employee";

$signatureLabel['employer']['label'] = "Runolaulu-Akatemian edustajan allekirjoituksen otsikko";
$signatureLabel['employer']['text'] = "Pekka Huttu-Hiltunen,<br>Runolaulu-Akatemia, puheenjohtaja";
$signatureLabel['employer']['type'] = "textarea";
$signatureLabel['employer']['name'] = "signature_employer";

$signatureLabel['employee']['label'] = "Esiintyjän/esiintyjän edustajan allekirjoituksen otsikko";
$signatureLabel['employee']['text'] = $user->name[0] . "<br>" . $bandName;
$signatureLabel['employee']['type'] = "textarea";
$signatureLabel['employee']['name'] = "signature_employee";

?>

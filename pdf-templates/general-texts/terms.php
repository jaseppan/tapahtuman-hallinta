<?php

/*****************/
$x++;
$subInd = 0;
$subInd++;
$text[$x]['label'] = $x . ". Sopimusehdot";
$text[$x]['type'] = "textarea";

$text[$x]['text'] = "<p>" . $x+3 . "."  . $subInd . " Järjestäjä maksaa edellä sovitusta " . $tyosta . " korvauksena " . strtolower($position) . "lle " . $fee . " euroa, järjestäjä vastaa palkkion sivukuluista. Palkkio maksetaan konsertin jälkeen " . $position . "n toimitettua verokortin";
/*if($feeMethod=="verokortti") { 
	$text[$x]['text'] .= "verokortin"; 
} else { 
	$text[$x]['text'] .= "laskun"; 
}*/
$text[$x]['text'] .= " Järjestäjälle. Taiteilija voi myös toimittaa laskun, jolloin palkkiosummaan voi lisätä 20% palkkiosummasta.<br />";
/*if($feeMethod=="verokortti") {
	$text[$x]['text'] .= "Palkkio sisältää sunnuntaityö- ja lomakorvaukset.<br>";
} else {
	$text[$x]['text'] .= "Palkkio sisältää matkakulut.<br>";
}*/

$subInd++;
$text[$x]['text'] .= $x . "."  . $subInd . " Matkakulut korvataan erikseen sovitulla tavalla matkalaskua ja kuitteja vastaan. <br>";

$subInd++;
if($needAccommodation="kylla") {
	$text[$x]['text'] .= $x . "."  . $subInd . " Järjestäjä vastaa majoituksista sekä ruokailuista ajalta " . date_format(date_create($dateOfArrival), 'j.n.Y') .  "–" . date_format(date_create($dateOfDeparture), 'j.n.Y') . ". Järjestäjä ei maksa erillistä päivärahaa " .$position . "lle. Jos " .$position . " haluaa tuoda mukanaan puolisonsa, lapsensa, lemmikkinsä tms., on hänen itse vastattava kustannuksista. Mikäli " .$position . " tarvitsee apua heidän majoituksessaan, on siitä ilmoitettava Järjestäjälle 18.6." . date("Y") . " mennessä.<br>";
} else {
	$text[$x]['text'] .= $x . "."  . $subInd . " Järjestäjä vastaa ruokailuista ajalta $dateOfArrival. Järjestäjä ei maksa erillistä päivärahaa " . $position . "jalle. Jos " .$position . " haluaa tuoda mukanaan puolisonsa, lapsensa, lemmikkinsä tms., on hänen itse vastattava kustannuksista.";	
}
if($position == 'Esiintyjä') {
$subInd++;
$text[$x]['text'] .= $x . "."  . $subInd . " " .$position . "n tulee itse vakuuttaa itsensä tarpeellisilla henkilö- ja omaisuusvakuutuksilla sopimuksen tarkoittaman tilaisuuden ja matkojen ajalle eikä Järjestäjä vastaa näistä vakuutuksilla katettavista vahingoista ja varkauksista, elleivät ne ole suoranaisia seurauksia Järjestäjän palveluksessa tai vastuulla olevan henkilön/henkilöiden virheellisistä toimenpiteistä.<br>";
}
$subInd++;
$text[$x]['text'] .= $x . "."  . $subInd . " Lääkärin toteama sairaus, mikäli se estää " .$position . "a tämän sopimuksen täyttämistä, kumoaa tämän sopimuksen, samoin sota, lakko, tulipalo, poliittiset selkkaukset sekä muut niihin verrattavat olosuhteet, jotka pakottavat Järjestäjän peruuttamaan ohjelman.<br>";

$subInd++;
$text[$x]['text'] .= $x . "."  . $subInd . " Mikäli jompikumpi osapuoli rikkoo sopimuksen, on rikkoja velvollinen suorittamaan toiselle osapuolelle vahingonkorvausta mainitun palkkion verran.<br>";
if($position == 'Esiintyjä') {
$subInd++;
$text[$x]['text'] .= $x . "."  . $subInd . " Palkkioon ei sisälly tallentamiskorvauksia (televisiointi/ radiointi, videointi, valokuvaus, joita käytetään kaupallisiin tarkoituksiin). Näistä korvauksista sovitaan erikseen ja niistä tehdään erillinen sopimus " .$position . "n, Järjestäjän ja tallentajan kanssa. Järjestäjä varaa itselleen oikeuden ottaa valokuvia, videoida ja äänittää konsertteja omaa tiedotusta ja dokumentointia varten. Pedagogiseen kehittämistyöhön osallistuvat (mainittu sopimuksen johdannossa, ja jos ei ole yliviivattu) luovuttavat kuitenkin tällä sopimuksella mahdollisen ääni- ja videomateriaalin korvauksetta Runolaulu-Akatemian pedagogisen kehittämistyön käyttöön. Näissäkin tapauksissa Runolaulu-Akatemia on velvollinen kertomaan mahdollisesta käyttämisestä " .$position . "lle ennen sen toteuttamista.<br>";
} elseif($position == 'Opettaja') {
$text[$x]['text'] .= $x . "."  . $subInd . " Järjestäjä varaa itselleen 
oikeuden ottaa valokuvia, videoita ja äänittää opetusta ja konsertteja omaa tiedotusta ja dokumentointia varten.<br>";

}


$subInd++;
$text[$x]['text'] .= $x . "."  . $subInd . " Palkkio maksetaan 31.8." . date("Y") . " mennessä. Palkkioiden tai matkakorvausten perimisen ehdoton takaraja on 30.9." . date("Y")  . "<br>";


$subInd++;
$text[$x]['text'] .= $x . "."  . $subInd . " Tätä sopimusta koskevat mahdolliset erimielisyydet ratkaistaan ensisijaisesti keskinäisin neuvotteluin ja toissijaisesti Kajaanin käräjäoikeudessa.<p>";

$x++;
$text[$x]['text'] = "Tätä sopimusta on tehty kaksi samansisältöistä kappaletta, yksi kummallekin sopijapuolelle.<br>";

/*****************/
?>

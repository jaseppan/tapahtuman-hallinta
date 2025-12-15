<?php
$title = "TYÖSOPIMUS";

$x = 0;
$text[$x]['label'] = "TYÖNANTAJA";
$text[$x]['text'] = "Musiikkiyhdistys pro Sommelo ry<br>Kainuuntie 99, 88900  KUHMO<br>puh. 040 179 8600<br>Y-tunnus 2518498-5";
$text[$x]['type'] = "textarea";

$x++;
$text[$x]['label'] = "TYÖNTEKIJÄ";
$text[$x]['text'] = $user->name[0] . "<br>" . $idNum . "<br>" . $address . "<br>" . $zipCode . " " . $city . "<br>puh. " . $phone;
$text[$x]['type'] = "textarea";

$x++;
$text[$x]['label'] = "AMMATTINIMIKE";
$text[$x]['text'] = $task;
$text[$x]['type'] = "text";

$x++;
$text[$x]['label'] = "TYÖPAIKKA JA -TEHTÄVÄT";
$text[$x]['text'] = "Kainuuntie 99, Kuhmo<br>TEHTÄVÄT";
$text[$x]['type'] = "textarea";

$x++;
$text[$x]['label'] = "TYÖAIKA";
$text[$x]['text'] = "Sopimuksen mukaan. ( Työaika 36,25 viikkotuntia)";
$text[$x]['type'] = "textarea";

$x++;
$text[$x]['label'] = "TYÖSOPIMUKSEN KESTOAIKA";
$text[$x]['text'] = $date_of_arrival . " - " . $date_of_departure;
$text[$x]['type'] = "text";

$x++;
$text[$x]['label'] = "TYÖNTEKIJÄ SITOUTUU";
$text[$x]['text'] = "Noudattamaan laitoksen sääntöjä; siihen, ettei käytä hyväkseen eikä ilmaise sivullisille, mitä on saanut tietoonsa työssään tai muutoin työnantajan liike- ja ammattisalaisuuksia.";
$text[$x]['type'] = "textarea";

$x++;
$text[$x]['label'] = "IRTISANOMISAIKA";
$text[$x]['text'] = "Irtisanomisaika määräytyy yliopistojen työehtosopimuksen mukaisesti.";
$text[$x]['type'] = "textarea";

$x++;
$text[$x]['label'] = "PALKKA JA SEN MAKSAMINEN";
$text[$x]['text'] = "Palkka on " . $fee . " EUR, joka maksetaan heinäkuun loppuun mennessä työntekijän ilmoittamalle pankkitilille. Palkka sisältää lomakorvauksen, lomarahan sekä muut lisät.";
$text[$x]['type'] = "textarea";

$x++;
$text[$x]['label'] = "TYÖNTEKIJÄÄN SOVELLETTAVA TYÖEHTOSOPIMUS";
$text[$x]['text'] = "Yliopistojen työehtosopimus.";
$text[$x]['type'] = "text";

$x++;
$text[$x]['label'] = "LISÄKSI OLEMME SOPINEET";
$text[$x]['text'] = "";
$text[$x]['type'] = "textarea";

$x++;
$text[$x]['label'] = "ELÄKEOIKEUS";
$text[$x]['text'] = "Eläkeoikeus määräytyy TyEL:n mukaan";
$text[$x]['type'] = "text";

$x++;
$text[$x]['label'] = "ALLEKIRJOITUKSET";
$text[$x]['text'] = "Tätä sopimusta on tehty kaksi samansisältöistä kappaletta, yksi  kummallekin sopijapuolelle";
$text[$x]['type'] = "textarea";

$date['employer']['label'] = "Aika ja paikka (työnantaja)";
$date['employer']['text'] = date("j.n.Y") . ", Kuhmo";
$date['employer']['type'] = "text";
$date['employer']['name'] = "date_loc_employer";

$date['employee']['label'] = "Aika ja paikka (työntekijä)";
$date['employee']['text'] = date("j.n.Y") . ", Kuhmo";
$date['employee']['type'] = "text";
$date['employee']['name'] = "date_loc_employee";

$signatureLabel['employer']['label'] = "Työnantajan allekirjoituksen otsikko";
$signatureLabel['employer']['text'] = "Pekka Huttu-Hiltunen,<br>Pro Sommelo, puheenjohtaja";
$signatureLabel['employer']['type'] = "textarea";
$signatureLabel['employer']['name'] = "signature_employer";

$signatureLabel['employee']['label'] = "Työntekijän allekirjoituksen otsikko";
$signatureLabel['employee']['text'] = $user->name[0];
$signatureLabel['employee']['type'] = "textarea";
$signatureLabel['employee']['name'] = "signature_employee";
?>

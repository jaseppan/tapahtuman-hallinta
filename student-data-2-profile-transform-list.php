<?php
	checkPermission(2);
	// $transformList konvertoi fied_id:t seminar systeemistä ajax systeemiin
	$idTransformList = array(18=>61,3=>15,4=>16,5=>17,7=>18,16=>32,17=>31,19=>34,20=>43,21=>28,27=>50);
	// Pienin kurssin id	
	$minId = 25; // Edellisen vuoden viimeinen
	// $allMeals = "lounas 23.6.|paivallinen 23.6.|lounas 24.6.|paivallinen 24.6.|lounas 25.6.|paivallinen 25.6.|lounas 26.6.|paivallinen 26.6.";
	
	$allMeals = "lounas 23.6.|lounas 24.6.|lounas 25.6.|lounas 26.6.|";
	
	$mealArray = explode('|', $allMeals);
	$allMealValues = '[';
	foreach ($mealArray as $index => $meal) {
		$mealName = explode(' ', $meal)[0];
		$mealDate = explode(' ', $meal)[1];
		$mealValue = strtolower($mealName) . ' ' . $mealDate;
		$mealText = ucfirst($mealName) . ' | ' . ucfirst($mealName) . ' ' . $mealDate;
		$allMealValues .= '{"value":"' . $mealValue . '","text":"' . $mealText . '"}';
		if ($index < count($mealArray) - 1) {
			$allMealValues .= ',';
		}
	}
	$allMealValues .= ']';
	// $allMealValues = '[{"value":"aamiainen 26.6.","text":"Aamiainen | Breakfast 26.6."},{"value":"lounas 26.6.","text":"Lounas | Lunch 26.6."},{"value":"paivallinen 26.6.","text":"P&auml;iv&auml;llinen | Dinner 26.6."},{"value":"aamiainen 27.6.","text":"Aamiainen | Breakfast 27.6."},{"value":"lounas 27.6.","text":"Lounas | Lunch 27.6."},{"value":"paivallinen 27.6.","text":"P&auml;iv&auml;llinen | Dinner 27.6."},{"value":"aamiainen 28.6.","text":"Aamiainen | Breakfast 28.6."},{"value":"lounas 28.6.","text":"Lounas | Lunch 28.6."},{"value":"paivallinen 28.6.","text":"P&auml;iv&auml;llinen | Dinner 28.6."},{"value":"aamiainen 29.6.","text":"Aamiainen | Breakfast 29.6."},{"value":"lounas 29.6.","text":"Lounas | Lunch 29.6."},{"value":"paivallinen 29.6.","text":"P&auml;iv&auml;llinen | Dinner 29.6."},{"value":"aamiainen 30.6.","text":"Aamiainen | Breakfast 30.6."},{"value":"lounas 30.6.","text":"Lounas | Lunch 30.6."},{"value":"paivallinen 30.6.","text":"P&auml;iv&auml;llinen | Dinner 30.6."},{"value":"aamiainen 1.7.","text":"Aamiainen | Breakfast 1.7."},{"value":"lounas 1.7.","text":"Lounas | Lunch 1.7."},{"value":"paivallinen 1.7.","text":"P&auml;iv&auml;llinen | Dinner 1.7."},{"value":"aamiainen 2.7.","text":"Aamiainen | Breakfast 2.7."},{"value":"lounas 2.7.","text":"Lounas | Lunch 2.7."},{"value":"paivallinen 2.7.","text":"P&auml;iv&auml;llinen | Dinner 2.7."},{"value":"aamiainen 3.7.","text":"Aamiainen | Breakfast 3.7."},{"value":"lounas 3.7.","text":"Lounas | Lunch 3.7."},{"value":"paivallinen 3.7.","text":"P&auml;iv&auml;llinen | Dinner 3.7."},{"value":"aamiainen 4.7.","text":"Aamiainen | Breakfast 4.7."}]';
	// $allMealValues = '[{"value":"aamiainen 26.6.","text":"Aamiainen | Breakfast 26.6."},{"value":"lounas 26.6.","text":"Lounas | Lunch 26.6."},{"value":"paivallinen 26.6.","text":"P&auml;iv&auml;llinen | Dinner 27.6."},{"value":"aamiainen 27.6.","text":"Aamiainen | Breakfast 27.6."},{"value":"lounas 27.6.","text":"Lounas | Lunch 27.6."},{"value":"paivallinen 27.6.","text":"P&auml;iv&auml;llinen | Dinner 27.6."},{"value":"aamiainen 28.6.","text":"Aamiainen | Breakfast 28.6."},{"value":"lounas 28.6.","text":"Lounas | Lunch 28.6."},{"value":"paivallinen 28.6.","text":"P&auml;iv&auml;llinen | Dinner 28.6."},{"value":"aamiainen 29.6.","text":"Aamiainen | Breakfast 29.6."},{"value":"lounas 29.6.","text":"Lounas | Lunch 29.6."},{"value":"paivallinen 29.6.","text":"P&auml;iv&auml;llinen | Dinner 29.6."},{"value":"aamiainen 30.6.","text":"Aamiainen | Breakfast 30.6."},{"value":"lounas 30.6.","text":"Lounas | Lunch 30.6."},{"value":"paivallinen 30.6.","text":"P&auml;iv&auml;llinen | Dinner 30.6."},{"value":"aamiainen 1.7.","text":"Aamiainen | Breakfast 1.7."},{"value":"lounas 1.7.","text":"Lounas | Lunch 1.7."},{"value":"paivallinen 1.7.","text":"P&auml;iv&auml;llinen | Dinner 1.7."},{"value":"aamiainen 2.7.","text":"Aamiainen | Breakfast 2.7."},{"value":"lounas 2.7.","text":"Lounas | Lunch 2.7."},{"value":"paivallinen 2.7.","text":"P&auml;iv&auml;llinen | Dinner 2.7."},{"value":"aamiainen 3.7.","text":"Aamiainen | Breakfast 3.7."},{"value":"lounas 3.7.","text":"Lounas | Lunch 3.7."},{"value":"paivallinen 3.7.","text":"P&auml;iv&auml;llinen | Dinner 3.7."},{"value":"aamiainen 4.7.","text":"Aamiainen | Breakfast 4.7."}]';
	//$lunches = "lounas 24.6.|lounas 25.6.|lounas 26.6.|lounas 27.6.|lounas 28.6.|lounas 29.6.";	
	$lunches = "lounas 23.6.|lounas 24.6.|lounas 25.6.|lounas 26.6.|";
		
?>


<!--
// Ilmoittautumistiedot
1	Kurssilaisen tiedot
18	Syntymäaika
3	Katuosoite
4	Postinumero
5	Kaupunki
6	Maa
7	Puhelinnumero
8	AGBs
9	Yhteyshenkilö
10	Organisaatio
11	Katuosoite
12	Postinumero
13	Kaupunki
15	Puhelinnumero
16	Saapumispäivä
17	Lähtöpäivä
19	Ruokailupaketti
20	Erityisruokavalio
21	Varaan koulumajoituksen (10€ / yö)
22	Lyhyt kuvaus taitotasosta
23	Soitin
24	Vuokraan soittimen (hinta 5€/päivä)
25	Samasta perheestä osallistuu useampia lapsia (sisaralennus 50% toisesta lapsesta)
26	{lang fi}Varaus{/lang}
27	Lisätietoja
28	Toiveet kurssiin liittyen
29	Kurssilaisen nimi

// Profiilitiedot
1	Terms of Service
2	I Accept
3	{lang en}Date of Birth{/lang}{lang fi}Syntymäaika{/lang}
4	Esittelyteksti - Suomeksi | Artist introduction - in Finnish
6	Newsletter
10	Image | Kuva
13	{lang fi}Maa{/lang}{lang en}Country{/lang}
14	Esittelyteksti englanniksi | Artist introduction - in English
15	{lang fi}Lähiosoite{/lang}{lang en}Street address{/lang}
16	{lang en}Postal code{/lang}{lang fi}Postinumero{/lang}
17	{lang en}City{/lang}{lang fi}Postitoimipaikka{/lang}
18	{lang en}Telephone{/lang}{lang fi}Puhelin{/lang}
20	{lang en}Position{/lang}{lang fi}Asema{/lang}
21	Www-sivut | Website
23	Yhtye/Esitys | Ensemble/Performance
27	Henkilötunnus | Identity Number
28	{lang fi}Tarvitsetko majoituksen{/lang}{lang en}Do you need accommodation{/lang}
31	{lang fi}Lähtöpäivä (Kuhmosta){/lang}{lang en}Date of Departure (from Kuhmo){/lang}
32	{lang fi}Saapumispäivä{/lang}{lang en}Date of arrival{/lang}
33	{lang fi}Ruokailen Sommelon ruokalassa{/lang}{lang en}I will dine in the festival canteen{/lang}
34	Ateriat
35	Palkkion maksu
36	Pankkin nimi | Name of your bank
38	IBAN
39	Verotuskunta | Municipality of taxation
40	SWIFT (BIC)
41	Verokortti | Income-tax card
43	Ruokavalio | Diet
45	{lang fi}Osallistutko Vienan matkaan?{/lang}{lang en}Will you participate in Journey to Viena Karelia?{/lang}
46	Viisumi tyyppi | Visa type
47	Vienaan lähtöpäivä
48	Vienasta paluupäivä
49	Majoittuspaikka Vienassa
50	{lang fi}Lisätietoja{/lang}{lang en}Additional information{/lang}
51	Laskutus majoituksesta
52	Majoituspaikka
53	Huone/Luokka
54	Kuljetus Vienaan
55	Kuljetus Vienasta
56	Majoitustoive
57	Palkka (jos palkkion maksu verokortilla)
58	Palkkio (jos palkkion maksu laskulla)
59	Tehtävä
60	Haen talkoolaiseksi
61	Syntymäaika
62	Sukupuoli
63	Lyhyt kuvaus itsestä ja millaisista tehtävistä olet kiinnostunut
64	Majoituksessa huomioitavaa
-->

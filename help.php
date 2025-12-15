<div style="width: 600px; margin-left: auto; margin-right: auto;">
<p>OHJEET</p>
<p>SISäLLYS</p>
<p><a href="#1">1. HENKILöIDEN TIETOJEN TARKASTELU JA MUOKKAUS</a><br />
<a href="#1_1">1.1. SOPIMUKSET</a><br />
<a href="#1_2">1.2. TIETOLUOKKIEN LISÄÄMINEN</a><br />
<a href="#2">2. HAKU</a><br />
<a href="#3">3. HENKILöIDEN LISääMINEN</a><br />
<a href="#3_1">3.1. LISää HENKILö</a><br />
<a href="#3_1">3.2. CSV-tiedoston tuominen</a><br />
<a href="#3_2">3.3. USEAN HENKILöN LISääMINEN</a><br />
<a href="#4">4. LISTAT</a><br />
<a href="#4_1">4.1. LUO OMA LISTA</a><br />
<a href="#5">5. TULOSTUKSET</a><br />
<a href="#5_1">5.1. MAJOITUSTIEDOT</a><br />
<a href="#5_2">5.2. NIMILAPUT</a><br />
<a href="#5_3">5.3. RUOKALIPUT</a><br />
<a href="#5_4">5.4. RUOKAILULISTA</a><br />
<a href="#5_5">5.5. TARJOTTAVIEN ATERIOIDEN LISÄÄMINEN</a><br />
<a href="#6">6. TYöKALUT</a><br />
<a href="#6_1">6.1. LISTATTAVAT HENKILöT</a><br />
<a href="#6_2">6.2. ESIINTYJä-TAPAHTUMA -LINKIT</a><br />
<a href="#6_3">6.3 SYNKRONOI KURSSILAISTEN TIEDOT</a><br />
<a href="#6_4">6.4. HALLINAN KäYTTäTIEDOT</a><br />
<a href="#7">7. KäYTTäJäTASOT</a></p>
<p>&nbsp;</p>
<p>JOHDANTO</p>
<p>Tämä on Sommelolle räätälöity tapahtuman hallinta -sovellus, jonka avulla voi hallita tapahtumassa olevien henkilöiden tietoja sekä luoda tietojen pohjalta majoitus- ja ruokailulistoja sekä tulostaa nimilaput ja ruokaliput.</p>
<p><a name="1">1. HENKILöIDEN TIETOJEN TARKASTELU JA MUOKKAUS</a></p>
<p>Henkilöiden tietojen tarkesteluun pääsee haulla tai listojen näytä-/muokkaa -linkeistä.</p>
<p>Tietojen tarkastelussa oikealla näkyvät &rdquo;Samikset&rdquo; -lista/listat näyttävät muut henkilöt joilla on sama arvo kohdassa asema tai &rdquo;kyllä&rdquo; -valinta kohdassa &rdquo;Tarvitsetko majoituksen?&rdquo;, &rdquo;Ruokailen Sommelon ruokalassa&rdquo; tai &rdquo;Osallistutko Vienan matkaan?&rdquo;. Samikset-listojen avulla voit käydä nopeasti läpi erilaisia ryhmiä</p>
<p>Vasemmalla olevasta valikosta voit valita haluatko muokata tietoja, luoda sopimuksen vai palata takaisin.</p>
<p>Jos avaat muokkauksen, niin kyseisen henkilön muokkaus on varattu käyttöösi seuraavaksi viideksi minuutiksi tai kunnes olet tallentanut. Tämän tarkoitus on estää mahdollisuus, että eri henkilöt muokkaavat saman henkilön tietoja samaan aikaan, jolloin toisen muutokset voivat kumoutua. Aika on rajattu viiteen minuuttiin, jotta henkilön tietojen muokkaus ei jäisi lukituksi maailman tappiin asti.</p>
<p><a name="1_1">1.1. SOPIMUKSET</a></p>
<p>Luo sopimus -nappin takana oleva sopimuksen luonti- työkalussa sopimustyyppi riippuu henkilön asemasta. Mikäli henkilölle pitää luoda useampi sopimus (esim. taiteilija ja opettaja sopimus), niin se onnistuu vaihtamalla välillä henkilön asemaa.</p>
<p><a name="1_2">1.2. TIETOLUOKKIEN LISÄYS</a></p>
<p>Mikäli tarvitset lomakkeeseen uusia kenttiä tai pudotusvalikoihin tms. lisää vaihtoehtoja tai haluat muokata niitä, niin se tapahtuu Joomlan hallinnassa (Komponentit -> Ajax Register). Jo luoduissa kentissä voi huoleta muokata valintavaihtoehtojen otsikoita (oikealla), mutta ei ole suositeltavaa tehdä muutoksia oikeanpuolisiin tunnisteisiin. Esim. Aterian tunnisteet ovat määrämuotoisia ja muutokset niissä johtaa ateriatulosteiden virheelliseen toimintaan.
<p>&nbsp;</p>
<p><a name="2">2. HAKU</a></p>
<p>Haku kohdistuu tietokannassa olevien ihmisten nimiin, sähköpostisoitteisiin ja tallennetuihin profiilitietoihin. Hakuehtona käytettäviä sanoja ei ole välttämätöntä kirjoittaa kokonaan ja suurilla ja pienillä kirjaimilla ei ole eroa.</p>
<p>Klikkaamalla saadun henkilön nimeä pääset tietojen tarkasteluun.</p>
<p>&nbsp;</p>
<p><a name="3">3. HENKILöIDEN LISääMINEN</a></p>
<p><a name="3_1">3.1. LISää HENKILö</a></p>
<p>Lomakkeella voi tallentaa kerralla yhden henkilön kaikki tarvittavat tiedot.</p>
<p><a name="3_2">3.2. CSV-tiedoston tuominen</a></p>
<p>Työkalulla voi tuoda järjestelmään henkilöitä csv-formaatissa tallennettusta excel-taulukkosta. Tarkemmat ohjeet työkalun yhteydessä olevissa ohjeissa.</p>
<p>&lt; KOITAN TEHDä TäHäN VIDEON &gt;</p>
<p><a name="3_3">3.2. USEAN HENKILöN LISääMINEN</a></p>
<p>Lisättäessä uusia henkilöitä tietokantaan syötetään ensin nimi ja sähköpostiosoite. Tarkemmat ohjeet lomakkeen vierässä. Seuraava video opastaa, miten voi tuoda henkkilöitä excel tiedostosta.</p>
<p>&lt; KOITAN TEHDä TäHäN VIDEON &gt;</p>
<p>Henkilöiden profiilitiedot (yhteys-, majoitus-, ruokailu jne. -tiedot) lisätään henkilöiden tietojen muokkauksessa.</p>
<p>&nbsp;</p>
<p><a name="4">4. LISTAT</a></p>
<p>Listat näyttävät henkilöiden tiedot annetuilla rajauksilla. Tiedot voi järjestää sarakkeiden mukaan klikkamalla halutun sarakkeen otsikkoa, Tarvittaessa nimet voi kääntää muotoon Sukunimi, Etunimi.</p>
<p>Taulokot voi ladata Excel-tiedostona omalle koneelle klikkaamalla &rdquo;Lataa tiedosto&rdquo; -nappia. Tämä voi olla tarpeen esim. taulukon tulostamista varten. (Huom. toiminto toimii parhaiten Firefox-selaimella. Chrome-selaimella ladatussa tiedostossa ei tiedostopäätettä, josta järjestälmäsi osaisi päätellä millä ohjelmalla se pitäisi avata, jolloin tiedosto täytyy avata excelin kauttaa. Safarilla ja IE:llä toiminto ei ole testattu).</p>
<p><a name="4_1">4.1. LUO OMA LISTA</a></p>
<p>Voit luoda omia listoja kohdassa Listat &gt; Luo oma lista. Jos tarvitset luomaasi listaa usein, niin voit listä sen omiin kirjamerkkeihin. Jos arvelet muidenkin tarvitsevan luomaasi listaa lähetä Oma lista -työkalun luoma osoite ja nimiesitys sähköpostiin <a href="mailto:janne.seppanen@runolaulu.fi" style="line-height: 100%;">janne.seppanen@runolaulu.fi</a>, niin voidaan lisätä linkki Listat -valikkoon.</p>
<p>&nbsp;</p>
<p><a name="5">5. TULOSTUKSET</a></p>
<p>Tulostuksien kautta voit luoda tarpeellisia lippuja ja lappuja. Kurssilaisten ilmoittautumistiedot eivät ole suoraan käytettävissä tulostuksissa, joten on tärkeää synkronoida kurssilaisten tiedot ennen tulosteiden luomista (kts. Kohta 5.3.) .</p>
<p><a name="5_1">5.1. MAJOITUSTIEDOT</a></p>
<p>Luo raportin järjestelmässä olevien henkilöiden majoitustiedoista. Voit valita haluatko tiedot pdf:ään ja excel-tiedostoon (huom. tiedosto on avattava excelin tms. kautta).</p>
<p><a name="5_2">5.2. NIMILAPUT</a></p>
<p>Luo nimilaput järjestelmässä oleville henkilöille. Nimilaput voi luoda aseman perusteella määritellylle ryhmälle ja tarvittaessa voit valita yksittäisiä henkilöitä.</p>
<p>Nimen alla oleva teksti määräytyy tehtävän mukaan.</p>
<p><a name="5_3">5.3. RUOKALIPUT</a></p>
<p>Luo ruokaliput järjestelmässä oleville henkilöille, jotka ruokailevat. Ruokaliput voi luoda aseman perusteella määritellylle ryhmälle ja tarvittaessa voit valita yksittäisiä henkilöitä.</p>
<p><a name="5_4">5.4. RUOKAILULISTA</a></p>
<p>Ruokailulistassa näkyvät kaikki henkilöt, joilla on valittu arvo &rdquo;Kyllä&rdquo; kohdassa &rdquo;Ruokailee Sommelon ruokalassa&rdquo;. Ellei yksittäisiä aterioita ole merkitty, niin henkilö merkitään kaikille aterioille oleskelu aikanaan saapumis- ja lähtöpäivämäärän perusteella.</p>
<p>Ellei henkilölle ole määritelty aterioita eikä saapumis- ja/tai lähtöpäivämäärää, niin puutteellisisita tiedoista ilmoitetaan ruokailulistan alla.</p>
<p>Voit valita haluatko tiedot pdf:ään ja excel-tiedostoon (huom. tiedosto on avattava excelin tms. kautta).</p>
5.5. TARJOTTAVIEN ATERIOIDEN LISÄÄMINEN
<p>Tarjolla olevat ateriat määritellään AJAX Registerin Ateria -Extra Fieldin arvojen perusteella: voit muokata niitä täällä: http://www.sommelo.net/administrator/index.php?option=com_ajaxregister&view=field&layout=edit&id=34, Muokkaa samalla tiedoston student-data-2-profile-transform-list.php $allMeals ja $lounches arvoja.</p> 
<p>&nbsp;</p>
<p><a name="6">6. TYöKALUT</a></p>
<p><a name="6_1">6.1. LISTATTAVAT HENKILöT</a></p>
<p>Listattavat henkilöt -työkalulla määritellään, ketkä henkilöt näkyvät listoissa ja tulostuksissa. Henkilöitä ei voi poistaa Tapahtuman hallinnan kautta. Mikäli havaitset henkilöitä, jotka mielestäsi joutaisi poistaa ilmoita asiasta Jannelle.</p>
<p><a name="6_2">6.2. ESIINTYJä-TAPAHTUMA -LINKIT</a></p>
<p>Esiintyjä-tapahtuma -linkit -työkalulla määritellään tapahtumat joissa esiintyjät esiintyvät. Kun linkit on määritelty, niin tiedot tapahtumasta tulevat suoraan niiden henkilöiden sopimuksiin, joiden tietoihin on määritelty arvo kohtaan Yhtye/Esitys | Ensemble/Performance.</p>
<p>Esiintyjien nimiä hallitaan Joomlan hallinnan kautta kohdassa Komponentit &gt; Ajax Register &gt; Extra fields &gt; Yhtye/Esitys | Ensemble/Performance.</p>
<p>Konserttitietojen hallinta niin ikään Joomlan hallinnassa Jevents -kalenterissa (Komponentit &gt; Jevents &gt; Hallitse tapahtumia</p>
<p><a name="6_3">6.3 SYNKRONOI KURSSILAISTEN TIEDOT</a></p>
<p>Kuten mainittua kurssilaisten ilmoittautumistiedot eivät ole suoraan käytettävissä tulostuksissa. Synkronointi työkalulla tuodaan kurssilaisten kurssilaisten yhteys-, majoittumis- ja ruokailutiedot henkilötietokantaan. Samalla määritellään automaattisesti ateriat valitun ruokailupaketin mukaan ja määritellään majoituspaikaksi Kontion koulu.</p>
<p>Työkalu lisää vain puuttuvat kurssilaiset, joten aiemmin tehdyt muutokset kurssilaisten tietoihin säilyvät.</p>
<p>&nbsp;</p>
<p><a name="6_4">6.4. HALLINAN KäYTTäTIEDOT</a></p>
<p>Tämä ominaisuus on vain pääkäyttäjän käyttäjässä. Tällä voi lisätä ja poistaa käyttäjiä ja määritellä heidän oikeutensa</p>
<p>&nbsp;</p>
<p><a name="7">7. KäYTTäJäTASOT</a></p>
<p>1. taso (Aputyövoima)<br />
- Voi hakea ja tarkastella tietoja<br />
- Voi luoda tulosteita<br />
- Näytetään vain seuraavat henkilötiedot: Nimi, sähköpostiosoite, asema, tehtävä, yhtye, puhelinnumero, saapumispäivä, lähtöpäivä, majoituspaikka</p>
<p>2. taso (Manageri)<br />
- Voi hakea, tarkastella ja muokata kaikkia henkilötietoja ym.</p>
<p>3. taso (Pääkäyttäjä)<br />
- Lisäksi voi lisätä hallinnan käyttäjiä poistaa kurssilaiset henkilötietokannasta</p>
</div>

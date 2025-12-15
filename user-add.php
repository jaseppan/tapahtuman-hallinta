<?php checkPermission(2);
?>
<div id="add-user">
	<h3>Lisää henkilö</h3>
	<div id="add-user-form">
		<form action = "index.php?page=user-insert" method="POST">
			<p><label>Nimi</label><br/>
			<textarea name="name" cols="50" rows="10">
			</textarea></p>
			<p><label>Sähköposti</label><br/>
			<textarea name="email" cols="50" rows="10">
			</textarea><br/></p>
			<input type="submit" name="submit" value="Lisää">
		</form>
	</div>
	<div id="add-user-help">
		<p>Syötä nimet muodossa Etunimi Sukunimi.</p>
		<p>Voit lisätä useamman henkilön kerralla liittämällä kenttään nimilistan esim.: <br />
		Matti Meikäläinen, Maija Meikäläinen, Jouko Heikäläinen</p>
		<p>Vastaavat sähköposti osoitteet voit antaa myös listana:<br />
		matti.meikalainen@sahkoposti.fi, maija.meikalainen@sahkoposti.fi, jouko.heikäläinen@gmail.com</p>
		<p><b>Huom!</b> 
<li>Listojen on oltava saman pituiset</li>
<li>Vältä henkilön lisäämistä ilman sähköpostia, mutta tarvittaessa voit merkitä puuttuvan sähköpostiosoiteen viivalla (-) esim.:<br />Matti Meikäläinen, -, Jouko Heikäläinen</li>
<li>Älä tallenna kurssilaisia tällä lomakkeella
	</div>
</div>

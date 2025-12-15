<?php checkPermission(3); ?>
<div class="info-div">
	<h2>Hallinnan käyttäjät</h2>
	<table style="margin-bottom:30px;" width="600px">
	<tr align="left">
	<th>Nimi</th><th>Käyttäjänimi</th><th>Oikeudet</th>
	</tr>
	<?php
		for($x = 0; $x < count($id); $x++) {
			echo "<tr><td>" . $name[$x] . "</td><td>" . $user_name[$x] . "</td><td align='center'>" . $privileges[$x] . "</td>
			<td><a href='index.php?page=admin-manager&task=edit&id=" . $id[$x] . "' class='editlink'>Muokkaa</a> | 
			<a href='index.php?page=admin-manager&task=delete&id=" . $id[$x] . "' class='deletelink'>Poista</a></td></tr>";
		}
	?>
	<table>
</div>

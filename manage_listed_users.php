<?php 
checkPermission(2);
include("hidden_persons.php");
include("function-name-format-changer.php");

// Haetaan henkilöt
$sql = "SELECT * FROM `so_users`";

$result = $mysqli->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$id[] = $row["id"];
		$name[] = $row["name"];
		$email[] = $row["email"];			

	}
} else {
	echo $sql . "<br>";
	echo("MySQLi error");
}



for($x = 0; $x < count($id); $x++) { 
	if(!in_array($id[$x],$hidden_persons_hardcoded)) {
		$tmp = "<tr><td>" . trim(nameFormatChanger($name[$x])) . ", " . $id[$x] . "</td>";
		$tmp .= "<td>Näytä <input type='radio' name='" . $id[$x] . "' value='0'"; 
		if(!in_array($id[$x],$hidden_persons_db)){ 
			$tmp .= " checked>";
		}
		$tmp .= "</td>";
		$tmp .= "<td>Piilota <input type='radio' name='" . $id[$x] . "' value='1'";
		if(in_array($id[$x],$hidden_persons_db)){ 
			$tmp .= " checked>";
		}
		$tmp .= "</td>";
		$form[] = $tmp;
	} 	
}


usort($form, 'strnatcasecmp');?>

<form action = "index.php?page=hidden_persons_set" method = "POST" >
	<table style="margin-left: auto; margin-right: auto;">
		<div style="position: fixed; right: 20px;">
			<input type="submit" name="submit" value="Tallenna muutokset">
		</div>
		<?php for($x = 0; $x < count($form); $x++) { 
			echo $form[$x];
		} ?>

	</table>
</form>


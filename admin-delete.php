<?php checkPermission(3); ?>
<p>Haluatko varmasti poistaa hallinnan käyttäjän <?php echo $name[0] ?>?<p>
<form action="" method="post">
<input type="submit" name="confirm" value="Peru">
<input type="submit" name="confirm" value="Kyllä">
</form>

<?php
if($_POST['confirm'] == 'Kyllä') {
	$sql = "DELETE FROM `so_js_event_manager_users` WHERE id = " . $_GET['id'];
	echo $sql . "<br>";	

	if ($mysqli->query($sql) === TRUE) {
		echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . $mysqli->error;
	}

	echo '<meta http-equiv="refresh" content="0; url=index.php?page=admin-manager" />';
}

?>

<?php checkPermission(1); ?>
<div id="search">
	<h1 style="text-align:center;">Henkilöhaku</h1>
	<div class="search-form">
		<form action="" method="POST" style="text-align:center;">
			<input type="text" name="search">
			<input type="submit" name="submit" value="Hae"/>
		</form>
		<?php
		if(isset($_POST['search']) && $_POST['search']) {
			include("get-search-data.php");
			if( $users->name ) {
				for($x = 0; $x < count($users->name); $x++) {
					echo "<a href='index.php?page=user-view&id=" . $users->id[$x] . "'>" . $users->name[$x] . " (" . $users->email[$x] . ")</a><br>";
					echo "<hr>";		
				}
			} else {
				echo "Mitään ei löydy";
			}
		} 
		?>
	</div>
</div>

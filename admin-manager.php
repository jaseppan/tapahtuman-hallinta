<?php 
checkPermission(3); 
if(!isset($task))
	$task = isset($_GET['task']) ? $_GET['task'] : false;

if($task != 'add') {
	if(isset($_GET['id']) && $_GET['id']){
		$sql = "SELECT id, name, user_name, privileges FROM `so_js_event_manager_users` WHERE id=" . $_GET['id'];
	} else {
		$sql = "SELECT id, name, user_name, privileges FROM `so_js_event_manager_users` ORDER BY name";
	}

	$result = $mysqli->query($sql);	

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$id[] = $row["id"];	
			$name[] = $row["name"];	
			$user_name[] = $row["user_name"];	
			$privileges[] = $row["privileges"];					
		}
	}
}

?>
<div id="info-view">
	<div class="tool-menu">
		<ul>
		<li><a href="index.php?page=admin-manager&task=list">Admin-käyttäjälista</a></li>
		<li><a href="index.php?page=admin-manager&task=add">Lisää admin-käyttäjä</a></li>
		<li><a onclick="goBack()">Palaa takaisin</a></li>
		</ul>
	</div>
	<div class="info-div">
	<?php
	if($task == 'edit'){
		include('admin-edit.php');	
	} elseif($task == 'delete'){
		include('admin-delete.php');	
	} elseif ($task == 'add') {
		include('admin-add.php');
	} else {
		include('admin-list.php');
	}
	?>
	</div>
</div>

<?php 
checkPermission(2);


if(!isset($_GET['id'])) 
    echo '<meta http-equiv="refresh" content="0; url=index.php?page=user-list" />';

if(!is_numeric($_GET['id'])) 
    echo '<meta http-equiv="refresh" content="0; url=index.php?page=user-list" />';

$id = intval( $_GET['id'] );

// Delete user from database
$sql = "DELETE FROM `so_users` WHERE id = " . $_GET['id'];

if ($mysqli->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $mysqli->error;
}

// Delete rows from database table so_ajaxregister_field_values with value $id in column user_id


$sql = "DELETE FROM so_ajaxregister_field_values WHERE user_id = " . $id;

if ($mysqli->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $mysqli->error;
}

echo '<meta http-equiv="refresh" content="0; url=index.php?page=user-list" />';


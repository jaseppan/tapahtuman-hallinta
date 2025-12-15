<?php 
checkPermission(2);

require_once('function-slugify.php');

if( isset($_POST['update-conserts']) ) {

    $artists_texts = (explode( ",",$_POST['artists']));
    sort($artists_texts);

    foreach($artists_texts as $text) {
        $text = filter_var($text, FILTER_SANITIZE_STRING);
        $artists[] = array (
            'value' => slugify($text),
            'text'  => trim($text),
        );
    }
    
    $artists_json = json_encode($artists);
    $sql = "UPDATE so_ajaxregister_fields SET value = '{$artists_json}' WHERE id = 23";
    $result = $mysqli->query($sql);

} else {

    $sql = "SELECT value FROM so_ajaxregister_fields WHERE id = 23";
    $result = $mysqli->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $artists = json_decode($row['value'], "ARRAY_A");
        }
    }
    

    
}

$artist_texts = array();

foreach( $artists as $value ){ 
    if(!empty($value['text'])) {
        $artist_texts[] = $value['text'];
    } 
} 


$artist_list = implode( ", ", $artist_texts);


?>

<form action="" method="post">
    <h2>Artistit</h2>
    <textarea name="artists" id="artists" cols="30" rows="10">
        <?php echo $artist_list; ?>
    </textarea>
    <input type="submit" name="update-conserts" value="Päivitä">
</form>


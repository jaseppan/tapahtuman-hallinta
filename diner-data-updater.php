<?php 
//checkPermission(2);

require_once('function-slugify.php');


if( isset($_POST['update-diners']) ) {

    $diners_texts = explode( "&#013;&#010;",$_POST['diners']);

    foreach($diners_texts as $text) {
        $text = filter_var($text, FILTER_SANITIZE_STRING);
        $diners[] = array (
            'value' => slugify($text),
            'text'  => $text,
        );
    }
    
    $diners_json = json_encode($diners);
    $sql = "UPDATE so_ajaxregister_fields SET value = '{$diners_json}' WHERE id = 34";
    $result = $mysqli->query($sql);

} else {

    $sql = "SELECT value FROM so_ajaxregister_fields WHERE id = 34";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $diners = json_decode($row['value'], 'ARRAY_A');
        }
    }
    
}


foreach( $diners as $value ){ 
    if(!empty($value['text'])) {
        $diner_texts[] = trim($value['text']);
    } 
} 


$diner_list = implode( "&#013;&#010;", $diner_texts);

?>

<form action="" method="post">
    <h2>Ateriat</h2>
    <textarea name="diners" id="diners" cols="30" rows="10">
        <?php echo $diner_list; ?>
    </textarea>
    <input type="submit" name="update-diners" value="Päivitä">
</form>


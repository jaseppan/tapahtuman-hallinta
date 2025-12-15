<?php
include("text.php");?>

<form action="pdf-templates/general-texts/pdf-creator.php" method="POST" target="_blank"> 
<!--Piilotetut-->
<input type="hidden" name="doc-name" value="Esiintymissopimus <?php echo $user->name[0]; ?>">
<input type="hidden" name="idNum" value="$idNum">
<input type="hidden" name="fee" value="fee">

<!--Näkyvät-->
<input type="text" name="title" value="<?php echo $title; ?>"><br>

<?php for($x = 0; $x < count($text); $x++) {?>
	<input type="text" name="label[]" value="<?php echo $text[$x]['label']; ?>" size="30"><br>
	<?php switch ($text[$x]['type']) {
		case "text": ?>
			<input type="<?php echo $text[$x]['type']; ?>" name="text[]" value="<?php echo $text[$x]['text']; ?>" size="50">
			<br>
			<?php break; 
		case "textarea": ?>
			<textarea name="text[]" rows="10" cols="50"><?php echo $text[$x]['text']; ?></textarea>

	<?php }
	echo "<br>";
}
?>
<br><br>
<label><?php echo $date['employer']['label']; ?></label><br>
<input type="<?php echo $date['employer']['type']; ?>" name="<?php echo $date['employer']['name']; ?>" value="<?php echo $date['employer']['text']; ?>" size="50">
<br><br>
<label><?php echo $date['employee']['label']; ?></label><br>
<input type="<?php echo $date['employee']['type']; ?>" name="<?php echo $date['employee']['name']; ?>" value="<?php echo $date['employee']['text']; ?>" size="50">
<br><br>
<label><?php echo $signatureLabel['employer']['label']; ?></label><br>
<textarea name="<?php echo $signatureLabel['employer']['name']; ?>" rows="10" cols="50"><?php echo $signatureLabel['employer']['text']; ?></textarea>
<br><br>
<label><?php echo $signatureLabel['employee']['label']; ?></label><br>
<textarea name="<?php echo $signatureLabel['employee']['name']; ?>" rows="10" cols="50"><?php echo $signatureLabel['employee']['text']; ?></textarea>
<br><br><input type="submit" name="submit" value="Luo pdf">
</form>

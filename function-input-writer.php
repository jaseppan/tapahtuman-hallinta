<?php
function inputWriter($x) {
	global $fields;
	global $value;
 	global $fieldValue;
	echo '<p>' . filterText($fields->label[$x]) . ': <br />';
	switch ($fields->type[$x]) {
		case "select":
		$options = json_decode($fields->value[$x],true);
		echo '<select name="' .$fields->id[$x] .  '">';
		echo '<option label=" "></option>';
		foreach ($options as $value) { 
			if($fieldValue && $value['value']==$fieldValue) {
				echo '<option value="' . $value['value'] . '" selected="selected">' . filterText($value['text']) . '</option>';
			} else {
			  	echo '<option value="' . $value['value'] . '">' . filterText($value['text']) . '</option>';
			}
		} 
		echo '</select>';	
		break;
		case "text":
		if($fields->validation[$x]=='date') {
			echo '<input type="date" name = "' . $fields->id[$x] . '" value="' . $fieldValue . '"/>';				
		} else  {?>					
			<input type="text" name = "<?php echo $fields->id[$x]; ?>" value="<?php echo $fieldValue;?>"/>
		<?php } ?>
		<?php break;
		case "textarea":
		?>	
		<textarea name = "<?php echo $fields->id[$x]; ?>" rows="8" cols="100"><?php echo $fieldValue;?></textarea>		
		<?php break;
		case "radios":
		$options = json_decode($fields->value[$x],true);
		?>
		<?php foreach ($options as $value) {
			if(isset( $value['value'] ) && isset($profile_id) && isset($profile->value[$profile_id]) && $value['value']==$profile->value[$profile_id]) {?>
				<input type="radio" name = "<?php echo $fields->id[$x] ?>" value="<?php echo $value['value'];?>" checked="checked"><?php echo filterText($value['text']); ?>
			<?php } else { ?>
				<input type="radio" name = "<?php echo $fields->id[$x] ?>" value="<?php echo $value['value'];?>"><?php echo filterText($value['text']); ?>
		
		
			<?php }?>
			<br />	
		<?php } ?>
		<?php break;
		case "radio":
		if( isset($profile) && $profile->value && $profile->value[$profile_id]==1) {
			echo '<input type="radio" name = "' . $fields->id[$x] . '" value="0"/>Ei';
			echo '<input type="radio" name = "' . $fields->id[$x] . '" value="1" checked="checked"/>Kyllä';
		} else {
			echo '<input type="radio" name = "' . $fields->id[$x] . '" value="0" checked="checked"/>Ei';
			echo '<input type="radio" name = "' . $fields->id[$x] . '" value="1"/>Kyllä';
		}
		echo '</p>';
		 
		break;
		case "checkboxes":
		$options = json_decode($fields->value[$x],true);
		$checked_values = ($fieldValue !== null && is_string( $fieldValue ) ) ? explode("|", $fieldValue) : [];
		echo "<br />";
		?>
		<?php foreach ($options as $value) {
			$found = array_search($value['value'], $checked_values);
			if ($found !== false) {?>
				<input type="checkbox" name = "<?php echo $fields->id[$x] . '[]' ?>" value="<?php echo $value['value'];?>" checked><?php echo filterText($value['text']); ?>
			<?php } else { ?>
				<input type="checkbox" name = "<?php echo $fields->id[$x] . '[]' ?>" value="<?php echo $value['value'];?>"/><?php echo filterText($value['text']); ?>
			<?php } ?>
		<?php }
		break;
		case "checkbox":
		if(isset($profile->value) && $profile_id && $profile->value[$profile_id]==1) {
			echo '<input type="checkbox" name = "' . $fields->id[$x] . '" value="1" checked>';
		} else {
			echo '<input type="checkbox" name = "' . $fields->id[$x] . '" value="1">';
		}
	}
}


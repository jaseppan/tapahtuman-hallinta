jQuery( document ).ready(function() {
	jQuery('#select_all').click( function() {
		jQuery('#person-list option').attr('selected', 'selected');
	});
	
	jQuery('#deselect_all').click( function() {
		jQuery('#person-list option').removeAttr("selected");
	});

	jQuery('.deletelink').click( function() {
		if (!confirm( "Haluatko varamasti poistaa henkilön tietokannasta? Toimintoa ei voi peruuttaa." ) ) {
			return false;
		}
	});
	
});




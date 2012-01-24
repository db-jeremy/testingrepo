jQuery(document).ready(function($) {
    // $() will work as an alias for jQuery() inside of this function
	//$("#sliderbox").css('background-color','#99e');
	$(function() {
		$( ".sliderbox-container-unnotched" ).scrollTo( '50%', 0 );
		$( ".tdr-ui-slider-unnotched" ).slider({
			slide: function(event, ui) {
			$(this).siblings(".sliderbox-container-unnotched").scrollTo( (ui.value)+'%', 0 );
			},
			value: 50
		});
	});
});
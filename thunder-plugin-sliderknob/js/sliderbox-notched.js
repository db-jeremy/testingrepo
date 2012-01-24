jQuery(document).ready(function($) {
    // $() will work as an alias for jQuery() inside of this function
	//$("#sliderbox").css('background-color','#99e');
	function getIndex (obj, value) {
		if (value > 0) {
		return Math.round(($(obj).parent().find('.slider-pane').size()-1)*(value/100));
		}
		else return 0;
	}
	//If difficulties continue, set the step size after initialization through a loop
	$(function() {
		$( ".sliderbox-container-notched" ).scrollTo( '0%', 0 );
		$( ".tdr-ui-slider-notched" ).slider({
			slide: function(event, ui) {
				$(this).siblings(".sliderbox-container-notched").scrollTo( $(this).parent().find('.slider-pane:eq('+getIndex(this, ui.value)+')'), 0 );
				//Alerts the value properly here, but doesn't work right in the step key value
				//alert($(this).siblings('.slider-pane').size());
			},
			value: 0,
			step:(($(this).parent().find('.slider-pane').size())-1)
		});
		//Get the step value after the fact
		//alert($( document ).(".tdr-ui-slider-notched").slider( "option", "step" ));
	});
});
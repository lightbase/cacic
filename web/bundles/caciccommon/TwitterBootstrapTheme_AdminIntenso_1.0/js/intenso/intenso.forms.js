// JavaScript Document
$(document).ready(function(){
	// Chosen multiselect
	$(".chzn-select").chosen();
	
	// activate typeahead
	$('.typeahead').typeahead();	

	// jQuery UI radio / checkbox buttons
	$('.radioset_on,.checkboxset_on').buttonset();
	
	// file input
	$('#fileInputUI').customFileInput({
        button_position : 'right'
    });
	
	// Uniform
	$(".uniform_on").uniform();
	
	// Jquery UI Datepicker
	$(".datepicker_on").datepicker();	
	
	// daterange picker 
	$('.daterange_on').daterangepicker();
	$('.daterange_duo_on_1,.daterange_duo_on_2').daterangepicker();
	
	// slider examples
	$( "#slider-range-min" ).slider({
		range: "min",
		value: 37,
		min: 1,
		max: 700,
		slide: function( event, ui ) {
			$( "#amount-slider-range-min" ).val( "$" + ui.value );
		}
	});
	$( "#amount-slider-range-min" ).val( "$" + $( "#slider-range-min" ).slider( "value" ) );
	
	$( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: 500,
		values: [ 75, 300 ],
		slide: function( event, ui ) {
			$( "#amount-slider-range" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		}
	});
	$( "#amount-slider-range" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
		" - $" + $( "#slider-range" ).slider( "values", 1 ) );
		
	$( "#slider-vertical" ).slider({
		orientation: "vertical",
		range: "min",
		min: 0,
		max: 100,
		value: 60,
		slide: function( event, ui ) {
			$( "#amount-slider-vertical" ).val( ui.value );
		}
	});
	$( "#amount-slider-vertical" ).val( $( "#slider-vertical" ).slider( "value" ) );
	
	$( "#slider-range-vertical" ).slider({
		orientation: "vertical",
		range: true,
		values: [ 17, 67 ],
		slide: function( event, ui ) {
			$( "#amount-slider-range-vertical" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		}
	});
	$( "#amount-slider-range-vertical" ).val( "$" + $( "#slider-range-vertical" ).slider( "values", 0 ) +
		" - $" + $( "#slider-range" ).slider( "values", 1 ) ); 
});
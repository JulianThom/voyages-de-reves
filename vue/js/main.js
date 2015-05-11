jQuery(function($){


	$.datepicker.setDefaults( $.datepicker.regional['fr'] );

	var datepickers = $('.datepicker').datepicker({
		minDate : 0,
		onSelect: function(date){
			var option = this.id == 'allee' ? 'minDate' : 'maxDate';
			datepickers.not('#'+this.id).datepicker('option',option,date);
		}
	})

});
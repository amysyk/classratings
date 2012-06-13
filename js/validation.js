
$(document).ready(function(){
	errornotice = $("#error");
	errornotice2 = $("#error2");

	$("#theform").submit(function(){
	errornotice.hide();
	errornotice2.hide();
	
		if ($("#reviewText").val().length > 1500) {
			errornotice.fadeIn (500);
			return false;
			} 
		else {
			if ($("#CurrentRating").data("stars").options.value == 0) {
				errornotice2.fadeIn (500);
				return false;
			}
			else {
				return true;
			}
			
		}
	});
});	
/*
 * I didn't know if I could use a plugin for this. So I did it by hand.
 */


function appendMessageToFormElement(element, message) {
	if($(element).next().prop('tagName') !== 'UL') {
		$(element).after('<ul><li>' + message + '</li></ul>'); //Just being consistent with Zend
	}
}

function removeErrorMessage(element) {
	 
	console.log($(element).next().prop('tagName'));
	if($(element).next().prop('tagName') == 'UL') {
			$(element).next().remove();
	 }
}

/**
 * There is probably a much better way to do this by iterating through the form elements, but time is short.
 */
$(document).ready(function() {

	//Country Validation
	$("#country").submit(function(event) {
		
		var error = false;
		
		
		if($("#countryCode").val().length != 3) {
			appendMessageToFormElement($("#countryCode"), 'Country Code needs to be 3 characthers long');
			error = true;
		} else removeErrorMessage($("#countryCode"));
		
		
		
		if($("#countryName").val().length < 3) {
			appendMessageToFormElement($("#countryName"), 'Country Name needs to be at least 3 characthers long');
			error = true;
		} else removeErrorMessage($("#countryName"));
		
		if($("#continentSelect").val() === '') {
			appendMessageToFormElement($("#continentSelect"), 'Please Select a continent');
			error = true;
		}  else removeErrorMessage($("#continentSelect"));
		
		if($("#population").val() === '' || isNaN($("#population").val())) {
			appendMessageToFormElement($("#population"), 'Please enter a number');
			error = true;
		} else removeErrorMessage($("#population"));
		
		
		if(error) {
			event.preventDefault();
		}
		
	});
	
	
	//City Validation
	$("#city").submit(function(event) {
		
		var error = false;
	
		if($("#cityName").val().length < 3) {
			appendMessageToFormElement($("#cityName"), 'City name needs to be 3 characthers long');
			error = true;
		} else removeErrorMessage($("#cityName"));
		
		
		if($("#countrySelect").val() === '') {
			appendMessageToFormElement($("#countrySelect"), 'Please Select a country');
			error = true;
		}  else removeErrorMessage($("#countrySelect"));
		
		if($("#district").val().length < 3) {
			appendMessageToFormElement($("#district"), 'District needs to be at least 3 characthers long');
			error = true;
		} else removeErrorMessage($("#cityName"));
		
		if($("#population").val() === '' || isNaN($("#population").val())) {
			appendMessageToFormElement($("#population"), 'Please enter a number');
			error = true;
		} else removeErrorMessage($("#population"));
		
		
		if(error) {
			event.preventDefault();
		}
	});
});
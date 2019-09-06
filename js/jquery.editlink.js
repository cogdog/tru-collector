/* TRU Collector Scripts 
   code by Alan Levine @cogdog http://cog.dog
*/

function emailInstructions( url ) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("getEditLinkResponse").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET", url, true);
	xmlhttp.send();
}

jQuery(document).ready(function() { 
	jQuery(document).on('click', '#getEditLink', function(e){

		// disable default behavior
		e.preventDefault();
		// initiate engines
		emailInstructions( jQuery( this ).data( 'widurl' ) );		
	});
});

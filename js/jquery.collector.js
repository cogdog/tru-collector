/* TRU Collector: TRU Collector  Scripts
   code by Alan Levine @cogdog http://cogdog.info

*/


function getAbsolutePath() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

jQuery(document).ready(function() {
	// called for via click of upload button in theme options

	jQuery('#splotdropzone input').change(function () {

		if (this.value) {
			// prompt for drop area

			// get the file size
			let file_size_MB = (this.files[0].size / 1000000.0).toFixed(2);

			if ( file_size_MB >  parseFloat(collectorObject.uploadMax)) {
            	alert('Error: The size of your image, ' + file_size_MB + ' Mb, is greater than the maximum allowed for this site (' + collectorObject.uploadMax + ' Mb). Try a different file or see if you can shrink the size of this one.');
            	jQuery('#wUploadImage').val("");
            } else {


            	jQuery('#wDefThumbURL').text( jQuery('#headerthumb').attr('src'));
				      jQuery('#dropmessage').text('Selected Image: ' + this.value.substring(12));

              // generate a preview of image in the thumbnail source
              // h/t https://codepen.io/waqasy/pen/rkuJf
              if (this.files && this.files[0]) {
                var freader = new FileReader();

                freader.onload = function (e) {
                  jQuery('#headerthumb').attr('src', e.target.result);
                };

                freader.readAsDataURL(this.files[0]);

                 // update status
                jQuery("#uploadresponse").html('Image selected. When you <strong>Save/Update</strong> below this file will be uploaded (' + file_size_MB + ' Mb).');

            } else {
              // no files received?
               reset_dropzone();
            }
			}

		} else {
			// cancel clicked
			reset_dropzone();
		}
	});


	jQuery("#headerthumb").click(function(){
		jQuery("#splotdropzone input").click();
	});

	function reset_dropzone() {
		//reset thumbnail preview
		jQuery('#headerthumb').attr('src', jQuery('#wDefThumbURL').text());

		// clear status field
		jQuery("#uploadresponse").text('');

		// reset drop zone prompt
		jQuery('#dropmessage').text('Drag file or click to select one to upload');

	}

	jQuery('#wTags').suggest( getAbsolutePath() + "wp-admin/admin-ajax.php?action=ajax-tag-search&tax=post_tag", {multiple:true, multipleSep: ","});


});

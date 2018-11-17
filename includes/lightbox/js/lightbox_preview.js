function wp_tags( taglist ) {
	var mystr = '';
	
	if (taglist === undefined) return '';
	
	var tagarray = taglist.split(',');
		
	for (i = 0; i < tagarray.length; i++) { 
		mystr += '<a href="#" rel="tag" class="label" onclick="return false;">' + tagarray[i] + '</a>, ';
    }
    return (mystr.substr(0, mystr.length-2)); 
}

function capitalizeEachWord(str) {
    return str.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}

function decodeEntities(input) {
  var y = document.createElement('textarea');
  y.innerHTML = input;
  return y.value;
}




function getLicenseName(lc) {

	var all_licenses = {
				'--' : 'No license selected',
				'u': 'Rights Status Unknown',
				'c': 'All Rights Reserved (copyrighted)',
				'pd'	: 'Public Domain',
				'cc0'	: 'CC0 No Rights Reserved',
				'cc-by': 'CC BY Creative Commons By Attribution',
				'cc-by-sa': 'CC BY SA Creative Commons Attribution-ShareAlike',
				'cc-by-nd': 'CC BY ND Creative Commons Attribution-NoDerivs',
				'cc-by-nc' : 'CC BY NC Creative Commons Attribution-NonCommercial',
				'cc-by-nc-sa' : 'CC BY NC SA Creative Commons Attribution-NonCommercial-ShareAlike',
				'cc-by-nc-nd' : 'CC By NC ND Creative Commons Attribution-NonCommercial-NoDerivs',
			}
	return ( all_licenses[lc]) ;
}

function get_attribution( license, work_title, work_creator) {
	// create an attribution string for the license

	var all_licenses = {
				'--' : 'No license selected',
				'u': 'Rights Status Unknown',
				'c': 'All Rights Reserved (copyrighted)',
				'pd'	: 'Public Domain',
				'cc0'	: 'CC0 No Rights Reserved',
				'cc-by': 'CC BY Creative Commons By Attribution',
				'cc-by-sa': 'CC BY SA Creative Commons Attribution-ShareAlike',
				'cc-by-nd': 'CC BY ND Creative Commons Attribution-NoDerivs',
				'cc-by-nc' : 'CC BY NC Creative Commons Attribution-NonCommercial',
				'cc-by-nc-sa' : 'CC BY NC SA Creative Commons Attribution-NonCommercial-ShareAlike',
				'cc-by-nc-nd' : 'CC By NC ND Creative Commons Attribution-NonCommercial-NoDerivs',
			}
	
	if ( work_creator == '') {
		var work_str =  '"' + work_title + '"';
	} else {
		var work_str = '"' + work_title + '" by or via "' + work_creator  + '" '
	}
		
	
	switch ( license ) {
	
		case '--': 
			return ( work_str +  '" license status: not selected.' );
			break;
			
		case '?': 	
			return ( work_str +  '" license status: unknown.' );
			break;
			
		case 'u': 	
			return ( work_str +   '" license status: unknown.' );
			break;
			
		case 'c': 	
			return ( work_str +  '" is &copy; All Rights Reserved.' );
			break;
		
		case 'cc0':
			return ( work_str +  ' is made available under the Creative Commons CC0 1.0 Universal Public Domain Dedication.');
			break;
	
		case 'pd':
			return ( work_str +  ' has been explicitly released into the public domain.');
			break;
		
		default:
			//find position in license where name of license starts
			var lstrx = all_licenses[license].indexOf('Creative Commons');
			
			return ( work_str + ' is licensed under a ' +  all_licenses[license].substring(lstrx)  + ' 4.0 International license.');
	}
}


function replaceURLWithHTMLLinks(text) {
	// h/t http://stackoverflow.com/a/19548526/2418186
    var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
    return text.replace(exp,"<a href='$1'>$1</a>"); 
}

function nl2br (str, is_xhtml) {
	// h/t http://stackoverflow.com/a/7467863/2418186
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}


(function($) {
 
    // Initialize the Lightbox for any links with the 'fancybox' class
	$(".fancybox").fancybox({

		fitToView	: false,
		maxWidth	: 0.85 * window.innerWidth,
		autoHeight	: true,
		autoSize	: false,
		closeClick	: false,
		openEffect  : 'fade',
		closeEffect : 'fade',
		scrolling   : 'yes',
		afterLoad   : function() {

			// flag for the editor type
			if ( $('#wRichText').val() == 1) {
				// get content from tinymce editor
				wtext =  tinymce.get('wTextHTML').getContent();
			} else {
				// get content from textarea
				wtext =  $('#wText').val();
			}

					
			if ( $('#wTags').val() == '') {
				tagd = ' ';
			} else {
				tagd = ', ';
			}
			            
            var wcats = [];
           
           $("input[name='wCats[]']:checked").each(function() {  
           		wcats.push($(this).parent().text());
            });
			
			// build output
			var wOutput = '<div class="post single" style="background-color:#f2f2f2;"><div class="content"><div class="featured-media"><img src="' + $('#wFeatureImageUrl').val()    + '" width="100%"></div><div style="background-color:#fff;padding: 7.5%;margin: 0 auto;"><div class="post-header"><h1 class="post-title">' + $('#wTitle').val() + '</h1></div></div><div class="post-content" style="background-color:#fff;;padding: 0 7.5%;margin:0 auto;"><p>' + wtext  + '</p><p><strong>Shared by </strong>' + $('#wAuthor').val() + '<br>' ;
			
			if ( $('#wLicense').val()) wOutput += '<strong>Reuse License:</strong> ' +  getLicenseName( $('#wLicense').val() )  + '<br />';
			
			
			if ( $('#wSource').val()) wOutput += '<strong>Image Credit:</strong> ' +  replaceURLWithHTMLLinks($('#wSource').val()) + '<br />';
			
			if ( $('#wAttributionPreview').val() == 1) wOutput += '<strong>Attribution Text:</strong><br /><textarea rows="2" onClick="this.select()" style="height:80px;">' +  get_attribution( $('#wLicense').val(), $('#wTitle').val(), $('#wSource').val()) + '</textarea><br />';
			
			wOutput += '</p><form><p><strong>Link to image:</strong></p><input type="text" class="form-control"  value="' + $('#wFeatureImageUrl').val()  + '" onClick="this.select();" /></form><p>&nbsp;</p></div><div class="clear"></div><div class="post-meta-bottom"><ul><li class="post-date"><a href="#">' + moment().format('MMMM D, YYYY')  + '</a></li>';
			
			// doth we have categories?
			if (wcats.length) wOutput += '<li class="post-categories">In: ' + wcats.join(", ") + '</li>';
			
			// doth we have tags?
			if ($('#wTags').val()) wOutput += '<li class="post-tags">Tagged: ' + wp_tags( $('#wTags').val() ) + tagd  + '</li>';
			wOutput += '</li></ul></div></div>';
			
			// set content
			this.content = wOutput;
			
		},
		helpers : {
			title: {
				type: 'outside',
				position: 'top'
			}
    	},
	}); 
	
})(jQuery);
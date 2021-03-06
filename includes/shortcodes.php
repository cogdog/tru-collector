<?php
# -----------------------------------------------------------------
# Shortcodes are friends to humans
# -----------------------------------------------------------------


add_shortcode("link", "trucollector_hyperlink");

function trucollector_hyperlink( $atts )  {
  	extract(shortcode_atts( array( "url" => '', "text" => '' ), $atts ));
  	
  	// make sure we have a URL that starts with http
	if ( strpos( $url, 'http') === 0) {
		// if no label use URL
		if ($text == '' ) $text = $url;
  	
 		 return '<a href="'. $url .'" target="_blank">'. $text . '</a>';
 	}
 	
 	return  $url;
 	
}


// shortcode for generating a list of content by license, useful for widgets

add_shortcode("licensed", "trucollector_license_list");

function trucollector_license_list( $atts )  {

	if ( trucollector_option('use_license') > 0 ) {
	
		extract(shortcode_atts( array( "show" => 'used' ), $atts ));

		// all allowable licenses for this theme
		$all_licenses = trucollector_get_licences();
		
		$output = '<ul>';
	
		foreach ( $all_licenses as $abbrev => $title) {
		
			// get number of items with this license
			$lcount = trucollector_get_license_count( $abbrev ); 
			
			// show if we have some
			if ( $lcount > 0 or $show == 'all'  ) {
				$output .=  '<li><a href="' . site_url() . '/licensed/' . $abbrev . '">' . $title . '</a> (' . $lcount . ")</li>\n";
			}
		}

		$output .=  '</ul>';
		
	} else {
	
		$output = 'The current settings for this site are to not use licenses; the site administrator can enable this feature from the <code>TRU Collector Options.</code>';
	}

	return $output;
}
?>
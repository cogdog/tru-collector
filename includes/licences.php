<?php

function trucollector_get_licences() {
	// return as an array the types of licenses available

	return ( array (
				'u' => 'Rights Status Unknown',
				'c' => 'All Rights Reserved (copyrighted)',
				'pd'	=> 'Public Domain',
				'cc0'	=> 'CC0 No Rights Reserved',
				'cc-by' => 'CC BY Creative Commons By Attribution',
				'cc-by-sa' => 'CC BY SA Creative Commons Attribution-ShareAlike',
				'cc-by-nd' => 'CC BY ND Creative Commons Attribution-NoDerivs',
				'cc-by-nc' => 'CC BY NC Creative Commons Attribution-NonCommercial',
				'cc-by-nc-sa' => 'CC BY NC SA Creative Commons Attribution-NonCommercial-ShareAlike',
				'cc-by-nc-nd' => 'CC By NC ND Creative Commons Attribution-NonCommercial-NoDerivs',
			)
		);
}

function trucollector_the_license( $lcode = '--' ) {
	// output the title of a license

	// passed by form with no menu selected
	if ($lcode != '--') {

		// get  possible licenses
		$all_licenses = trucollector_get_licences();

		if (array_key_exists( $lcode, $all_licenses ) ) {
			echo $all_licenses[$lcode];
		} else {
			echo 'no license found for key "' .  $lcode . '"';
		}
	} else {
		echo 'no license identified';

	}
}

function trucollector_get_the_license( $lcode = '--'  ) {

	// passed by form with no menu selected
	if ($lcode == '--') return '';

	// return the title of a license
	$all_licenses = trucollector_get_licences();

	return ($all_licenses[$lcode]);
}


function trucollector_get_license_count( $the_license ) {
	// get the number of items with a given license code

	// run a query based on post meta key/values

	$lic_query = new WP_Query( array( 'post_status' => 'publish', 'meta_key' => 'license', 'meta_value' =>  $the_license ) );

	// how many?
	return $lic_query->found_posts;

}

function trucollector_attributor( $license, $work_title, $work_creator='') {
	// create an attribution string for the license

	$all_licenses = trucollector_get_licences();

	$work_str = ( $work_creator == '') ? '"' . $work_title . '"' : '"' . $work_title . '" by ' . $work_creator;

	switch ( $license ) {

		case '?':
		case '--':
		case 'u':
			return ( $work_str .  ' has no known license status.' );
			break;

		case 'c':
			return ( $work_str .  ' is &copy; All Rights Reserved.' );
			break;

		case 'cc0':
			return ( $work_str . ' is made available under the Creative Commons CC0 1.0 Universal Public Domain Dedication.');
			break;

		case 'pd':
			return ( $work_str . ' has been explicitly released into the public domain.');
			break;

		default:
			//find position in license where name of license starts
			$lstrx = strpos( $all_licenses[$license] , 'Creative Commons');
			return ( $work_str . ' is licensed under a ' .  substr( $all_licenses[$license] , $lstrx)  . ' 4.0 International license.');
	}
}
?>

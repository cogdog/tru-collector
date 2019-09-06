<?php
# -----------------------------------------------------------------
# API stuff
# -----------------------------------------------------------------

// -----  expose post meta date to API
add_action( 'rest_api_init', 'trucollector_create_api_posts_meta_field' );
 
function trucollector_create_api_posts_meta_field() {
 
	register_rest_field( 'post', 'splot_meta', array(
								 'get_callback' => 'trucollector_get_splot_meta_for_api',
 								 'schema' => null,)
 	);
}
 
function trucollector_get_splot_meta_for_api( $object ) {
	//get the id of the post object array
	$post_id = $object['id'];

	// meta data fields we wish to make available
	$splot_meta_fields = ['author' => 'shared_by', 'license' => 'license', 'source' => 'source'];
	
	// array to hold stuff
	$splot_meta = [];
 
 	foreach ($splot_meta_fields as $meta_key =>  $meta_value) {
	 	//return the post meta for each field
	 	$splot_meta[$meta_key] =  get_post_meta( $post_id, $meta_value, true );
	 }
	 
	 return ($splot_meta);
 
} 



add_action( 'rest_api_init', function () {
  // redister a route for just random images, accept a paraemeter for the number of random images to fetch
  register_rest_route( 'splotcollector/v1', '/randy/(?P<n>\d+)', array(
    'methods' => 'GET',
    'callback' => 'trucollector_randy',
	 'args' => array(
		  'n' => array(
				'validate_callback' => function($param, $request, $key) {
			  	return is_numeric( $param );
				}
		  	), 
	 	)  
  	) );

  	
  // redister a route for pechaflickr requests, accept a paraemeter for the number of random images to fetch
  register_rest_route( 'splotcollector/v1', '/pechaflickr/(?P<n>\d+)/tag/(?P<tag>.*)', array(
    'methods' => 'GET',
    'callback' => 'trucollector_pechaflickr',
	 'args' => array(
		  'n' => array(
				'validate_callback' => function($param, $request, $key) {
			  	return is_numeric( $param );
				}
		  	), 
		  'tag' => array(
		  		'required' => false,
		  		'type' => 'string',
		  	), 
		  	
		  	
	 	)  
  	) );
  	
  	
  	
} );


function trucollector_randy( $data ) {
  // general function for getting random images, first test version
  
  // get specified random number of posts
 $posts = get_posts( array( 'orderby' => 'rand', 'posts_per_page' => $data['n']) );
  
  // bad news here
  if ( empty( $posts ) ) {
    return null;
  }
   
 // walk the results, add to array
  foreach ($posts as $item) {
  
  
  	// find code for license, if not present set to code for unknown
	$lic = ( get_post_meta( $item->ID, 'license', 1 ) ) ? get_post_meta( $item->ID, 'license', 1 ) : 'u';
	
  	$found[] = array(
  		'title' => $item->post_title,
  		'link' => get_permalink( $item->ID ),
  		'sharedby' => get_post_meta( $item->ID, 'shared_by', 1 ), 
		'license' => trucollector_get_the_license( $lic ),
		'images' => array(
			'thumb' => wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ), 'thumbnail')[0],
			'large' => wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ), 'large')[0]
		)
  	);
  }
 // server up some API goodness
 return new WP_REST_Response( $found, 200 );
}

function trucollector_pechaflickr( $data ) {
	  // get results for a pechaflickr request
  
	  // get specified random number of posts
	 $args = array( 
		'orderby' => 'rand', 
		'posts_per_page' => $data['n']
	 );
 
 	// check tag parameter, an "x" indicates no tags; otherwise add to query
	if (  $data['tag'] != 'x' )  $args['tag'] = strtolower($data['tag']);
 
	 $posts = get_posts( $args );
  
	// bad news here
	if ( empty( $posts ) ) {
	return null;
	}

	// not enough pictures found
	  if ( count($posts) < $data['n'] ) {
		$response = array(
			'Success' => false,
			'Message' => 'Not enough images found'
		);
  	
  	
	  } else {
 	
	 // we got results, walk and add to array
	  foreach ($posts as $item) {
	
		// find code for license, if not present set to code for unknown
		$lic = ( get_post_meta( $item->ID, 'license', 1 ) ) ? get_post_meta( $item->ID, 'license', 1 ) : 'u';
  
  		// assemble data
		$found[] = array(
			'title' => $item->post_title,
			'url' => get_permalink( $item->ID ),
			'shared_by' => get_post_meta( $item->ID, 'shared_by', 1 ), 
			'license' => trucollector_get_the_license( $lic ),
			'images' => array(
				'thumb' => wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ), 'thumbnail')[0],
				'large' => wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ), 'large')[0]
			)
		);
	  }
	  
	$response = array(
  		'Success' => true,
  		'Message' => '',
  		'Results' => $found
  	);

	  
  } // if 
 // server up some API goodness
 return new WP_REST_Response( $response, 200 );
}
?>
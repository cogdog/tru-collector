<?php
# -----------------------------------------------------------------
# Theme activation, let's go!
# -----------------------------------------------------------------

// run when this theme is activated
add_action('after_switch_theme', 'trucollector_setup');

function trucollector_setup () {
  
	// create special pages if they do not exist
	// backdate creation date 2 days just to make sure they do not end up future dated
	// which causes all kinds of disturbances in the force

  if (! get_page_by_path( 'collect' ) ) {
  
  	// create the Collect form page if it does not exist
  	$page_data = array(
  		'post_title' 	=> 'Collect',
  		'post_content'	=> 'Here is the place to add a new photo to this collection. If you are building this site, maybe edit this page to make it special.',
  		'post_name'		=> 'collect',
  		'post_status'	=> 'publish',
  		'post_type'		=> 'page',
  		'post_author' 	=> 1,
  		'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
  		'page_template'	=> 'page-collect.php',
  	);
  	
  	wp_insert_post( $page_data );
  
  }

  if (! get_page_by_path( 'desk' ) ) {

  	// create the welcome desk page if it does not exist
  	$page_data = array(
  		'post_title' 	=> 'Welcome Desk',
  		'post_content'	=> 'You are but one special key word away from being able to add images to this collection. Hopefully the kind owner of this site has provided you the key phrase. Spelling and capitalization do count. If you are said owner, editing this page will let you personalize this bit.',
  		'post_name'		=> 'desk',
  		'post_status'	=> 'publish',
  		'post_type'		=> 'page',
  		'post_author' 	=> 1,
  		'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
  		'page_template'	=> 'page-desk.php',
  	);
  	
  	wp_insert_post( $page_data );
  
  }

  if (! get_page_by_path( 'random' ) ) {

  	// create the Write page if it does not exist
  	$page_data = array(
  		'post_title' 	=> 'Random',
  		'post_content'	=> 'You should never see this page, it is for random redirects. What are you doing looking at this page? Get back to writing, willya?',
  		'post_name'		=> 'random',
  		'post_status'	=> 'publish',
  		'post_type'		=> 'page',
  		'post_author' 	=> 1,
  		'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
  		'page_template'	=> 'page-random.php',
  	);
  	
  	wp_insert_post( $page_data );
  
  }


  if (! get_page_by_path( 'licensed' ) ) {
  
  	// create index page and archive for licenses.
  	
  	$page_data = array(
  		'post_title' 	=> 'Items by License',
  		'post_content'	=> 'Browse the items in this collection by license for reuse',
  		'post_name'		=> 'licensed',
  		'post_status'	=> 'publish',
  		'post_type'		=> 'page',
  		'post_author' 	=> 1,
  		'post_date' 	=> date('Y-m-d H:i:s', time() - 172800),
  		'page_template'	=> 'page-licensed.php',
  	);
  	
  	wp_insert_post( $page_data );
  
  }
   
}


# -----------------------------------------------------------------
# Set up the table and put the napkins out
# -----------------------------------------------------------------

// we need to load the options this before the auto login so we can use the pass
add_action( 'after_setup_theme', 'trucollector_load_theme_options', 9 );

// change the name of admin menu items from "New Posts"
// -- h/t http://wordpress.stackexchange.com/questions/8427/change-order-of-custom-columns-for-edit-panels
// and of course the Codex http://codex.wordpress.org/Function_Reference/add_submenu_page

add_action( 'admin_menu', 'trucollector_change_post_label' );
add_action( 'init', 'trucollector_change_post_object' );

// turn 'em from Posts to Collectables
function trucollector_change_post_label() {
    global $menu;
    global $submenu;
    
    $thing_name = 'Collectable';
    
    $menu[5][0] = $thing_name . 's';
    $submenu['edit.php'][5][0] = 'All ' . $thing_name . 's';
    $submenu['edit.php'][10][0] = 'Add ' . $thing_name;
    $submenu['edit.php'][15][0] = $thing_name .' Categories';
    $submenu['edit.php'][16][0] = $thing_name .' Tags';
    echo '';
    
    
    add_submenu_page('edit.php', 'Collectable for Review', 'Collectable for Review', 'edit_pages', 'edit.php?post_status=draft&post_type=post' ); 
}

// change the prompts and stuff for posts to be relevant to collectables
function trucollector_change_post_object() {

    $thing_name = 'Collectable';

    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name =  $thing_name . 's';;
    $labels->singular_name =  $thing_name;
    $labels->add_new = 'Add ' . $thing_name;
    $labels->add_new_item = 'Add ' . $thing_name;
    $labels->edit_item = 'Edit ' . $thing_name;
    $labels->new_item =  $thing_name;
    $labels->view_item = 'View ' . $thing_name;
    $labels->search_items = 'Search ' . $thing_name;
    $labels->not_found = 'No ' . $thing_name . ' found';
    $labels->not_found_in_trash = 'No ' .  $thing_name . ' found in Trash';
    $labels->all_items = 'All ' . $thing_name;
    $labels->menu_name =  $thing_name;
    $labels->name_admin_bar =  $thing_name;
}

// edit the post editing admin messages to reflect use of Collectables
// h/t http://www.joanmiquelviade.com/how-to-change-the-wordpress-post-updated-messages-of-the-edit-screen/

function trucollector_post_updated_messages ( $msg ) {
    $msg[ 'post' ] = array (
         0 => '', // Unused. Messages start at index 1.
	 1 => "Collectable updated.",
	 2 => 'Custom field updated.',  // Probably better do not touch
	 3 => 'Custom field deleted.',  // Probably better do not touch

	 4 => "Collectable updated.",
	 5 => "Collectable restored to revision",
	 6 => "Collectable published.",

	 7 => "Collectable saved.",
	 8 => "Collectable submitted.",
	 9 => "Collectable scheduled.",
	10 => "Collectable draft updated.",
    );
    return $msg;
}

add_filter( 'post_updated_messages', 'trucollector_post_updated_messages', 10, 1 );

// modify the comment form
add_filter('comment_form_defaults', 'trucollector_comment_mod');

function trucollector_comment_mod( $defaults ) {
	$defaults['title_reply'] = 'Provide Feedback';
	$defaults['logged_in_as'] = '';
	$defaults['title_reply_to'] = 'Provide Feedback for %s';
	return $defaults;
}

// -----  add allowable url parameters
add_filter('query_vars', 'trucollector_queryvars' );

function trucollector_queryvars( $qvars ) {
	$qvars[] = 'flavor'; // flag for type of license
	
	return $qvars;
}   

// -----  rewrite rules for licensed pretty urls
add_action('init', 'trucollector_rewrite_rules', 10, 0); 
      
function trucollector_rewrite_rules() {
	$license_page = get_page_by_path('licensed');
	
	if ( $license_page ) {
		add_rewrite_rule( '^licensed/([^/]*)/?',  'index.php?page_id=' . $license_page->ID . '&flavor=$matches[1]','top');	
	}	
}



// options for post order on front page
add_action( 'pre_get_posts', 'trucollector_order_items' );

function trucollector_order_items( $query ) {

	if ( ( $query->is_home() && $query->is_main_query()) OR $query->is_archive() OR $query->is_search() ) {
	
		$query->set( 'orderby', trucollector_option('sort_by')  );
		$query->set( 'order', trucollector_option('sort_direction') );
		
	}
}

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

function trucollector_the_license( $lcode ) {
	// output the ttitle of a license
	$all_licenses = trucollector_get_licences();
	
	echo $all_licenses[$lcode];
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
		
	$work_str = ( $work_creator == '') ? '"' . $work_title . '"' : '"' . $work_title . '" by or via "' . $work_creator  . '" ';
	
	switch ( $license ) {
	
		case '?': 	
			return ( $work_str .  '" license status: unknown.' );
			break;
			
		case 'u': 	
			return ( $work_str .  '" license status: unknown.' );
			break;
			
		case 'c': 	
			return ( $work_str .  '" is &copy; All Rights Reserved.' );
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

# -----------------------------------------------------------------
# Menu Setup
# -----------------------------------------------------------------

// checks to see if a menu location is used.
function splot_is_menu_location_used( $location = 'primary' ) {	

	// get locations of all menus
	$menulocations = get_nav_menu_locations();
	
	// get all nav menus
	$navmenus = wp_get_nav_menus();
	
	
	// if either is empty we have no menus to use
	if ( empty( $menulocations ) OR empty( $navmenus ) ) return false;
	
	// othewise look for the menu location in the list
	return in_array( $location , $menulocations);
}

// create a basic menu if one has not been define for primary
function splot_default_menu() {

	// site home with trailing slash
	$splot_home = site_url('/');
  
 	return ( '<li><a href="' . $splot_home . '">Home</a></li><li><a href="' . $splot_home . 'collect' . '">Collect</a></li><li><a href="' . $splot_home . 'random' . '">Random</a></li>' );
  
}

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


# -----------------------------------------------------------------
# Options Panel for Admin
# -----------------------------------------------------------------

// -----  Add admin menu link for Theme Options
add_action( 'wp_before_admin_bar_render', 'trucollector_options_to_admin' );

function trucollector_options_to_admin() {
    global $wp_admin_bar;
    
    // we can add a submenu item too
    $wp_admin_bar->add_menu( array(
        'parent' => '',
        'id' => 'trucollector-options',
        'title' => __('TRU Collector Options'),
        'href' => admin_url( 'themes.php?page=trucollector-options')
    ) );
}


function trucollector_enqueue_options_scripts() {
	// Set up javascript for the theme options interface
	
	// media scripts needed for wordpress media uploaders
	wp_enqueue_media();
	
	// custom jquery for the options admin screen
	wp_register_script( 'trucollector_options_js' , get_stylesheet_directory_uri() . '/js/jquery.trucollector-options.js', null , '1.0', TRUE );
	wp_enqueue_script( 'trucollector_options_js' );
}

function trucollector_load_theme_options() {
	// load theme options settings

	if ( file_exists( get_stylesheet_directory()  . '/class.trucollector-theme-options.php' ) ) {
		include_once( get_stylesheet_directory()  . '/class.trucollector-theme-options.php' );		
	}
	
	
}


# -----------------------------------------------------------------
# login stuff
# -----------------------------------------------------------------

// Add custom logo to entry screen... because we can
// While we are at it, use CSS to hide the back to blog and retried password links

// If you hate the splot logo, change the file in the theme directory images/site-login-logo.png
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/site-login-logo.png);
            padding-bottom: 30px;
        }    
	#backtoblog {display:none;}
	#nav {display:none;}
    </style>
<?php }


// Make logo link points to blog, not Wordpress.org Change Dat!
// -- h/t http://www.sitepoint.com/design-a-stylized-custom-wordpress-login-screen/

add_filter( 'login_headerurl', 'login_link' );

function login_link( $url ) {
	return get_bloginfo( 'url' );
}
 
 
// Auto Login

function splot_redirect_url() {
	// where to send them after login ok
	return ( site_url('/') . 'collect' );
}

function splot_user_login( $user_login = 'collector' ) {
	// login the special user account to allow authoring
	
	// check for the correct user
	$autologin_user = get_user_by( 'login', $user_login ); 
	
	if ( $autologin_user ) {
	
		// just in case we have old cookies
		wp_clear_auth_cookie(); 
		
		// set the user directly
		wp_set_current_user( $autologin_user->id, $autologin_user->user_login );
		
		// new cookie
		wp_set_auth_cookie( $autologin_user->id);
		
		// do the login
		do_action( 'wp_login', $autologin_user->user_login );
		
		// send 'em on their way
		wp_redirect( splot_redirect_url() );
		
		
	} else {
		// uh on, problem
		die ('Bad news. Looks like there is a missing account for "' . $user_login . '".');
	
	}
	


}

// remove admin tool bar for non-admins, remove access to dashboard
// -- h/t http://www.wpbeginner.com/wp-tutorials/how-to-disable-wordpress-admin-bar-for-all-users-except-administrators/

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
	if ( !current_user_can('edit_others_posts')  ) {
	  show_admin_bar(false);
	}

}

# -----------------------------------------------------------------
# Customizer Stuff
# -----------------------------------------------------------------

add_action( 'customize_register', 'trucollector_register_theme_customizer' );


function trucollector_register_theme_customizer( $wp_customize ) {
	// Create custom panel.
	$wp_customize->add_panel( 'customize_collector', array(
		'priority'       => 500,
		'theme_supports' => '',
		'title'          => __( 'TRU Collector', 'fukasawa'),
		'description'    => __( 'Customizer Stuff', 'fukasawa'),
	) );

	// Add section for the collect form
	$wp_customize->add_section( 'collect_form' , array(
		'title'    => __('Collect Form','fukasawa'),
		'panel'    => 'customize_collector',
		'priority' => 10
	) );
	
	// Add setting for default prompt
	$wp_customize->add_setting( 'default_prompt', array(
		 'default'           => __( 'Add something to this collection? Yes! Use the form below to share it', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Add control for default prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'default_prompt',
		    array(
		        'label'    => __( 'Default Prompt', 'fukasawa'),
		        'priority' => 10,
		        'description' => __( 'The opening message above the form.' ),
		        'section'  => 'collect_form',
		        'settings' => 'default_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);
	
	// setting for title label
	$wp_customize->add_setting( 'item_title', array(
		 'default'           => __( 'Title for this Item', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control fortitle label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_title',
		    array(
		        'label'    => __( 'Title Label', 'fukasawa'),
		        'priority' => 11,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_title',
		        'type'     => 'text'
		    )
	    )
	);
	
	// setting for title description
	$wp_customize->add_setting( 'item_title_prompt', array(
		 'default'           => __( 'Enter a descriptive title that works well as a headline when listed in this site.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for title description
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_title_prompt',
		    array(
		        'label'    => __( 'Title Prompt', 'fukasawa'),
		        'priority' => 12,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_title_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);
	
	// setting for image upload label
	$wp_customize->add_setting( 'item_upload', array(
		 'default'           => __( 'Upload an Image for this Item', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for image upload  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_upload',
		    array(
		        'label'    => __( 'Image Upload Label', 'fukasawa'),
		        'priority' => 13,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_upload',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for image upload prompt
	$wp_customize->add_setting( 'item_upload_prompt', array(
		 'default'           => __( 'Upload an image by dragging its icon to the window that opens when clicking  "Select Image" button. Larger JPG, PNG images are best. To preserve animation, GIFs should be no larger than 500px wide.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for image upload prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_upload_prompt',
		    array(
		        'label'    => __( 'Image Upload Prompt', 'fukasawa'),
		        'priority' => 14,
		        'description' => __( 'Directions for image uploads' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_upload_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for author  label
	$wp_customize->add_setting( 'item_author', array(
		 'default'           => __( 'Who is Uploading the Item?', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for author  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_author',
		    array(
		        'label'    => __( 'Credit Label', 'fukasawa'),
		        'priority' => 15,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_author',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for author  label prompt
	$wp_customize->add_setting( 'item_author_prompt', array(
		 'default'           => __( 'Take credit for sharing this item by entering your name(s),  twitter handle(s), or remain "Anonymous".', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for author  label prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_author_prompt',
		    array(
		        'label'    => __( 'Image Upload Prompt', 'fukasawa'),
		        'priority' => 16,
		        'description' => __( 'Directions for the author/uploader credit' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_author_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for description  label
	$wp_customize->add_setting( 'item_description', array(
		 'default'           => __( 'Item Description', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for description  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_description', 
		    array(
		        'label'    => __( 'Description Label', 'fukasawa'),
		        'priority' => 20,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_description',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for description  label prompt
	$wp_customize->add_setting( 'item_description_prompt', array(
		 'default'           => __( 'Enter a descriptive caption to include with the item.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for description  label prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_description_prompt',
		    array(
		        'label'    => __( 'Item Description Prompt', 'fukasawa'),
		        'priority' => 22,
		        'description' => __( 'Directions for the description entry field' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_description_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for image source  label
	$wp_customize->add_setting( 'item_image_source', array(
		 'default'           => __( 'Source of Image', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for image source  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_image_source',
		    array(
		        'label'    => __( 'Image Source Label', 'fukasawa'),
		        'priority' => 24,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_image_source',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for image source  prompt
	$wp_customize->add_setting( 'item_image_source_prompt', array(
		 'default'           => __( 'Enter name of a person, web site, etc to give credit for the image submitted above.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for image source prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_image_source_prompt',
		    array(
		        'label'    => __( 'Image Source Prompt', 'fukasawa'),
		        'priority' => 26,
		        'description' => __( 'Directions for the image source field' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_image_source_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for license  label
	$wp_customize->add_setting( 'item_license', array(
		 'default'           => __( 'License for Reuse', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for license  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_license',
		    array(
		        'label'    => __( 'License Label', 'fukasawa'),
		        'priority' => 27,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_license',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for license  prompt
	$wp_customize->add_setting( 'item_license_prompt', array(
		 'default'           => __( 'Indicate a reuse license associated with the image. If this is your own image,  select a license to share it under.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for license prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_license_prompt',
		    array(
		        'label'    => __( 'Image License Prompt', 'fukasawa'),
		        'priority' => 28,
		        'description' => __( 'Directions for the license selection' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_license_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for categories  label
	$wp_customize->add_setting( 'item_categories', array(
		 'default'           => __( 'Categories', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for categories  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_categories',
		    array(
		        'label'    => __( 'Categories Label', 'fukasawa'),
		        'priority' => 30,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_categories',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for categories  prompt
	$wp_customize->add_setting( 'item_categories_prompt', array(
		 'default'           => __( 'Check all categories that will help organize this item.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for categories prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_categories_prompt',
		    array(
		        'label'    => __( 'Categories Prompt', 'fukasawa'),
		        'priority' => 32,
		        'description' => __( 'Directions for the categories selection' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_categories_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);
	
	// setting for tags  label
	$wp_customize->add_setting( 'item_tags', array(
		 'default'           => __( 'Tags', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for tags  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_tags',
		    array(
		        'label'    => __( 'Tags Label', 'fukasawa'),
		        'priority' => 32,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_tags',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for tags  prompt
	$wp_customize->add_setting( 'item_tags_prompt', array(
		 'default'           => __( 'Add any descriptive tags for this item. Separate multiple ones with commas.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for tags prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_tags_prompt',
		    array(
		        'label'    => __( 'Tags Prompt', 'fukasawa'),
		        'priority' => 34,
		        'description' => __( 'Directions for  tags entry' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_tags_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);

	// setting for editor notes  label
	$wp_customize->add_setting( 'item_editor_notes', array(
		 'default'           => __( 'Notes to the Editor', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for editor notes  label
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_editor_notes',
		    array(
		        'label'    => __( 'Editor Notes Label', 'fukasawa'),
		        'priority' => 36,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_editor_notes',
		        'type'     => 'text'
		    )
	    )
	);

	// setting for editor notes  prompt
	$wp_customize->add_setting( 'item_editor_notes_prompt', array(
		 'default'           => __( 'Add any notes or messages to send to the site manager; this will not be part of what is published. If you wish to be contacted, leave an email address or twitter handle.', 'fukasawa'),
		 'type' => 'theme_mod',
		 'sanitize_callback' => 'sanitize_text'
	) );
	
	// Control for editor notes prompt
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'item_editor_notes_prompt',
		    array(
		        'label'    => __( 'Editor Notes Prompt', 'fukasawa'),
		        'priority' => 38,
		        'description' => __( '' ),
		        'section'  => 'collect_form',
		        'settings' => 'item_editor_notes_prompt',
		        'type'     => 'textarea'
		    )
	    )
	);
			
 	// Sanitize text
	function sanitize_text( $text ) {
	    return sanitize_text_field( $text );
	}
}


function trucollector_form_default_prompt() {
	 if ( get_theme_mod( 'default_prompt') != "" ) {
	 	return get_theme_mod( 'default_prompt');
	 }	else {
	 	return 'Add something to this collection? Yes! Use the form below to share it';
	 }
}

function trucollector_form_item_title() {
	 if ( get_theme_mod( 'item_title') != "" ) {
	 	echo get_theme_mod( 'item_title');
	 }	else {
	 	echo 'Title for this Item';
	 }
}

function trucollector_form_item_title_prompt() {
	 if ( get_theme_mod( 'item_title_prompt') != "" ) {
	 	echo get_theme_mod( 'item_title_prompt');
	 }	else {
	 	echo 'Enter a descriptive title that works well as a headline when listed in this site.';
	 }
}

function trucollector_form_item_upload() {
	 if ( get_theme_mod( 'item_upload') != "" ) {
	 	echo get_theme_mod( 'item_upload');
	 }	else {
	 	echo 'Upload an Image for this Item';
	 }
}

function trucollector_form_item_upload_prompt() {
	 if ( get_theme_mod( 'item_upload_prompt') != "" ) {
	 	echo get_theme_mod( 'item_upload_prompt');
	 }	else {
	 	echo 'Upload an image by dragging its icon to the window that opens when clicking  "Select Image" button. Larger JPG, PNG images are best. To preserve animation, GIFs should be no larger than 500px wide.';
	 }
}

function trucollector_form_item_author() {
	 if ( get_theme_mod( 'item_author') != "" ) {
	 	echo get_theme_mod( 'item_author');
	 }	else {
	 	echo 'Who is Uploading the Item?';
	 }
}

function trucollector_form_item_author_prompt() {
	 if ( get_theme_mod( 'item_author_prompt') != "" ) {
	 	echo get_theme_mod( 'item_author_prompt');
	 }	else {
	 	echo 'Take credit for sharing this item by entering your name(s),  twitter handle(s), or remain "Anonymous".';
	 }
}

function trucollector_form_item_description() {
	 if ( get_theme_mod( 'item_description') != "" ) {
	 	echo get_theme_mod( 'item_description');
	 }	else {
	 	echo 'Description Label';
	 }
}

function trucollector_form_item_description_prompt() {
	 if ( get_theme_mod( 'item_description_prompt') != "" ) {
	 	echo get_theme_mod( 'item_description_prompt');
	 }	else {
	 	echo 'Enter a descriptive caption to include with the item.';
	 }
}

function trucollector_form_item_image_source() {
	 if ( get_theme_mod( 'item_image_source') != "" ) {
	 	echo get_theme_mod( 'item_image_source');
	 }	else {
	 	echo 'Source of Image';
	 }
}

function trucollector_form_item_image_source_prompt() {
	 if ( get_theme_mod( 'item_image_source_prompt') != "" ) {
	 	echo get_theme_mod( 'item_image_source_prompt');
	 }	else {
	 	echo 'Enter name of a person, web site, etc to give credit for the image submitted above.';
	 }
}

function trucollector_form_item_license() {
	 if ( get_theme_mod( 'item_license') != "" ) {
	 	echo get_theme_mod( 'item_license');
	 }	else {
	 	echo 'Item License';
	 }
}

function trucollector_form_item_license_prompt() {
	 if ( get_theme_mod( 'item_license_prompt') != "" ) {
	 	echo get_theme_mod( 'item_license_prompt');
	 }	else {
	 	echo 'Select the appropriate reuse license for this item.';
	 }
}

function trucollector_form_item_categories() {
	 if ( get_theme_mod( 'item_categories') != "" ) {
	 	echo get_theme_mod( 'item_categories');
	 }	else {
	 	echo 'Categories';
	 }
}

function trucollector_form_item_categories_prompt() {
	 if ( get_theme_mod( 'item_categories_prompt') != "" ) {
	 	echo get_theme_mod( 'item_categories_prompt');
	 }	else {
	 	echo 'Check all categories that will help organize this item.';
	 }
}

function trucollector_form_item_tags() {
	 if ( get_theme_mod( 'item_tags') != "" ) {
	 	echo get_theme_mod( 'item_tags');
	 }	else {
	 	echo 'Tags';
	 }
}

function trucollector_form_item_tags_prompt() {
	 if ( get_theme_mod( 'item_tags_prompt') != "" ) {
	 	echo get_theme_mod( 'item_tags_prompt');
	 }	else {
	 	echo 'Add any descriptive tags for this item. Separate multiple ones with commas.';
	 }
}


function trucollector_form_item_editor_notes() {
	 if ( get_theme_mod( 'item_editor_notes') != "" ) {
	 	echo get_theme_mod( 'item_editor_notes');
	 }	else {
	 	echo 'Notes to the Editor';
	 }
}

function trucollector_form_item_editor_notes_prompt() {
	 if ( get_theme_mod( 'item_editor_notes_prompt') != "" ) {
	 	echo get_theme_mod( 'item_editor_notes_prompt');
	 }	else {
	 	echo 'Add any notes or messages to send to the site manager; this will not be part of what is published. If you wish to be contacted, leave an email address or twitter handle.';
	 }
}



# -----------------------------------------------------------------
# For the Collection Form
# -----------------------------------------------------------------

add_action('wp_enqueue_scripts', 'add_trucollector_scripts');

function add_trucollector_scripts() {	 
 
 
 	// do your parents have style?
    $parent_style = 'fukasawa_style'; 
    
    // load 'em
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    
    // kids are next
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );


 	if ( is_page('collect') ) { // use on just our form page
    
		 // add media scripts if we are on our maker page and not an admin
		 // after http://wordpress.stackexchange.com/a/116489/14945
    	 
		if (! is_admin() ) wp_enqueue_media();
		
		// Build in tag auto complete script
   		wp_enqueue_script( 'suggest' );

		// custom jquery for the uploader on the form
		wp_register_script( 'jquery.collector' , get_stylesheet_directory_uri() . '/js/jquery.collector.js', null , '1.0', TRUE );
		wp_enqueue_script( 'jquery.collector' );
		
	}

}

# -----------------------------------------------------------------
# Useful spanners and wrenches
# -----------------------------------------------------------------


function get_attachment_caption_by_id( $post_id ) {
	// function to get the caption for an attachment (stored as post_excerpt)
	// -- h/t http://wordpress.stackexchange.com/a/73894/14945

    $the_attachment = get_post( $post_id );
    return ( $the_attachment->post_excerpt ); 
}

function trucollector_author_user_check( $expected_user = 'collector' ) {
	// checks for the proper authoring account set up

	$auser = get_user_by( 'login', $expected_user );
		
	if ( !$auser) {
		return ('Authoring account not set up. You need to <a href="' . admin_url( 'user-new.php') . '">create a user account</a> with login name <strong>' . $expected_user . '</strong> with a role of <strong>Author</strong>. Make a killer strong password; no one uses it.');
	} elseif ( $auser->roles[0] != 'author') {
	
		// for multisite lets check if user is not member of blog
		if ( is_multisite() AND !is_user_member_of_blog( $auser->ID, get_current_blog_id() ) )  {
			return ('The user account <strong>' . $expected_user . '</strong> is set up but has not been added as a user to this site (and needs to have a role of <strong>Author</strong>). You can <a href="' . admin_url( 'user-edit.php?user_id=' . $auser->ID ) . '">edit it now</a>'); 
			
		} else {
		
			return ('The user account <strong>' . $expected_user . '</strong> is set up but needs to have it\'s role set to <strong>Author</strong>. You can <a href="' . admin_url( 'user-edit.php?user_id=' . $auser->ID ) . '">edit it now</a>'); 
		}
		
	} else {
		return ('The authoring account <strong>' .$expected_user . '</strong> is correctly set up.');
	}
}

function splot_jetpack_post_email_check ( ) {
// returns a status check for the Jetpack plugin and that post by email module is active

	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'post-by-email' ) ) {
		return  ('The Jetpack plugin is installed and Post By Email module is active. You may proceed to setup posting by email. You will need to install and/or activate a plugin that automatically creates thumbnails from post images such as <a href="https://wordpress.org/plugins/auto-thumbnailer/" target="_blank">Auto Thumbnailer</a>.'); 
	} else {
		return  ('The Jetpack plugin is <strong>not installed</strong> or the Post By Email module is <strong>not active</strong>. Check your  <a href="' . admin_url( 'plugins.php') . '">plugins</a>  or JetPack settings'); 
	}
}


function trucollector_check_user( $allowed='collector' ) {
	// checks if the current logged in user is who we expect
   $current_user = wp_get_current_user();
	
	// return check of match
	return ( strtolower( $current_user->user_login ) == $allowed );
}

function splot_the_author() {
	// utility to put in template to show status of special logins
	// nothing is printed if there is not current user, 
	//   echos (1) if logged in user is the special account
	//   echos (0) if logged in user is the another account
	//   in both cases the code is linked to a logout script

	if ( is_user_logged_in() and !current_user_can( 'edit_others_posts' ) ) {
		$user_code = ( trucollector_check_user() ) ? 1 : 0;
		echo '<a href="' . wp_logout_url( site_url() ). '">(' . $user_code  .')</a>';
	}

}

function set_html_content_type() {
	// from http://codex.wordpress.org/Function_Reference/wp_mail
	return 'text/html';
}

function br2nl ( $string )
// from http://php.net/manual/en/function.nl2br.php#115182
{
    return preg_replace('/\<br(\s*)?\/?\>/i', PHP_EOL, $string);
}

function make_links_clickable( $text ) {
//----	h/t http://stackoverflow.com/a/5341330/2418186
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
}


# -----------------------------------------------------------------
# API stuff
# -----------------------------------------------------------------

add_action( 'rest_api_init', function () {
	// redister the route, accept a paraemeter for the number of images to fetch
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
} );

function trucollector_randy( $data ) {
  
  // get specified random number of posts
 $posts = get_posts( array( 'orderby' => 'rand', 'posts_per_page' => $data['n']) );
  
  // bad news here
  if ( empty( $posts ) ) {
    return null;
  }
 
 // walk the results, add to array
  foreach ($posts as $item) {
  	$found[] = array(
  		'title' => $item->post_title,
  		'link' => get_permalink( $item->ID ),
  		'featuredimg' => wp_get_attachment_url( get_post_thumbnail_id( $item->ID ), 'thumbnail' )
  	);
  }
 // server up some API goodness
 return new WP_REST_Response( $found, 200 );
}

// Load plugin requirements file to display admin notices.
require get_stylesheet_directory() . '/inc/splot-plugins.php';


?>
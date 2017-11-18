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

// modify the comment form
add_filter('comment_form_defaults', 'trucollector_comment_mod');

function trucollector_comment_mod( $defaults ) {
	$defaults['title_reply'] = 'Provide Feedback';
	$defaults['logged_in_as'] = '';
	$defaults['title_reply_to'] = 'Provide Feedback for %s';
	return $defaults;
}


function trucollector_get_licences() {
	// return as an array the types of licenses available
	
	return ( array (
				'?' => 'Rights Status Unknown',
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

function trucollector_attributor( $license, $work_title, $work_creator='') {
	// create an attribution string for the license

	$all_licenses = trucollector_get_licences();
		
	$work_str = ( $work_creator == '') ? '"' . $work_title . '"' : '"' . $work_title . '" by or via "' . $work_creator  . '" ';
	
	switch ( $license ) {
	
		case '?': 	
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
// create a link that can automatically log in as a specific user, bypass login screen
// -- h/t  http://www.wpexplorer.com/automatic-wordpress-login-php/

add_action( 'after_setup_theme', 'trucollector_autologin');

function trucollector_autologin() {
	
	// URL Paramter to check for to trigger login
	if ($_GET['autologin'] == 'collector') {
	
		// change to short auto logout time
		add_filter( 'auth_cookie_expiration', 'trucollector_change_cookie_logout', 99, 3 );

		// ACCOUNT USERNAME TO LOGIN TO
		$creds['user_login'] = 'collector';
		
		// ACCOUNT PASSWORD TO USE- stored as option
		$creds['user_password'] = trucollector_option('pkey');

			
		$creds['remember'] = true;
		
		// login user, send secure cookie if this is on https
		$autologin_user = wp_signon( $creds, is_ssl() );
			
		if ( !is_wp_error($autologin_user) ) 
			wp_redirect ( site_url() . '/collect' );
	}
}

// not sure if this works to shorten the logout time
function trucollector_change_cookie_logout( $expiration, $user_id, $remember ) {
	if ( current_user_can( 'edit_pages' )  ) {
		// bump up default 14 day logout function 
    	return $remember ? $expiration : 1209600; 
    } else {
    	// shorter auto logout for guests (1 hour)
      	return $remember ? $expiration : 3600; 
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
# For the Collection Form
# -----------------------------------------------------------------

add_action('wp_enqueue_scripts', 'add_trucollector_scripts');

function add_trucollector_scripts() {	 
 
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
	global $current_user;
    get_currentuserinfo();
	
	// return check of match
	return ( $current_user->user_login == $allowed );
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

?>
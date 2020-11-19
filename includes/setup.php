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

  if (! page_with_template_exists( 'page-collect.php' ) ) {
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

 if (! page_with_template_exists( 'page-desk.php' ) ) {

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

	if (! page_with_template_exists( 'page-licensed.php' ) ) {

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

  flush_rewrite_rules();

}


# -----------------------------------------------------------------
# Set up the table and put the napkins out
# -----------------------------------------------------------------

// we need to load the options this before the auto login so we can use the pass
add_action( 'after_setup_theme', 'trucollector_load_theme_options', 9 );

// change the name of admin menu items from "New Posts"
// -- h/t https://wordpress.stackexchange.com/a/9224/14945
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
	$qvars[] = 'random'; // random flag
	$qvars[] = 'tk'; // flag for edit key
	$qvars[] = 'elink'; // for edit link requests
	$qvars[] = 'wid'; // id for sending email edit link
	return $qvars;
}

// options for post order on front page
add_action( 'pre_get_posts', 'trucollector_order_items' );

function trucollector_order_items( $query ) {

	if ( $query->is_main_query() ) {
		if (  $query->is_home() OR $query->is_archive() ) {
			$query->set( 'orderby', trucollector_option('sort_by')  );
			$query->set( 'order', trucollector_option('sort_direction') );
		}

	}
}

# -----------------------------------------------------------------
# Remove the New Post buttons, links from dashboard
# -----------------------------------------------------------------

// remove sub menu from Posts menu
add_action( 'admin_menu', 'trucollector_remove_admin_submenus', 999 );

function trucollector_remove_admin_submenus() {
	remove_submenu_page( 'edit.php', 'post-new.php' );
}


// remove from admin bar too
add_action( 'admin_bar_menu', 'trucollector_remove_admin_menus', 999 );

function trucollector_remove_admin_menus() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_node( 'new-post' );
}


// use CSS to hide the one on the posts listing
function trucollector_custom_admin_styles(){
    wp_enqueue_style( 'admin_css',  get_stylesheet_directory_uri() . '/includes/admin.css');
}

add_action('admin_enqueue_scripts', 'trucollector_custom_admin_styles');


# -----------------------------------------------------------------
# Make URLs by rewrites
# -----------------------------------------------------------------

/* set up rewrite rules */
add_action('init','trucollector_rewrite_rules');


function trucollector_rewrite_rules() {
	// for sending to random item
   add_rewrite_rule('random/?$', 'index.php?random=1', 'top');

   // for edit link requests
   add_rewrite_rule( '^get-edit-link/([^/]+)/?',  'index.php?elink=1&wid=$matches[1]','top');


   $license_page_id = trucollector_get_license_page_id();

   add_rewrite_rule( '^licensed/([^/]+)/page/([0-9]{1,})/?',  'index.php?page_id=' . $license_page_id . '&flavor=$matches[1]&paged=$matches[2]','top');

	add_rewrite_rule( '^licensed/([^/]*)/?',  'index.php?page_id=' . $license_page_id . '&flavor=$matches[1]','top');

}


/* handle redirects */

add_action( 'template_redirect', 'trucollector_write_director' );

function trucollector_write_director() {

	if ( is_page( trucollector_get_collect_page() ) and !isset( $_POST['trucollector_form_make_submitted'] ) ) {

		// check for query vars that indicate this is a edit request/ build qstring
		$tk  = get_query_var( 'tk', 0 );    // magic token to check

		$args = ( $tk )  ? '?tk=' . $tk : '';

			// normal entry check for author
		if ( !is_user_logged_in() ) {
			// not already logged in? go to desk.
			wp_redirect ( home_url('/') . trucollector_get_desk_page()  . $args );
			exit;

		} elseif ( !current_user_can( 'edit_others_posts' ) ) {
			// okay user, who are you? we know you are not an admin or editor

			// if the writer user not found, we send you to the desk
			if ( !trucollector_check_user() ) {
				// now go to the desk and check in properly
				wp_redirect ( home_url('/') . trucollector_get_desk_page() . $args  );
				exit;
			}
		}

	}

	if ( is_page(trucollector_get_desk_page()) ) {


		// check for query vars that indicate this is a edit request/ build qstring
		$tk  = get_query_var( 'tk', 0 );    // magic token to check

		$args = ( $tk )  ? '?tk=' . $tk : '';


		// already logged in? go directly to the tool
		if ( is_user_logged_in() ) {

			if ( current_user_can( 'edit_others_posts' ) ) {
				// If user has edit/admin role, send them to the tool
				wp_redirect( splot_redirect_url() . $args );
				exit;

			} else {

				// if the correct user already logged in, go directly to the tool
				if ( trucollector_check_user() ) {
					wp_redirect( splot_redirect_url()  . $args );
					exit;
				}
			}

		} elseif ( trucollector_option('accesscode') == '')  {
			splot_user_login('collector', true, $args );
			exit;
		} elseif ( isset( $_POST['trucollector_form_access_submitted'] )
		&& wp_verify_nonce( $_POST['trucollector_form_access_submitted'], 'trucollector_form_access' ) ) {

			// access code from the form
			if ( stripslashes( $_POST['wAccess'] ) == trucollector_option('accesscode') ) {
				splot_user_login('collector', true, $args );
				exit;
			}

		}

	}

  if ( get_query_var('random') == 1 ) {
		 // set arguments for WP_Query on published posts to get 1 at random
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'orderby' => 'rand'
		);

		// It's time! Go someplace random
		$my_random_post = new WP_Query ( $args );

		while ( $my_random_post->have_posts () ) {
		  $my_random_post->the_post ();

		  // redirect to the random post
		  wp_redirect ( get_permalink () );
		  exit;
		}
   } elseif ( get_query_var('elink') == 1 and get_query_var('wid')  ) {

   		// get the id parameter from URL
		$wid = get_query_var( 'wid' , 0 );   // id of post

		trucollector_mail_edit_link ($wid);
   		exit;

   	/*
   } elseif ( get_query_var('tk') ) {
   		// catch all if the collect page URL has changed, capture and redirect


   		$tk  = get_query_var( 'tk', 0 );    // magic token to check
		$args = ( $tk )  ? '?tk=' . $tk : '';
		wp_redirect( splot_redirect_url()  . $args );
		exit;
	*/
	}
}


// prevent posts from being saved to /random (reserved for random post generator

add_action( 'save_post', 'splot_save_post_random_check' );

function splot_save_post_random_check( $post_id ) {
    // verify post is not a revision and that the post slug is "random"

    $new_post = get_post( $post_id );
    if ( ! wp_is_post_revision( $post_id ) and  $new_post->post_name == 'random' ) {


        // unhook this function to prevent infinite looping
        remove_action( 'save_post', 'splot_save_post_random_check' );

        // update the post slug
        wp_update_post( array(
            'ID' => $post_id,
            'post_name' => 'randomly' // do your thing here
        ));

        // re-hook this function
        add_action( 'save_post', 'splot_save_post_random_check' );

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
# For the Collection Form
# -----------------------------------------------------------------

add_action('wp_enqueue_scripts', 'add_trucollector_scripts', 100);

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

 	if ( is_page( trucollector_get_collect_page() ) ) { // use on just our form page

		 // add media scripts if we are on our maker page and not an admin
		 // after http://wordpress.stackexchange.com/a/116489/14945

		if (! is_admin() ) wp_enqueue_media();

		// Build in tag auto complete script
   		wp_enqueue_script( 'suggest' );


   		// Autoembed functionality in rich text editor
   		// needs dependency on tiny_mce
   		// h/t https://wordpress.stackexchange.com/a/287623

   		wp_enqueue_script( 'mce-view', '', array('tiny_mce') );


		// custom jquery for the uploader on the form
		wp_register_script( 'jquery.collector' , get_stylesheet_directory_uri() . '/js/jquery.collector.js', null , '1.0', TRUE );
		wp_enqueue_script( 'jquery.collector' );



		// add scripts for fancybox (used for previews of collected items)
		//-- h/t http://code.tutsplus.com/tutorials/add-a-responsive-lightbox-to-your-wordpress-theme--wp-28100
		wp_register_script( 'fancybox', get_stylesheet_directory_uri() . '/includes/lightbox/js/jquery.fancybox.pack.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'fancybox' );

		// Lightbox formatting for preview screated with rich text editor
		wp_register_script( 'lightbox_preview', get_stylesheet_directory_uri() . '/includes/lightbox/js/lightbox_preview.js', array( 'fancybox' ), '1.1', null , '1.0', TRUE );
		wp_enqueue_script( 'lightbox_preview' );

		// fancybox styles
		wp_register_style( 'lightbox-style', get_stylesheet_directory_uri() . '/includes/lightbox/css/jquery.fancybox.css' );
		wp_enqueue_style( 'lightbox-style' );

		// used to display formatted dates
		wp_register_script( 'moment' , get_stylesheet_directory_uri() . '/js/moment.js', null, '1.0', TRUE );
		wp_enqueue_script( 'moment' );

	}  elseif ( is_single() ) {
		// on single pages, enable the editlink capability

		wp_register_script( 'jquery.editlink' , get_stylesheet_directory_uri() . '/js/jquery.editlink.js', array( 'jquery' ) , '0.3', TRUE );
		wp_enqueue_script( 'jquery.editlink' );
	}
}


# -----------------------------------------------------------------
# Comments
# -----------------------------------------------------------------

// possibly add contributor email to comment notifications
// add_filter( 'comment_moderation_recipients', 'trucollector_comment_notification_recipients', 15, 2 );
add_filter( 'comment_notification_recipients', 'trucollector_comment_notification_recipients', 15, 2 );

function trucollector_comment_notification_recipients( $emails, $comment_id ) {

	 $comment = get_comment( $comment_id );

	 // check if we should send notifications
	 if ( trucollector_ok_to_notify( $comment ) ) {
	 	// find post id from comment ID and fetch the email address to append to notifications
		$emails[] = get_post_meta(  $comment->comment_post_ID, 'wEmail', 1 );
	}
 	return ( $emails );
}

// modify the comment notification for content creators, non users dont need the wordpress comment mod stuff
// h/t https://wordpress.stackexchange.com/a/170151/14945

add_filter( 'comment_notification_text', 'trucollector_comment_notification_text', 20, 2 );

function trucollector_comment_notification_text( $notify_message, $comment_id ){
    // get the current comment
    $comment = get_comment( $comment_id );

    // change notification only for recipient who is the author of this an item (e.g. skip for admins)
    if ( trucollector_ok_to_notify( $comment ) ) {
    	// get post data
    	$post = get_post( $comment->comment_post_ID );

		// don't modify trackbacks or pingbacks
		if ( '' == $comment->comment_type ){
			// build the new message text
			$notify_message  = sprintf( __( 'New comment on  "%s" published at "%s"' ), $post->post_title, get_bloginfo( 'name' ) ) . "\r\n\r\n----------------------------------------\r\n";
			$notify_message .= sprintf( __('Author : %1$s'), $comment->comment_author ) . "\r\n";
			$notify_message .= sprintf( __('E-mail : %s'), $comment->comment_author_email ) . "\r\n";
			$notify_message .= sprintf( __('URL    : %s'), $comment->comment_author_url ) . "\r\n";
			$notify_message .= sprintf( __('Comment Link: %s'), get_comment_link( $comment_id ) ) . "\r\n\r\n----------------------------------------\r\n";
			$notify_message .= __('Comment: ') . "\r\n" . $comment->comment_content . "\r\n\r\n----------------------------------------\r\n\r\n";

			$notify_message .= __('See all comments: ') . "\r\n";
			$notify_message .= get_permalink($comment->comment_post_ID) . "#comments\r\n\r\n";

		}
	}

	// return the notification text
    return $notify_message;
}

function trucollector_ok_to_notify( $comment ) {
	// check if theme options are set to use comments and that the post associated with comment has the notify flag activated
	return ( trucollector_option('allow_comments') and get_post_meta( $comment->comment_post_ID, 'wCommentNotify', 1 ) );
}

?>

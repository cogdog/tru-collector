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
	$defaults['title_reply'] = get_trucollector_comment_title();

	$defaults['title_reply_after'] = '</h3>' . get_trucollector_comment_extra_intro();
	$defaults['logged_in_as'] = '';
	$defaults['title_reply_to'] =  get_trucollector_comment_title() . ' for %s';
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
	$qvars[] = 'ispre'; // flag for preview when not logged in
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


// remove new post from admin bar too
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

 		// tinymce mods
		add_filter("mce_external_plugins", "trucollector_register_buttons");
		add_filter('mce_buttons','trucollector_tinymce_buttons');
		add_filter('mce_buttons_2','trucollector_tinymce_2_buttons');


		// custom jquery for the uploader on the form
		wp_register_script( 'jquery.collector' , get_stylesheet_directory_uri() . '/js/jquery.collector.js', null , '1.0', TRUE );


		// add a local variable for the site's home url
		wp_localize_script(
		  'jquery.collector',
		  'collectorObject',
		  array(
		  	'ajaxUrl' => admin_url('admin-ajax.php'),
			'siteUrl' => esc_url(home_url()),
			'uploadMax' => trucollector_option('upload_max' )
		  )
		);

		wp_enqueue_script( 'jquery.collector' );

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


# -----------------------------------------------------------------
# login stuff
# -----------------------------------------------------------------

// Add custom logo to entry screen... because we can
// While we are at it, use CSS to hide the back to blog and retried password links

add_action( 'login_enqueue_scripts', 'splot_login_logo' );

function splot_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/site-login-logo.png);
            height:90px;
			width:320px;
			background-size: 320px 90px;
			background-repeat: no-repeat;
			padding-bottom: 0px;
        }
    </style>
<?php }


// Make logo link points to blog, not Wordpress.org Change Dat
// -- h/t http://www.sitepoint.com/design-a-stylized-custom-wordpress-login-screen/

add_filter( 'login_headerurl', 'splot_login_link' );

function splot_login_link( $url ) {
	return 'https://splot.ca/';
}

/* Customize message above registration form */

add_filter('login_message', 'splot_add_login_message');

function splot_add_login_message() {
	return '<p class="message">To do all that is SPLOT!</p>';
}

// login page title
add_filter( 'login_headertext', 'splot_login_logo_url_title' );

function splot_login_logo_url_title() {
	return 'The grand mystery of all things SPLOT';
}

# -----------------------------------------------------------------
# Tiny-MCE mods
# -----------------------------------------------------------------

add_filter( 'tiny_mce_before_init', 'trucollector_tinymce_settings' );

function trucollector_tinymce_settings( $settings ) {

	$settings['images_upload_handler'] = 'function (blobInfo, success, failure) {
    var xhr, formData;

    xhr = new XMLHttpRequest();
    xhr.withCredentials = false;
    xhr.open(\'POST\', \'' . admin_url('admin-ajax.php') . '\');

    xhr.onload = function() {
      var json;

      if (xhr.status != 200) {
        failure(\'HTTP Error: \' + xhr.status);
        return;
      }

      json = JSON.parse(xhr.responseText);

      if (!json || typeof json.location != \'string\') {
        failure(\'Invalid JSON: \' + xhr.responseText);
        return;
      }

      success(json.location);
    };

    formData = new FormData();
    formData.append(\'file\', blobInfo.blob(), blobInfo.filename());
	formData.append(\'action\', \'trucollector_upload_action\');
    xhr.send(formData);
  }';

	return $settings;
}

// add button for image upload
function trucollector_register_buttons( $plugin_array ) {
	$plugin_array['imgbutton'] = get_stylesheet_directory_uri() . '/js/image-button.js';
	return $plugin_array;
}

// remove  buttons from the visual editor

function trucollector_tinymce_buttons($buttons) {
	//Remove the more button
	$remove = array('wp_more', 'fullscreen');

	// Find the array key and then unset

	foreach ($remove as $notneeded) {
		if ( ( $key = array_search($notneeded,$buttons) ) !== false ) unset($buttons[$key]);
	}

	// now add the image button in, and the second one that acts like a label
	$buttons[] = 'image';
	$buttons[] = 'imgbutton';

	return $buttons;
 }

// remove  more buttons from the visual editor


function trucollector_tinymce_2_buttons( $buttons)  {
	//Remove the keybord shortcut and paste text buttons
	$remove = array('wp_help','pastetext');

	return array_diff($buttons,$remove);
 }


// this is the handler used in the tiny_mce editor to manage iage upload
add_action( 'wp_ajax_nopriv_trucollector_upload_action', 'trucollector_upload_action' ); //allow on front-end
add_action( 'wp_ajax_trucollector_upload_action', 'trucollector_upload_action' );

function trucollector_upload_action() {

    $newupload = 0;

    if ( !empty($_FILES) ) {
        $files = $_FILES;
        foreach($files as $file) {
            $newfile = array (
                    'name' => $file['name'],
                    'type' => $file['type'],
                    'tmp_name' => $file['tmp_name'],
                    'error' => $file['error'],
                    'size' => $file['size']
            );

            $_FILES = array('upload'=>$newfile);
            foreach($_FILES as $file => $array) {
                $newupload = media_handle_upload( $file, 0);
            }
        }
    }
    echo json_encode( array('id'=> $newupload, 'location' => wp_get_attachment_image_src( $newupload, 'large' )[0], 'caption' => get_attachment_caption_by_id( $newupload ) ) );
    die();
}

# -----------------------------------------------------------------
# For the Writing Form
# -----------------------------------------------------------------

add_action('wp_head', 'trucollector_no_featured_image');

function trucollector_no_featured_image() {
	if ( is_page( trucollector_get_collect_page() ) and isset( $_POST['trucollector_form_make_submitted'] ) ) {
    ?>
        <style>
            .featured-media {
                display:none;
            }
        </style>
    <?php
    }
}

// filter content on writing page so we do not submit the page content if form is submitted
add_filter( 'the_content', 'trucollector_firstview' );

function trucollector_firstview( $content ) {
    // Check if we're inside the main loop on the writing page
    if ( is_page( trucollector_get_collect_page() ) && in_the_loop() && is_main_query() ) {

    	if ( isset( $_POST['trucollector_form_make_submitted'] ) ) {
    		return '';
    	} else {
    		 return $content;
    	}

    }

    return $content;
}
?>

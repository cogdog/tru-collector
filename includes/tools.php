<?php

# -----------------------------------------------------------------
# Useful spanners and wrenches
# -----------------------------------------------------------------

function page_with_template_exists ( $template ) {
	// returns true if at least one Page exists that uses given template

	// look for pages that use the given template
	$seekpages = get_posts (array (
				'post_type' => 'page',
				'meta_key' => '_wp_page_template',
				'meta_value' => $template
			));
	 
	// did we find any?
	$pages_found = ( count ($seekpages) ) ? true : false ;
	
	// report to base
	return ($pages_found);
}

function get_pages_with_template ( $template ) {
	// returns array of pages with a given template
	
	// look for pages that use the given template
	$seekpages = get_posts (array (
				'post_type' => 'page',
				'meta_key' => '_wp_page_template',
				'meta_value' => $template,
				'posts_per_page' => -1
	));
	
	// holder for results
	$tpages = array(0 => 'Select Page');
	

	// Walk those results, store ID of pages found
	foreach ( $seekpages as $p ) {
		$tpages[$p->ID] = $p->post_title;
	}
	
	return $tpages;
}

function trucollector_get_collect_page() {

	// return slud for page set in theme options for writing page (newer versions of SPLOT)
	if ( trucollector_option( 'collect_page' ) )  {
		return ( get_post_field( 'post_name', get_post( trucollector_option( 'collect_page' ) ) ) ); 
	} else {
		// older versions of SPLOT use the slug
		return ('collect');
	}
}

function trucollector_get_desk_page() {

	// return slug for page set in theme options for welcome desk page (newer versions of SPLOT)
	if (  trucollector_option( 'desk_page' ) ) {
		return ( get_post_field( 'post_name', get_post( trucollector_option( 'desk_page' ) ) ) ); 
	} else {
		// older versions of SPLOT use the slug
		return ('desk');
	}
}


function trucollector_get_license_page() {

	// return slug for page set in theme options for view by license page (newer versions of SPLOT)
	if (  trucollector_option( 'license_page' ) ) {
		return ( get_post_field( 'post_name', get_post( trucollector_option( 'license_page' ) ) ) ); 
	} else {
		// older versions of SPLOT use the slug
		return ('licensed');
	}
}

function trucollector_get_license_page_id() {

	// return slug for page set in theme options for view by license page (newer versions of SPLOT)
	if (  trucollector_option( 'license_page' ) ) {
		return ( trucollector_option( 'license_page' ) ); 
	} else {
		// older versions of SPLOT use the slug
		return ( get_page_by_path('licensed')->ID );
	}
}


function trucollector_archive_title( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( ucfirst(get_trucollector_collection_plural_item()) .  ' Categorized "', false ) . '"';
    } elseif ( is_tag() ) {
        $title = single_tag_title( ucfirst(get_trucollector_collection_plural_item()) .  ' Tagged "', false ) . '"';
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    }
  
    return $title;
}
 
add_filter( 'get_the_archive_title', 'trucollector_archive_title' );


function trucollector_allowed_email_domain( $email ) {
	// checks if an email address is within a list of allowed domains

	// allow for empty entries
	if ( empty($email) ) return true;

	// extract domain h/t https://www.fraudlabspro.com/resources/tutorials/how-to-extract-domain-name-from-email-address/
	$domain = substr($email, strpos($email, '@') + 1);

	$allowables = explode(",", trucollector_option('email_domains'));

	foreach ( $allowables as $item) {
		if ( $domain == trim($item)) return true;
	}

	return false;
}



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
?>
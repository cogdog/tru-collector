<?php

# -----------------------------------------------------------------
# Useful spanners and wrenches
# -----------------------------------------------------------------


function splot_redirect_url() {
	// where to send to collect form
	return ( home_url('/') . trucollector_get_collect_page() );
}

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


# -----------------------------------------------------------------
# Media
# -----------------------------------------------------------------

// return the maxium upload file size in omething more useful than bytes
function trucollector_max_upload() {
	$maxupload = wp_max_upload_size() / 1000000;
	return ( round( $maxupload ) . ' Mb');

}

function trucollector_get_upload_max() {
	// in case not set in options, return the max
	return ( trucollector_option('upload_max') ) ? trucollector_option('upload_max') . ' Mb' : trucollector_max_upload();

}

// function to get the caption for an attachment (stored as post_excerpt)
// -- h/t http://wordpress.stackexchange.com/a/73894/14945

function get_attachment_caption_by_id( $post_id ) {
    $the_attachment = get_post( $post_id );
    return ( $the_attachment->post_excerpt );
}

// for uploading images
function trucollector_insert_attachment( $file_handler, $post_id ) {

	if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) return (false);

	require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

	$attach_id = media_handle_upload( $file_handler, $post_id );

	return ($attach_id);

}

# -----------------------------------------------------------------
# Allow Previews
# -----------------------------------------------------------------


function  splotbox_show_drafts( $query ) {
// show drafts only for single previews
    if ( is_user_logged_in() || is_feed() || !is_single() )
        return;

    $query->set( 'post_status', array( 'publish', 'draft' ) );
}

add_action( 'pre_get_posts', 'splotbox_show_drafts' );

// enable previews of posts for non-logged in users
// ----- h/t https://wordpress.stackexchange.com/a/164088/14945

add_filter( 'the_posts', 'splotbox_reveal_previews', 10, 2 );

function splotbox_reveal_previews( $posts, $wp_query ) {

    //making sure the post is a preview to avoid showing published private posts
    if ( !is_preview() )
        return $posts;

    if ( is_user_logged_in() )
    	 return $posts;

    if ( count( $posts ) )
        return $posts;

    if ( !empty( $wp_query->query['p'] ) ) {
        return array ( get_post( $wp_query->query['p'] ) );
    }
}

function splotbox_is_preview() {
	return ( get_query_var( 'ispre', 0 ) == 1);
}


function trucollector_preview_notice() {
	return ('<div class="notify"><span class="symbol icon-info"></span>
This is a preview of your entry that shows how it will look when published. <a href="#" onclick="self.close();return false;">Close this window/tab</a> when done to return to the sharing form. Make any changes and click "Revise Draft" again or if it is ready, click "Publish Now".
				</div>');
}

# -----------------------------------------------------------------
# Useful spanners and wrenches
# -----------------------------------------------------------------

/**
 * Recursively sort an array of taxonomy terms hierarchically. Child categories will be
 * placed under a 'children' member of their parent term.
 * @param Array   $cats     taxonomy term objects to sort
 * @param Array   $into     result array to put them in
 * @param integer $parentId the current parent ID to put them in
   h/t http://wordpress.stackexchange.com/a/99516/14945
 */
function trucollector_sort_terms_hierarchicaly( Array &$cats, Array &$into, $parentId = 0 )
{
    foreach ($cats as $i => $cat) {
        if ($cat->parent == $parentId) {
            $into[$cat->term_id] = $cat;
            unset($cats[$i]);
        }
    }

    foreach ($into as $topCat) {
        $topCat->children = array();
        trucollector_sort_terms_hierarchicaly($cats, $topCat->children, $topCat->term_id);
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

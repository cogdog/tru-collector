<?php

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

	// add a customizer link that opens the sharing form
	$wp_admin_bar->add_menu( array(
		'parent' => 'customize',
		'id' => 'tru-collector-customize',
		'title' => __('Collection Form'),
		'href' => admin_url( 'customize.php?url='. splot_redirect_url())
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
?>

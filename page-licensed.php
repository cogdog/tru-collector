<?php 

/*
Template Name: Items by License
*/


get_header();

// ------------------------ check vars ------------------------

$page_id = $post->ID;

// all allowable licenses for this theme
$all_licenses = trucollector_get_licences();

if ( isset( $wp_query->query_vars['flavor'] ) ) {
	$license_flavor = $wp_query->query_vars['flavor'];

	// make sure we have something in the set of allowed ones; otherwise set to none
	if ( ! array_key_exists ( $license_flavor, $all_licenses ) ) $license_flavor = 'none';
	
} else {
	// no license in query string
	$license_flavor = 'none';
}


$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

?>



<div class="content thin">

	<?php if ($license_flavor == 'none') :?>
											        
		<?php 
	
		if ( have_posts() ) : 
		
			while ( have_posts() ) : the_post(); 
		
				?>
			
				<div id="post-<?php the_ID(); ?>" <?php post_class( 'post single' ); ?>>
				
				<?php if ( has_post_thumbnail() ) : ?>
						
					<div class="featured-media">
			
						<?php the_post_thumbnail( 'post-image' ); ?>
						
					</div><!-- .featured-media -->
						
				<?php endif; ?>

				<div class="post-inner">
					
					<div class="post-header">
														
						<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
																
					</div><!-- .post-header -->
						
					<div class="post-content">
					
						<?php the_content(); ?>
					
							<?php if ( trucollector_option('use_license') > 0 ):?>
								<ul>
								<?php
				
									foreach ( $all_licenses as $abbrev => $title) {
									
										// get number of items with this license
										$lcount = trucollector_get_license_count( $abbrev ); 
										
										// show if we have some
										if ( $lcount > 0 ) {
											echo '<li><a href="' . site_url() . '/licensed/' . $abbrev . '">' . $title . '</a> (' . $lcount . ")</li>\n";
										}
									}
								?>
								</ul>
							<?php else:?>

							<p>The current settings for this site are to not use licenses; the site administrator can enable this feature from the <code>TRU Collector Options.</code> </p>
							<?php endif?>

					</div><!-- .post-content -->
					
					<div class="clear"></div>	
					
				<?php 
		endwhile; 

	endif; 
	
	?>
	<?php else:?>
	
		<?php
			// construct query for licenses

			if ( $license_flavor == 'u' ) {
				// cover case where older sites used '?' for unknown

				$args = array(
					'paged'         => $paged,
					'meta_query' => array(
					'relation' => 'OR',
						array(
							'key'     => 'license',
							'value'   => 'u',
							'compare' => '=',
						),
                        array(
                                'key' => 'license',
                                'value' => '?',
                                'compare' => '=',
                        ),
					),
				);
			
			
			} else  {
				// normal query
				$args = array(
					'meta_key'   => 'license',
					'meta_value' => $license_flavor,
					'paged'         => $paged,
				);
			}
			
		$my_query = new WP_Query( $args );
		
		// Pagination fix
		$temp_query = $wp_query;
		$wp_query   = NULL;
		$wp_query   = $my_query;		
		?>

	<?php if ( $my_query->have_posts() ): ?>

	
		<div class="page-title">
			
			<div class="section-inner">

				<h4><?php echo $my_query->found_posts?> Items Licensed <?php echo $all_licenses[$license_flavor]; ?> &bull;  <a href="<?php echo get_permalink($page_id);?>">All By Licenses</a>
			
				<?php
			
				if ( "1" < $my_query->max_num_pages ) : ?>
			
					<span><?php printf( __('Page %s of %s', 'fukasawa'), $paged, $my_query->max_num_pages ); ?></span>
				
					<div class="clear"></div>
			
				<?php endif; ?></h4>
					
			</div> <!-- /section-inner -->
		
		</div> <!-- /page-title -->
	

		<div class="posts" id="posts">

			<div class="grid-sizer"></div>
				
			<?php 
			while ( $my_query->have_posts() ) : $my_query->the_post();
			
				get_template_part( 'content', get_post_format() );
				
			endwhile; 
			?>
			
			<div class="clear"></div>	
		
		</div><!-- .posts -->
		
			<?php if (  $my_query->max_num_pages > 1 ) : ?>
			
	
				<div class="archive-nav">
			
					<?php 
		
						$nav_label = get_trucollector_collection_plural_item();
			
						echo get_next_posts_link( __( 'Older ' . $nav_label , 'fukasawa' ) . ' &rarr;' , $my_query->max_num_pages ); 
			
						echo get_previous_posts_link( '&larr; ' . __( 'Newer ' . $nav_label, 'fukasawa' )); 
			
						// Reset postdata
						wp_reset_postdata();

						// Reset main query object
						$wp_query = NULL;
						$wp_query = $temp_query;
					?>
		
					<div class="clear"></div>
					
				</div><!-- .archive-nav -->
		


			<?php endif; ?>
		<?php endif; ?>
<?php endif; ?>
	
</div> <!-- /content -->
								
<?php get_footer(); ?>
<?php
// ------------------------ check vars ------------------------

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
?>

<?php get_header(); ?>

<div class="content thin">

	<?php if ($license_flavor == 'none') :?>
		<div <?php post_class('post single'); ?>>
		
			<?php if ( has_post_thumbnail() ) : ?>
			
				<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail_size' ); $thumb_url = $thumb['0']; ?>
		
				<div class="featured-media">
		
					<?php the_post_thumbnail('post-image'); ?>
					
				</div> <!-- /featured-media -->
					
			<?php endif; ?>
			
			<div class="post-inner">
												
				<div class="post-header">
																										
					<h2 class="post-title"><?php the_title(); ?></h2>
															
				</div> <!-- /post-header section -->
				    
			    <div class="post-content">	
			    	<?php the_content(); ?>
			    	
			    	
			    	<?php if ( trucollector_option('use_license') > 0 ):?>
						<ul>
						<?php
					
							foreach ( $all_licenses as $abbrev => $title) {
								echo '<li><a href="' . site_url() . '/licensed/' . $abbrev . '">' . $title . "</a></li>\n";
							}
					
						?>
						</ul>
					<?php else:?>
					
						<p>The current settings for this site are to not use licenses; the site administration can enable this feature from the <code>TRU Collector Options.</code> </p>
					
					
					<?php endif?>

			    </div>
			</div>	
		</div>
			
	<?php else:?>
	
		<?php
$args = array(
	'post_type'  => 'product',
	'meta_query' => array(
		'relation' => 'OR',
		array(
			'key'     => 'color',
			'value'   => 'orange',
			'compare' => '=',
		),
                array(
                        'relation' => 'AND',
                        array(
                                'key' => 'color',
                                'value' => 'red',
                                'compare' => '=',
                        ),
                        array(
                                'key' => 'size',
                                'value' => 'small',
                                'compare' => '=',
                        ),
		),
	),
);		
		
		
			if ( $license_flavor == 'u' ) {
				// cover case where older sites used '?' for unknown

				$args = array(
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
					'meta_value' => $license_flavor
				);
			}
			
		$my_query = new WP_Query( $args );
		?>

	
	<div class="page-title">
			
		<div class="section-inner">

			<h4><?php echo $my_query->found_posts?> Items Licensed <?php echo $all_licenses[$license_flavor]; ?> 
			
			<?php
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
			if ( "1" < $my_query->max_num_pages ) : ?>
			
				<span><?php printf( __('Page %s of %s', 'fukasawa'), $paged, $my_query->max_num_pages ); ?></span>
				
				<div class="clear"></div>
			
			<?php endif; ?></h4>
					
		</div> <!-- /section-inner -->
		
	</div> <!-- /page-title -->
	

	<?php if ( $my_query->have_posts() ) : ?>
	
			
			<div class="posts" id="posts">
			
				<?php while (  $my_query->have_posts() ) :  $my_query->the_post(); ?>
						
					<?php get_template_part( 'content', get_post_format() ); ?>
				
				<?php endwhile; ?>
							
			</div> <!-- /posts -->
		
			<?php if (  $my_query->max_num_pages > 1 ) : ?>
			
				<div class="archive-nav">
			
					<div class="section-inner">
			
						<?php echo get_next_posts_link( '&laquo; ' . __('Older items', 'fukasawa')); ?>
							
						<?php echo get_previous_posts_link( __('Newer items', 'collectables') . ' &raquo;'); ?>
					
						<div class="clear"></div>
				
					</div>
				
				</div> <!-- /post-nav archive-nav -->
							
			<?php endif; ?>
				
		<?php endif; ?>
		
	<?php endif; ?>
	
</div> <!-- /content -->
								
<?php get_footer(); ?>
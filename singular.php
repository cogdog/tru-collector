<?php get_header();

if  (  is_single() ) {
	// we are a post, yep

	//  get post meta
	$wSource = get_post_meta( $post->ID, 'source', 1 );
	$wAuthor = get_post_meta( $post->ID, 'shared_by', 1 );
	$wLicense = (get_post_meta( $post->ID, 'license', 1 )) ? get_post_meta( $post->ID, 'license', 1 ) : 'u' ;

	// festured image
	$wFeatureImageID = get_post_thumbnail_id( $post->ID);
	
    // get image alt tag
	$wAlt = ( get_post_meta($wFeatureImageID, '_wp_attachment_image_alt', true) ) ? get_post_meta($wFeatureImageID, '_wp_attachment_image_alt', true) : 'None provided' ;

}

$item_label = get_trucollector_collection_single_item();

?>

<div class="content thin">

	<?php

	if ( have_posts() ) :

		while ( have_posts() ) : the_post();

			?>

			<div id="post-<?php the_ID(); ?>" <?php post_class( 'post single' ); ?>>

				<?php

				$post_format = get_post_format();

				if ( $post_format == 'video' ) :

					if ( $pos = strpos( $post->post_content, '<!--more-->' ) ) : ?>

						<div class="featured-media">

							<?php

							// Fetch post content
							$content = get_post_field( 'post_content', get_the_ID() );

							// Get content parts
							$content_parts = get_extended( $content );

							// oEmbed part before <!--more--> tag
							$embed_code = wp_oembed_get( $content_parts['main'] );

							echo $embed_code;

							?>

						</div><!-- .featured-media -->

						<?php
					endif;

				elseif ( $post_format == 'gallery' ) : ?>

					<div class="featured-media">

						<?php fukasawa_flexslider( 'post-image' ); ?>

						<div class="clear"></div>

					</div><!-- .featured-media -->

				<?php elseif ( has_post_thumbnail() ) : ?>

					<div class="featured-media">

						<?php the_post_thumbnail( 'post-image' );?>

					</div><!-- .featured-media -->

				<?php endif; ?>

				<div class="post-inner">

					<div class="post-header">

						<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>

						<?php if (  is_single() AND !is_preview() AND function_exists('the_ratings')) { the_ratings(); } ?>

					</div><!-- .post-header -->

					<div class="post-content">


							<?php  if ( is_single() AND is_preview() ):?>
								<div class="notify"><span class="symbol icon-info"></span>
This is a preview of your <?php echo $item_label?> that shows how it will look when published. <a href="#" onclick="self.close();return false;">Close this window/tab</a> when done to return to the submission form. Make any changes and check the info again or if it is ready, click <strong>Share Now</strong>
							</div>
							<?php endif?>

						<?php the_content();?>


					<?php if (  is_single() ): ?>
						<div class="splot_meta">
						<p>
						<?php

							if (  trucollector_option('show_sharedby') AND !empty($wAuthor) ) {
								echo '<strong>' . trucollector_form_item_author('get') . ':</strong> <a href="' . get_site_url() . '/?collector=' . urlencode($wAuthor) . '">' . $wAuthor . '</a><br />';
							}

							if ( ( trucollector_option('use_source') > 0 )  AND !empty($wSource) ) {
								echo '<strong>' . trucollector_form_item_image_source('get') . ':</strong> ' .  make_links_clickable($wSource)  . '<br />';
							}

							 // alt descriptions y'all should be doing
							 echo '<strong>' . trucollector_form_item_img_alt('get') . ':</strong> ' .  $wAlt  . '<br />';

							if  ( trucollector_option('use_license') > 0 AND !empty($wLicense) ) {
								echo '<strong>' . trucollector_form_item_license('get') . ':</strong> ';
								trucollector_the_license( $wLicense );
								echo '<br />';

								// display attribution?
								if  ( trucollector_option( 'show_attribution' ) == 1 ) {
									echo '<strong>Attribution Text:</strong><br /><textarea rows="2" onClick="this.select()" style="height:4rem;">' . trucollector_attributor( $wLicense, get_the_title(), $wSource ) . '</textarea>';
								}
							}

							?>
				    		<?php if  ( trucollector_option( 'show_link' ) != 0 AND has_post_thumbnail() ) :?>

							<form>
								<label for="link">Image Link:</label>
									<input type="text" class="form-control" id="link" value="<?php $iurl = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); echo $iurl[0];  ?>" onClick="this.select();" />
							</form>

							<?php endif;?>
						</p>

						<?php
							// show the request edit link button if option set and they have provided an email and post is published
							if ( trucollector_option('show_email') and get_post_meta( $post->ID, 'wEmail', 1 ) and get_post_status() == 'publish' ) :?>

							<p>
								<strong>Edit Link:</strong> <em>(emailed to author)</em><br /><a href="#" id="getEditLink" class="button" data-widurl="<?php echo get_bloginfo('url') . '/get-edit-link/' .   $post->ID ?>">Request Now</a> <span id="getEditLinkResponse" class="writenew"></span>
							</p>
							<?php endif?>
						</div><!-- spplot_meta -->
					<?php endif; // is_single?>


					<?php if ( is_single() AND is_preview() ):?>
						<div class="notify"><span class="symbol icon-info"></span> Once done reviewing your <?php echo $item_label?>, <a href="#" onclick="self.close();return false;">Close this window/tab</a> to return to the editing form.</div>
					<?php endif?>

					</div><!-- .post-content -->

					<div class="clear"></div>

					<?php if ( is_single() ) : ?>

						<div class="post-meta-bottom">

							<?php
							$args = array(
								'before'           => '<div class="clear"></div><p class="page-links"><span class="title">' . __( 'Pages:','fukasawa' ) . '</span>',
								'after'            => '</p>',
								'link_before'      => '<span>',
								'link_after'       => '</span>',
								'separator'        => '',
								'pagelink'         => '%',
								'echo'             => 1
							);

							wp_link_pages( $args );
							?>

							<ul>

								<li class="post-date"><a href="<?php the_permalink(); ?>"><?php the_date(get_option('date_format')); ?></a></li>
								<?php if (  trucollector_option('show_cats') != 0 AND has_category()) : ?>
									<li class="post-categories"><?php _e('In:','fukasawa'); ?> <?php the_category(', '); ?></li>
								<?php endif; ?>
								<?php if ( trucollector_option('show_tags') != 0 AND  has_tag() ) : ?>
									<li class="post-tags"> <?php the_tags('Tagged: ', ', '); ?></li>
								<?php endif; ?>



								<?php edit_post_link( __( 'Edit ' . ucfirst($item_label), 'fukasawa' ), '<li>', '</li>' ); ?>
							</ul>
							<div class="clear"></div>




						</div><!-- .post-meta-bottom -->

					<?php endif; ?>

				</div><!-- .post-inner -->

				<?php if ( is_single() AND !is_preview() ): ?>

					<div class="post-navigation">

						<?php

						$prev_post = get_previous_post();
						$next_post = get_next_post();

						if ( $prev_post ) : ?>

							<a class="post-nav-prev" href="<?php echo get_permalink( $prev_post->ID ); ?>">
								<p>&larr; <?php _e( 'Previous ' . $item_label, 'fukasawa' ); ?></p>
							</a>

							<?php
						endif;

						if ( $next_post ) : ?>

							<a class="post-nav-next" href="<?php echo get_permalink( $next_post->ID ); ?>">
								<p><?php _e( 'Next ' . $item_label, 'fukasawa' ); ?> &rarr;</p>
							</a>

							<?php
						endif;
						?>

						<div class="clear"></div>

					</div><!-- .post-navigation -->

				<?php endif; ?>

				<?php if ( comments_open() AND trucollector_option('allow_comments') AND !is_preview() ) : ?>

					<?php comments_template( '', true ); ?>

				<?php endif; ?>

			</div><!-- .post -->

			<?php
		endwhile;

	endif;

	?>

</div><!-- .content -->

<?php get_footer(); ?>

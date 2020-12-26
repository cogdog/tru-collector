<?php get_header(); ?>

<div class="content">

	<?php if ( have_posts() ) :

		$nav_label = get_trucollector_collection_plural_item();
		?>

		<?php
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$archive_title = '';
		$archive_subtitle = '';

		if ( is_archive() ) {
			$archive_title = $wp_query->found_posts . ' ' . get_the_archive_title();
		} elseif ( is_search() ) {
			$archive_title = sprintf(
						_n(
							'%s ' . get_trucollector_collection_single_item() . ' found for "%s"',
							'%s ' . get_trucollector_collection_plural_item() . ' found for "%s"',
							$wp_query->found_posts,
							'fukasawa'
						),
						number_format_i18n( $wp_query->found_posts ),
						get_search_query());
		} elseif ( $paged > 1 ) {
			$archive_title = sprintf( __( ' (page %1$s of %2$s)', 'fukasawa' ), $paged, $wp_query->max_num_pages );
		}

		if ( ( is_archive() || is_search() ) && 1 < $wp_query->max_num_pages ) {
			$archive_subtitle = sprintf( __( ' (page %1$s of %2$s)', 'fukasawa' ), $paged, $wp_query->max_num_pages );
		}

		if ( $archive_title ) : ?>

			<div class="page-title">

				<div class="section-inner">

					<h4>
						<?php
						echo $archive_title;

						if ( $archive_subtitle ) {
							echo '<span>' . $archive_subtitle . '</span>';
						}
						?>

						<div class="clear"></div>

					</h4>

					<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
					?>

				</div><!-- .section-inner -->

			</div><!-- .page-title -->

		<?php endif; ?>

		<div class="posts" id="posts">

			<div class="grid-sizer"></div>

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'content', get_post_format() );

			endwhile;
			?>

		</div><!-- .posts -->

	<?php endif; ?>

	<?php if ( $wp_query->max_num_pages > 1 ) : ?>

		<div class="archive-nav">

			<?php

				echo get_next_posts_link( __( 'Previous ' . $nav_label , 'fukasawa' ) . ' &rarr;' );

				echo get_previous_posts_link( '&larr; ' . __( 'Next ' . $nav_label, 'fukasawa' ) );

			?>

			<div class="clear"></div>

		</div><!-- .archive-nav -->

	<?php endif; ?>

</div><!-- .content -->

<?php get_footer(); ?>

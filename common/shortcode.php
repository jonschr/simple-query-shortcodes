<?php

/**
 * Do our main loop
 */
add_shortcode( 'loop', 'gsq_add_shortcode' );
function gsq_add_shortcode( $atts ) {

	wp_enqueue_style( 'gsq-styles' );

	$args = shortcode_atts( array(
		'debug' 			=> null,
		'category' 			=> null,
		'category_name' 	=> null,
		'post_type' 		=> 'post',
		'post__in' 			=> null,
		'posts_per_page' 	=> '-1',
		'p' 				=> null,
		'name' 				=> null,
		'taxonomy' 			=> null,
		'field' 			=> 'slug',
		'terms' 			=> null,
		'operator' 			=> 'IN',
		'orderby' 			=> null,
		'order' 			=> null,
		'offset' 			=> null,
		'columns' 			=> 1,
		'layout' 			=> null,
		'align' 			=> 'left',
		'extras' 			=> null,
		'class' 			=> null,
		'style' 			=> null,
		'connected_type' 	=> null,
	), $atts );

	//* Let's do any processing of the arguments we need to do
	$args = apply_filters( 'gsq_do_args_defaults', $args );

	//* Start listening for output
	ob_start();

	// // * Show all of the queries (useful for debugging)
	// echo '<pre><small>';
	// print_r( $args );
	// echo '</small></pre>';

	//* For testing, echo the arguments being used in the query if needed
	gsq_debug( $args );
	
	//* Hook in before a specific layout
	do_action( 'before_loop_layout_' . $args['layout'], $args );

	//* If this is a normal loop, and we don't need to deal with Posts2Posts, do a query
	if ( empty( $args['connected_type'] ) ) {
		$gsq_shortcode_query = new WP_Query( $args );
	}

	//* If this is not a normal loop and we need to deal with Posts2Posts, do that query
	if ( $args['connected_type'] ) {
		$gsq_shortcode_query = new WP_Query( array(
		  'connected_type' => $args['connected_type'],
		  'connected_items' => get_queried_object(),
		  'nopaging' => true,
		) );
	}

	//* For testing, show the query
	gsq_debug_query( $args, $gsq_shortcode_query );

	//* Add the post-count-n class
	$post_count = $gsq_shortcode_query->post_count;
	
	if ( $args['class'] )
		$args['class'] = $args['class'] . ' ' . 'post-count-' . $post_count;

	if ( !$args['class'] )
		$args['class'] = 'post-count-' . $post_count;

	//* Add a post-count-x class for even or odd
	if ( $post_count % 2 == 0 ) {
		$args['class'] = $args['class'] . ' ' . 'post-count-even';
	} else {
		$args['class'] = $args['class'] . ' ' . 'post-count-odd';
	}

    if ( $gsq_shortcode_query->have_posts() ) :

		//* Admin notice if there's no layout defined
		if ( !has_action( 'add_loop_layout_' . $args['layout'] ) && current_user_can( 'edit_posts' ) ) {

			echo '<p class="loop-error"><strong>NOTE:</strong> The specified layout for this <strong>[loop]</strong> shortcode has not been defined. Please attach an action to the <strong>add_loop_layout_' . $args['layout'] . '</strong> hook.</p>';
		}

		do_action( 'gsq_loop_before_while', $args );

		while ( $gsq_shortcode_query->have_posts() ) : $gsq_shortcode_query->the_post();

			do_action( 'gsq_loop_before_entry' );

			global $post;
			$post_id = get_the_ID();

			$classes = implode( ' ', get_post_class('entry') );
			printf( '<article class="%s">', $classes );

				echo '<div class="loop-item-inner">';

				  	//* Hook in to add a specific layout (this is the markup for each post)
				  	if ( has_action( 'add_loop_layout_' . $args['layout'] ) )
						do_action( 'add_loop_layout_' . $args['layout'], $post_id );

				echo '</div>';

			echo '</article>';

			do_action( 'gsq_loop_after_entry' );

		endwhile; // End of one post.
		
		do_action( 'gsq_loop_after_while', $args );

	else : // If no posts exist.

		do_action( 'gsq_loop_else', $args );

	endif; // End loop.

	wp_reset_query();

	//* Hook in after a specific layout
	do_action( 'after_loop_layout_' . $args['layout'], $args );

	//* Output everything we've done up to now
	return ob_get_clean();

}
<?php
/**
 * Plugin Name: Genesis Simple Query Shortcodes
 * Description: Just another simple query shortcode plugin.
 * Plugin URI: http://redblue.us
 * Author: Jon Schroeder
 * Author URI: http://redblue.us
 * Version: 0.1
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or exit;

//* If we don't have Genesis running, let's bail out right there
$theme = wp_get_theme(); // gets the current theme
if ( 'genesis' != $theme['Template'] )
    return;

if ( is_admin() )
	return;

//* Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', 'gsq_enqueue_scripts_styles' );
function gsq_enqueue_scripts_styles() {

    //* Don't add these scripts and styles to the admin side of the site
    if ( is_admin() )
        return;

	//* Register the main stylesheet
	wp_register_style( 'gsq-styles', plugin_dir_url( __FILE__ ) . 'css/genesis-simple-query.css' );

}

include 'common/basic-loop-hooks.php';

include 'layouts/blank.php';
include 'layouts/genesis-default.php';

add_shortcode( 'loop', 'gsq_add_shortcode' );
function gsq_add_shortcode( $atts ) {

	wp_enqueue_style( 'gsq-styles' );

	$args = shortcode_atts( array(
		'debug' => null,
		'category' => null,
		'category_name' => null,
		'post_type' => null,
		'posts_per_page' => '-1',
		'p' => null,
		'taxonomy' => null,
		'field' => 'slug',
		'terms' => null,
		'operator' => 'IN',
		'orderby' => null,
		'order' => null,
		'offset' => null,
		'columns' => 1,
		'layout' => 'blank',
		'align' => 'left',
		'extras' => null,
		'class' => null,
		'style' => null,
		'connected_type' => null,
	), $atts );

	//* If there's any term or taxonomy, or a category with a CPT in our shortcode, we'll modify the query appropriately
	if ( $args['taxonomy'] || $args['terms'] || ( $args['category'] && !$args['post_type'] ) ) {

		//* If there's a category being set instead of a term on a CPT, let's make it a term instead (this is a common error)
		if ( $args['category'] && !$args['post_type'] ) {
			$args['terms'] = $args['category'];
			
			//* Need to reset the category to null so that it's not actually added to the query (because it will never find a post in both the 'category' AND the 'term' )
			$args['category'] = null;
		}

		//* If a term is provided, but no taxonomy, let's get it automatically if there's only one tax attached to this post type
		if ( $args['terms'] && !$args['taxonomy'] ) {
			$taxonomies = get_object_taxonomies( $args['post_type'], 'objects');

			foreach ( $taxonomies as $taxonomy ) {
				$args['taxonomy'] = $taxonomy->name;
			}
		}
		
		//* Set up the taxonomy query
		$tax_args = array(
			'tax_query' => array(
				array(
					'taxonomy' => $args['taxonomy'],
					'field'    => $args['field'],
					'terms'    => $args['terms'],
					'operator' => $args['operator']
				),
			),
		);
		
		//* So that they don't mess anything up down the line, reset the values of the things we've used
		$args['taxonomy'] = null;
		$args['field'] = null;
		$args['terms'] = null;
		$args['operator'] = null;

		$args = wp_parse_args( $args, $tax_args );
	}

	//* Start listening for output
	ob_start();

	//* For testing, echo the arguments being used in the query if needed
	gsq_debug( $args );
	
	//* Hook in before a specific layout
	do_action( 'before_loop_layout_' . $atts['layout'], $args );

	//* If this is a normal loop, and we don't need to deal with Posts2Posts, do a query
	if ( empty( $atts['connected_type'] ) ) {
		$gsq_shortcode_query = new WP_Query( $args );
	}

	//* If this is not a normal loop and we need to deal with Posts2Posts, do that query
	if ( !empty( $atts['connected_type'] ) ) {
		$gsq_shortcode_query = new WP_Query( array(
		  'connected_type' => $atts['connected_type'],
		  'connected_items' => get_queried_object(),
		  'nopaging' => true,
		) );
	}

    if ( $gsq_shortcode_query->have_posts() ) :

		//* If there's no layout being set, but there's a CPT, let's automatically use the name of the post type as the layout
    	if ( !$atts['layout'] && !$atts['post_type'] )
    		$atts['layout'] = $atts['post_type'];

		//* Admin notice if there's no layout defined
		if ( !has_action( 'add_loop_layout_' . $atts['layout'] ) && current_user_can( 'edit_posts' ) ) {

			echo '<p class="loop-error"><strong>NOTE:</strong> The specified layout for this <strong>[loop]</strong> shortcode has not been defined. Please attach an action to the <strong>add_loop_layout_' . $atts['layout'] . '</strong> hook.</p>';
		}

		do_action( 'gsq_loop_before_while', $atts );
	
		while ( $gsq_shortcode_query->have_posts() ) : $gsq_shortcode_query->the_post();

			do_action( 'gsq_loop_before_entry' );

			printf( '<article %s>', genesis_attr( 'entry' ) );

				echo '<div class="loop-item-inner">';
				
				  	$post_id = get_the_ID();

				  	//* Hook in to add a specific layout (this is the markup for each post)
					do_action( 'add_loop_layout_' . $atts['layout'], $post_id );

					if ( !has_action( 'add_loop_layout_' . $atts['layout'] ) )
						do_action( 'add_loop_layout_blank', $post_id );						

				echo '</div>';

			echo '</article>';

			do_action( 'gsq_loop_after_entry' );

		endwhile; // End of one post.
		
		do_action( 'gsq_loop_after_while', $atts );

	else : // If no posts exist.

		do_action( 'gsq_loop_else', $atts );

	endif; // End loop.

	wp_reset_query();

	//* Hook in after a specific layout
	do_action( 'after_loop_layout_' . $atts['layout'], $args );

	//* Output everything we've done up to now
	return ob_get_clean();

}

function gsq_debug( $args ) {
	if ( $args['debug'] ) {

		echo '<h2 style="text-align: left;">ARGUMENTS BEING PASSED:</h2>';
		echo '<pre style="font-size: 14px; text-align: left;">';
		var_dump( $args );
		echo '</pre>';	
	}
}
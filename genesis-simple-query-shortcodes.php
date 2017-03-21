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

	$atts = shortcode_atts( array(
		'category' => null,
		'post_type' => 'post',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'p' => null,
		'taxonomy' => null,
		'field' => null,
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

	$args = array();

	//* The basic things we need to output something
	$default_args = array(
		'post_type' => $atts['post_type'],
		'posts_per_page' => $atts['posts_per_page'],
	);

	$args = wp_parse_args( $args, $default_args );

	//* Start listening for output
	ob_start();
	
	//* Hook in before a specific layout
	do_action( 'before_loop_layout_' . $atts['layout'], $args );

	//* If this is a normal loop, and we don't need to deal with Posts2Posts, do a query
	if ( empty( $atts['connected_type'] ) )
		$gsq_shortcode_query = new WP_Query( $args );

	//* If this is not a normal loop and we need to deal with Posts2Posts, do that query
	if ( !empty( $atts['connected_type'] ) ) {
		$gsq_shortcode_query = new WP_Query( array(
		  'connected_type' => $atts['connected_type'],
		  'connected_items' => get_queried_object(),
		  'nopaging' => true,
		) );
	}

    if ( $gsq_shortcode_query->have_posts() ) :

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

	//* Hook in after a specific layout
	do_action( 'after_loop_layout_' . $atts['layout'], $args );

	//* Output everything we've done up to now
	return ob_get_clean();
}
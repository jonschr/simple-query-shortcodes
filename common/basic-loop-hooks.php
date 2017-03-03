<?php

add_action( 'gsq_loop_before_while', 'gsq_loop_do_before_while' );
function gsq_loop_do_before_while( $atts ) {

	if ( !has_action( 'add_loop_layout_' . $atts['layout'] ) )
		$atts['layout'] = 'blank';

	//* Add columns classes. This is how our defaults are styled, so add a custom class if style is set to none
	if ( $atts['style'] == 'none' ) {
		$classes[] = 'custom-loop-columns-' . $atts['columns'];	
		$classes[] = 'custom-loop-container';
	} else {
		$classes[] = 'loop-columns-' . $atts['columns'];	
		$classes[] = 'loop-container';
	}

	$classes[] = 'loop-container-align-' . $atts['align'];
	$classes[] = 'loop-layout-' . $atts['layout'];
	$classes[] = $atts['class'];

	// $classes = array(
	// 	'loop-container',
	// 	$alignment = 'loop-container-align-' . $atts['align'],
	// 	$columns = 'loop-columns-' . $atts['columns'],
	// 	$layout = 'loop-layout-' . $atts['layout'],
	// 	$columns = $atts['columns'],
	// );

	$classes = implode( ' ', $classes );

	printf( '<div class="%s">', $classes );
}

add_action( 'gsq_loop_after_while', 'gsq_loop_do_after_while' );
function gsq_loop_do_after_while() {
	echo '</div>'; // .gsq-loop-container
}


add_action( 'gsq_loop_else', 'gsq_do_default_nothing_found' );
function gsq_do_default_nothing_found() {
	echo 'Whoops, nothing here!';
}
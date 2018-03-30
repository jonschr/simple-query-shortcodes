<?php

add_action( 'gsq_loop_before_while', 'gsq_loop_do_before_while', 10, 1 );
function gsq_loop_do_before_while( $atts ) {

	$layout = isset($atts['layout'] ) ? $atts['layout'] : null;
	$style = isset($atts['style'] ) ? $atts['style'] : null;
	$columns = isset($atts['columns'] ) ? $atts['columns'] : null;
	$align = isset($atts['align'] ) ? $atts['align'] : null;
	$class = isset($atts['class'] ) ? $atts['class'] : null;

	if ( !has_action( 'add_loop_layout_' . $atts['layout'] ) )
		$atts['layout'] = 'blank';

	//* Add columns classes. This is how our defaults are styled, so add a custom class if style is set to none
	if ( $style == 'none' ) {
		$classes[] = 'custom-loop-columns-' . $columns;	
		$classes[] = 'custom-loop-container';
	} else {
		$classes[] = 'loop-columns-' . $columns;	
		$classes[] = 'loop-container';
	}

	$classes[] = 'loop-container-align-' . $align;
	$classes[] = 'loop-layout-' . $layout;
	$classes[] = $class;

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
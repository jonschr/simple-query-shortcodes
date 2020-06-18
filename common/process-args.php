<?php

/**
 * Layout CPT defaults
 */
add_filter( 'gsq_do_args_defaults', 'gsq_custom_post_type_default_layout', 5, 1 );
function gsq_custom_post_type_default_layout( $args ) {
	
	// Bail if there's already something set, leaving the setting along
	if ( $args['layout'] )
		return $args;

	// Bail if we're using the default layout, leaving the setting along (this situation likely will never happen in practice , since the filter which adds 'default' should come later)
	if ( $args['layout'] == 'default' )
		return $args;

	// If we're setting a custom content type, let's use that as a default if there's nothing there
	if ( $args['post_type'] != 'post' )
		$args['layout'] = $args['post_type'];

	return $args;
}

/**
 * Set default layout
 */
add_filter( 'gsq_do_args_defaults', 'gsq_default_layout', 7, 1 );
function gsq_default_layout( $args ) {

	// If there's no layout being set, use 'default' instead
	if ( !$args['layout'] )
		$args['layout'] = 'default';	

	return $args;
}

/**
 * 'Post' post type category defaults when terms are set
 */
add_filter( 'gsq_do_args_defaults', 'gsq_post_terms', 9, 1 );
function gsq_post_terms( $args ) {
	
	if ( $args['post_type'] != 'post' )
		return $args;

	// If there are terms set but no taxonomy, then let's set ta category name and reset
	if ( $args['terms'] != null && $args['taxonomy'] == null ) {

		$args['category_name'] = $args['terms'];
		
		// Reset things
		$args['terms'] = null;
	}

	return $args;
}

/**
 * 'Post' post type category defaults when category is set
 */
add_filter( 'gsq_do_args_defaults', 'gsq_post_category', 10, 1 );
function gsq_post_category( $args ) {

	// Bail if we're not on a post
	if ( $args['post_type'] != 'post' )
		return $args;

	// If there are terms set but no taxonomy, then let's set ta category name and reset
	if ( $args['category'] != null && $args['taxonomy'] == null ) {

		$args['category_name'] = $args['category'];
		
		// Reset things
		$args['category'] = null;
	}

	return $args;
}

/**
 * CPT autodetect the taxonomy if there's only one
 */
add_filter( 'gsq_do_args_defaults', 'gsq_autodetect_cpt_terms', 10, 1 );
function gsq_autodetect_cpt_terms( $args ) {

	// Bail if it's not a custom post type
	if ( $args['post_type'] == 'post' )
		return $args;

	// Bail if we're not dealing with any terms
	if ( !$args['terms'] && !$args['category'] )
		return $args;

	// Bail if we DID actually set a taxonomy
	if ( $args['taxonomy'] )
		return $args;

	// If there's a category set, let's make it a term instead
	if ( $args['category'] && !$args['terms'] ) {
		
		// Make it a term
		$args['terms'] = $args['category'];

		// Reset the category
		unset( $args['category'] );

	}

	// If a term is provided, but no taxonomy, let's get it automatically if there's only one tax attached to this post type
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
	unset( $args['taxonomy'] );
	unset( $args['field'] );
	unset( $args['terms'] );
	unset( $args['operator'] );

	$args = wp_parse_args( $args, $tax_args );

	return $args;
}

add_filter( 'gsq_do_args_defaults', 'gsq_custom_tax_queries', 12, 1 );
function gsq_custom_tax_queries( $args ) {
	
	//* Bail if the terms aren't set
	if ( !isset( $args['terms'] ) )
		return $args;
		
	//* Bail if the tax isn't set
	if ( !isset( $args['taxonomy'] ) )
		return $args;
		
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
	unset( $args['taxonomy'] );
	unset( $args['field'] );
	unset( $args['terms'] );
	unset( $args['operator'] );

	$args = wp_parse_args( $args, $tax_args );

	return $args;
}
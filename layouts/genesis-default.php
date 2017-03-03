<?php

add_action( 'add_loop_layout_genesis', 'gsq_layout_genesis', $post_id );
function gsq_layout_genesis( $post_id ) {

	global $post;

	do_action( 'genesis_entry_header' );

	do_action( 'genesis_before_entry_content' );

	printf( '<div %s>', genesis_attr( 'entry-content' ) );
	do_action( 'genesis_entry_content' );
	echo '</div>';

	do_action( 'genesis_after_entry_content' );

	do_action( 'genesis_entry_footer' );

}


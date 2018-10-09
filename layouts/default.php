<?php

//* Output each default item
add_action( 'add_loop_layout_default', 'rb_default_each' );
function rb_default_each() {

	//* Global vars
	global $post;

	//* Vars
	$title = get_the_title();
	$permalink = get_the_permalink();

	//* Markup
	printf( '<h3 class="entry-title"><a href="%s">%s</a></h3>', $permalink, $title );
	the_excerpt();
	edit_post_link( null, '<small>', '</small>', get_the_ID(), 'post-edit-link' );

}
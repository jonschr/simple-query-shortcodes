<?php

add_action( 'add_loop_layout_blank', 'gsq_layout_blank', 10, 1 );
function gsq_layout_blank( $post_id ) {

	$permalink = get_the_permalink( $post_id ); 
	$title = get_the_title( $post_id ); 

	printf( '<h3><a href="%s">%s</a></h3>', $permalink, $title );
	edit_post_link( null, '<small>', '</small>', $post_id, 'post-edit-link' );

}
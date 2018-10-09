<?php

function gsq_debug( $args ) {
	if ( $args['debug'] ) {

		echo '<h2 style="text-align: left;">ARGUMENTS BEING PASSED:</h2>';
		echo '<pre style="font-size: 14px; text-align: left;">';
		var_dump( $args );
		echo '</pre>';	
	}
}

function gsq_debug_query( $args, $query ) {
	if ( $args[ 'debug'] ) {

		echo '<h2 style="text-align: left;">QUERY BEING RETURNED:</h2>';
		echo '<pre style="font-size: 14px; text-align: left;">';
		var_dump( $query );
		echo '</pre>';	
	}
}
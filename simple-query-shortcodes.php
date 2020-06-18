<?php

/*
	Plugin Name: Simple Query Shortcodes
	Plugin URI: https://github.com/jonschr/simple-query-shortcodes
    GitHub Plugin URI: https://github.com/jonschr/simple-query-shortcodes
    Description: Just another simple query shortcode plugin.
    Version: 1.1.4
    Author: Jon Schroeder
    Author URI: https://elod.in

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
*/

/* Prevent direct access to the plugin */
if ( !defined( 'ABSPATH' ) ) {
    die( "Sorry, you are not allowed to access this page directly." );
}

//* Plugin directory and version
define( 'SIMPLE_QUERY_SHORTCODES', dirname( __FILE__ ) );
define( 'SIMPLE_QUERY_SHORTCODES_VERSION', '1.1.4' );

//* Enqueue Scripts and Styles
add_action( 'wp_enqueue_scripts', 'gsq_enqueue_scripts_styles' );
add_action( 'enqueue_block_assets', 'gsq_enqueue_scripts_styles' );
function gsq_enqueue_scripts_styles() {

    //* Don't add these scripts and styles to the admin side of the site
    if ( is_admin() )
        return;

	//* Register the main stylesheet
	wp_register_style( 'gsq-styles', plugin_dir_url( __FILE__ ) . 'css/simple-query.css' );

}

/**
 * Backend styles and scripts
 */
// add_action( 'enqueue_block_editor_assets', 'gsq_enqueue_scripts_styles_gutenberg' );
function gsq_enqueue_scripts_styles_gutenberg() {

    //* Register the main stylesheet
	wp_register_style( 'gsq-styles', '/css/simple-query.css' );

}

//* Includes
include_once( 'common/process-args.php' );
include_once( 'common/basic-loop-hooks.php' );
include_once( 'common/debug.php' );

//* Layouts
include_once( 'layouts/blank.php' );
include_once( 'layouts/default.php' );

//* Main Shortcode
include_once( 'common/shortcode.php' );

//* Updater
require 'vendor/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/jonschr/simple-query-shortcodes',
	__FILE__,
	'simple-query-shortcodes'
);

// Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');
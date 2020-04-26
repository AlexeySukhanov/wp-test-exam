<?php
/**
 * fino-child functions and definitions
 *
 */

/**
 * Adds custom post types and associated taxonomies.
 */
require 'cpt/artists.php';
require 'cpt/releases.php';

/**
 * Adds css && js to configure admin panel
 */
function configure_admin_interface(){

    // Adds year select to year taxonomy page
    wp_enqueue_style( 'admin-custom-css', get_stylesheet_directory_uri() . '/assets/css/admin-custom.css' );

    // Adds year select to year taxonomy page
    wp_enqueue_script( 'year-select-js', get_stylesheet_directory_uri() . '/assets/js/year-select.js', array( 'jquery' ), '', true );
    wp_enqueue_script( 'admin-custom-js', get_stylesheet_directory_uri() . '/assets/js/admin-custom.js', array( 'jquery', 'year-select-js' ), '', true );

}
add_action( 'admin_enqueue_scripts', 'configure_admin_interface' );
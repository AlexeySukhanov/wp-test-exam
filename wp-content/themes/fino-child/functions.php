<?php
/**
 * fino-child functions and definitions
 *
 */

//Add custom post types and associated taxonomies.
require 'cpt/artists.php';
require 'cpt/releases.php';

/**
 * Adds year select to year taxonomy page
 */
function add_year_select(){
    $request = $_SERVER['REQUEST_URI'];

    if ( $request == '/wp-admin/edit-tags.php?taxonomy=year&post_type=releases') {
        $status = 'show years';
        wp_enqueue_script('year-select-js', get_stylesheet_directory_uri() . '/assets/js/year-select.js', array('jquery'), '', true);
        wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery', 'year-select-js'), '', true);
    }
}
add_action( 'admin_enqueue_scripts', 'add_year_select' );
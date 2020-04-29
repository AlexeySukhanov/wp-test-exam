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

/**
 * Adds ui css && js
 */
function add_user_scripts(){
    wp_enqueue_script( 'user-custom-js', get_stylesheet_directory_uri() . '/assets/js/user-custom.js', array( 'jquery' ), '', true );
}
add_action( 'wp_enqueue_scripts', 'add_user_scripts' );


/**
 * Custom releases order
 */
add_filter( 'facetwp_sort_options', function( $options, $params ) {
    $options['default']['label'] = 'My sort label';
    $options = array(
        'title_asc' => array(
            'label' => 'Order by Title',
            'query_args' => array(
                'orderby' => 'title',
                'order' => 'ASC',
            )
        ),
        'date_desc' => array(
            'label' => 'Order by Date',
            'query_args' => array(
                'orderby' => 'date',
                'order' => 'DESC',
            )
        ),

    );
    return $options;
}, 10, 2 );


/**
 * Include customized lastViewed class
 */
require_once 'custom-last-viewed.php';




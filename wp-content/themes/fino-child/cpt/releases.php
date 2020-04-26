<?php
/*
 * Releases CPT settings
 *
 */


/*
 * Initiates Releases CPT
 */
function register_releases_cpt() {

    $labels = array(
        'name' => 'Releases',
        'singular_name' => 'Release',
        'add_new' => 'Add Release',
        'add_new_item' => 'Add New release',
        'edit_item' => 'Edit Release',
        'new_item' => 'New Release',
        'all_items' => 'All Releases',
        'view_item' => 'View Release',
        'search_items' => 'Find Releases',
        'not_found' =>  'Release not found.',
        'not_found_in_trash' => 'No releases in the trash.',
        'menu_name' => 'Releases'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-format-audio',
        'menu_position' => 21,
        'supports' => array( 'title', 'comments', 'thumbnail')
    );

    register_post_type('releases', $args);

}
add_action( 'init', 'register_releases_cpt' );


/*
 * Filters Releases CPT updated messages
 */
function filters_releases_messages( $messages ) {

    global $post, $post_ID;

    $messages['releases'] = array(
        0 => '',
        1 => sprintf( 'Release updated. <a href="%s">View</a>', esc_url( get_permalink($post_ID) ) ),
        2 => 'Release info field updated.',
        3 => 'Release info field deleted.',
        4 => 'Release updated.',
        5 => isset($_GET['revision']) ? sprintf( 'Artist restored to revision from %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( 'Release published. <a href="%s">View</a>', esc_url( get_permalink($post_ID) ) ),
        7 => 'Release saved.',
        8 => sprintf( 'Release submitted. <a target="_blank" href="%s">View</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( 'Release scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">View</a>', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( 'Release draft updated.. <a target="_blank" href="%s">View</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );

    return $messages;

}
add_filter( 'post_updated_messages', 'filters_releases_messages' );


/*
 * Adds Releases CPT taxonomies
 */
function add_formats_taxonomies() {

    register_taxonomy('genre',
        array('releases'),
        array(
            'hierarchical' => false,
            'labels' => array(
                'name' => 'Genres',
                'singular_name' => 'Genre',
                'search_items' =>  'Search Genres',
                'popular_items' => 'Popular Genres',
                'all_items' => 'All Genres',
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => 'Edit Genre',
                'update_item' => 'Update Genre',
                'add_new_item' => 'Add New Genre',
                'new_item_name' => 'New Genre Name',
                'separate_items_with_commas' => 'Separate genres with commas',
                'add_or_remove_items' => 'Add or remove genres',
                'choose_from_most_used' => 'Choose from mose used genres',
                'menu_name' => 'Genres'
            ),
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'genre',
                'hierarchical' => false
            ),
        )
    );

    register_taxonomy('style',
        array('releases'),
        array(
            'hierarchical' => false,
            'labels' => array(
                'name' => 'Styles',
                'singular_name' => 'Style',
                'search_items' =>  'Search Styles',
                'popular_items' => 'Popular Styles',
                'all_items' => 'All Styles',
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => 'Edit Style',
                'update_item' => 'Update Style',
                'add_new_item' => 'Add New Style',
                'new_item_name' => 'New Style Name',
                'separate_items_with_commas' => 'Separate styles with commas',
                'add_or_remove_items' => 'Add or remove styles',
                'choose_from_most_used' => 'Choose from mose used styles',
                'menu_name' => 'Styles'
            ),
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'style',
                'hierarchical' => false
            ),
        )
    );

    register_taxonomy('format',
        array('releases'),
        array(
            'hierarchical' => false,
            'labels' => array(
                'name' => 'Formats',
                'singular_name' => 'Format',
                'search_items' =>  'Search Formats',
                'popular_items' => 'Popular Formats',
                'all_items' => 'All Formats',
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => 'Edit Format',
                'update_item' => 'Update Format',
                'add_new_item' => 'Add New Format',
                'new_item_name' => 'New Format Name',
                'separate_items_with_commas' => 'Separate formats with commas',
                'add_or_remove_items' => 'Add or remove formats',
                'choose_from_most_used' => 'Choose from mose used formats',
                'menu_name' => 'Formats'
            ),
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'format',
                'hierarchical' => false
            ),
        )
    );

    register_taxonomy('country',
        array('releases'),
        array(
            'hierarchical' => false,
            'labels' => array(
                'name' => 'Countries',
                'singular_name' => 'Country',
                'search_items' =>  'Search Countries',
                'popular_items' => 'Popular Countries',
                'all_items' => 'All Countries',
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => 'Edit Country',
                'update_item' => 'Update Country',
                'add_new_item' => 'Add New Country',
                'new_item_name' => 'New Country Name',
                'separate_items_with_commas' => 'Separate countries with commas',
                'add_or_remove_items' => 'Add or remove countries',
                'choose_from_most_used' => 'Choose from mose used countries',
                'menu_name' => 'Countries'
            ),
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'country',
                'hierarchical' => false
            ),
        )
    );

    register_taxonomy('year',
        array('releases'),
        array(
            'hierarchical' => false,
            'labels' => array(
                'name' => 'Years',
                'singular_name' => 'Year',
                'search_items' =>  'Search Years',
                'popular_items' => 'Popular Years',
                'all_items' => 'All Years',
                'parent_item' => null,
                'parent_item_colon' => null,
                'edit_item' => 'Edit Year',
                'update_item' => 'Update Year',
                'add_new_item' => 'Add New Year',
                'new_item_name' => 'New Year Value',
                'separate_items_with_commas' => 'Separate years with commas',
                'add_or_remove_items' => 'Add or remove years',
                'choose_from_most_used' => 'Choose from mose used years',
                'menu_name' => 'Years'
            ),
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'year',
                'hierarchical' => false
            ),
        )
    );

}
add_action( 'init', 'add_formats_taxonomies', 0 );
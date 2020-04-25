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
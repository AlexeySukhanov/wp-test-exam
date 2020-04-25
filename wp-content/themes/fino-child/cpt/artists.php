<?php
/*
 * Artists CPT settings
 *
 */


/*
 * Initiates Artists CPT
 */
function register_artists_cpt() {

    $labels = array(
        'name' => 'Artists',
        'singular_name' => 'Artist',
        'add_new' => 'Add Artist',
        'add_new_item' => 'Add New Artist',
        'edit_item' => 'Edit Artist',
        'new_item' => 'New Artist',
        'all_items' => 'All Artists',
        'view_item' => 'View Artist',
        'search_items' => 'Find Artists',
        'not_found' =>  'Artist not found.',
        'not_found_in_trash' => 'No artists in the trash.',
        'menu_name' => 'Artists'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-groups',
        'menu_position' => 20,
        'supports' => array( 'title', 'editor', 'thumbnail')
    );

    register_post_type('artists', $args);

}
add_action( 'init', 'register_artists_cpt' );


/*
 * Filters Artists CPT updated messages
 */
function filters_artists_messages( $messages ) {

    global $post, $post_ID;

    $messages['artists'] = array(
        0 => '',
        1 => sprintf( 'Artist updated. <a href="%s">View</a>', esc_url( get_permalink($post_ID) ) ),
        2 => 'Artist info field updated.',
        3 => 'Artist info field deleted.',
        4 => 'Artist updated.',
        5 => isset($_GET['revision']) ? sprintf( 'Artist restored to revision from %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( 'Artist published. <a href="%s">View</a>', esc_url( get_permalink($post_ID) ) ),
        7 => 'Artist saved.',
        8 => sprintf( 'Artist submitted. <a target="_blank" href="%s">View</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( 'Artist scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">View</a>', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( 'Artist draft updated.. <a target="_blank" href="%s">View</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );

    return $messages;

}
add_filter( 'post_updated_messages', 'filters_artists_messages' );
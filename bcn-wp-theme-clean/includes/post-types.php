<?php
/**
 * Custom Post Types and Taxonomies
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

/**
 * Register Custom Post Types
 */
function bcn_register_post_types() {
    // Register Community Event Post Type
    register_post_type(
        'bcn_event',
        array(
            'labels'              => array(
                'name'               => _x('Events', 'Post Type General Name', 'bcn-wp-theme'),
                'singular_name'      => _x('Event', 'Post Type Singular Name', 'bcn-wp-theme'),
                'menu_name'          => __('Events', 'bcn-wp-theme'),
                'name_admin_bar'     => __('Event', 'bcn-wp-theme'),
                'add_new_item'       => __('Add New Event', 'bcn-wp-theme'),
                'edit_item'          => __('Edit Event', 'bcn-wp-theme'),
                'view_item'          => __('View Event', 'bcn-wp-theme'),
                'all_items'          => __('All Events', 'bcn-wp-theme'),
                'search_items'       => __('Search Events', 'bcn-wp-theme'),
                'not_found'          => __('No events found.', 'bcn-wp-theme'),
            ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'events'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-calendar-alt',
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt', 'comments'),
            'show_in_rest'        => true,
        )
    );

    // Register Community Member Post Type
    register_post_type(
        'bcn_member',
        array(
            'labels'              => array(
                'name'               => _x('Members', 'Post Type General Name', 'bcn-wp-theme'),
                'singular_name'      => _x('Member', 'Post Type Singular Name', 'bcn-wp-theme'),
                'menu_name'          => __('Members', 'bcn-wp-theme'),
                'name_admin_bar'     => __('Member', 'bcn-wp-theme'),
                'add_new_item'       => __('Add New Member', 'bcn-wp-theme'),
                'edit_item'          => __('Edit Member', 'bcn-wp-theme'),
                'view_item'          => __('View Member', 'bcn-wp-theme'),
                'all_items'          => __('All Members', 'bcn-wp-theme'),
                'search_items'       => __('Search Members', 'bcn-wp-theme'),
                'not_found'          => __('No members found.', 'bcn-wp-theme'),
            ),
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'members'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 6,
            'menu_icon'           => 'dashicons-groups',
            'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
            'show_in_rest'        => true,
        )
    );
}
add_action('init', 'bcn_register_post_types');

/**
 * Register Custom Taxonomies
 */
function bcn_register_taxonomies() {
    // Register Event Category Taxonomy
    register_taxonomy(
        'event_category',
        'bcn_event',
        array(
            'labels'            => array(
                'name'          => _x('Event Categories', 'taxonomy general name', 'bcn-wp-theme'),
                'singular_name' => _x('Event Category', 'taxonomy singular name', 'bcn-wp-theme'),
                'search_items'  => __('Search Event Categories', 'bcn-wp-theme'),
                'all_items'     => __('All Event Categories', 'bcn-wp-theme'),
                'edit_item'     => __('Edit Event Category', 'bcn-wp-theme'),
                'update_item'   => __('Update Event Category', 'bcn-wp-theme'),
                'add_new_item'  => __('Add New Event Category', 'bcn-wp-theme'),
                'new_item_name' => __('New Event Category Name', 'bcn-wp-theme'),
                'menu_name'     => __('Event Categories', 'bcn-wp-theme'),
            ),
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-category'),
            'show_in_rest'      => true,
        )
    );

    // Register Membership Level Taxonomy
    register_taxonomy(
        'bcn_membership_level',
        'bcn_member',
        array(
            'labels'            => array(
                'name'          => _x('Membership Levels', 'taxonomy general name', 'bcn-wp-theme'),
                'singular_name' => _x('Membership Level', 'taxonomy singular name', 'bcn-wp-theme'),
                'search_items'  => __('Search Membership Levels', 'bcn-wp-theme'),
                'all_items'     => __('All Membership Levels', 'bcn-wp-theme'),
                'edit_item'     => __('Edit Membership Level', 'bcn-wp-theme'),
                'update_item'   => __('Update Membership Level', 'bcn-wp-theme'),
                'add_new_item'  => __('Add New Membership Level', 'bcn-wp-theme'),
                'new_item_name' => __('New Membership Level Name', 'bcn-wp-theme'),
                'menu_name'     => __('Membership Levels', 'bcn-wp-theme'),
            ),
            'hierarchical'      => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'membership'),
            'show_in_rest'      => true,
        )
    );
}
add_action('init', 'bcn_register_taxonomies');

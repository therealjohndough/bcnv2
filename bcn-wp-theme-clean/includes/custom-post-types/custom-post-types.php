<?php
/**
 * Custom Post Types for BCN Theme
 * 
 * @package BCN_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_Custom_Post_Types {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('init', array($this, 'add_rewrite_rules'));
        add_filter('post_updated_messages', array($this, 'custom_post_type_messages'));
    }
    
    public function register_post_types() {
        $this->register_events_post_type();
        $this->register_news_post_type();
        $this->register_members_post_type();
        $this->register_resources_post_type();
        $this->register_testimonials_post_type();
    }
    
    public function register_taxonomies() {
        $this->register_event_types_taxonomy();
        $this->register_news_categories_taxonomy();
        $this->register_member_types_taxonomy();
    }
    
    private function register_events_post_type() {
        $labels = array(
            'name' => 'Events',
            'singular_name' => 'Event',
            'menu_name' => 'Events',
            'name_admin_bar' => 'Event',
            'archives' => 'Event Archives',
            'attributes' => 'Event Attributes',
            'parent_item_colon' => 'Parent Event:',
            'all_items' => 'All Events',
            'add_new_item' => 'Add New Event',
            'add_new' => 'Add New',
            'new_item' => 'New Event',
            'edit_item' => 'Edit Event',
            'update_item' => 'Update Event',
            'view_item' => 'View Event',
            'view_items' => 'View Events',
            'search_items' => 'Search Events',
            'not_found' => 'Not found',
            'not_found_in_trash' => 'Not found in Trash',
            'featured_image' => 'Featured Image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
            'insert_into_item' => 'Insert into event',
            'uploaded_to_this_item' => 'Uploaded to this event',
            'items_list' => 'Events list',
            'items_list_navigation' => 'Events list navigation',
            'filter_items_list' => 'Filter events list',
        );
        
        $args = array(
            'label' => 'Events',
            'description' => 'BCN Events',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields'),
            'taxonomies' => array('event_type'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-calendar-alt',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => 'events',
                'with_front' => false,
            ),
        );
        
        register_post_type('bcn_event', $args);
    }
    
    private function register_news_post_type() {
        $labels = array(
            'name' => 'News',
            'singular_name' => 'News Article',
            'menu_name' => 'News',
            'name_admin_bar' => 'News Article',
            'archives' => 'News Archives',
            'attributes' => 'News Article Attributes',
            'parent_item_colon' => 'Parent News Article:',
            'all_items' => 'All News Articles',
            'add_new_item' => 'Add New News Article',
            'add_new' => 'Add New',
            'new_item' => 'New News Article',
            'edit_item' => 'Edit News Article',
            'update_item' => 'Update News Article',
            'view_item' => 'View News Article',
            'view_items' => 'View News Articles',
            'search_items' => 'Search News Articles',
            'not_found' => 'Not found',
            'not_found_in_trash' => 'Not found in Trash',
            'featured_image' => 'Featured Image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
            'insert_into_item' => 'Insert into news article',
            'uploaded_to_this_item' => 'Uploaded to this news article',
            'items_list' => 'News articles list',
            'items_list_navigation' => 'News articles list navigation',
            'filter_items_list' => 'Filter news articles list',
        );
        
        $args = array(
            'label' => 'News',
            'description' => 'BCN News Articles',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields', 'author'),
            'taxonomies' => array('news_category'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 6,
            'menu_icon' => 'dashicons-megaphone',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => 'news',
                'with_front' => false,
            ),
        );
        
        register_post_type('bcn_news', $args);
    }
    
    private function register_members_post_type() {
        $labels = array(
            'name' => 'Members',
            'singular_name' => 'Member',
            'menu_name' => 'Members',
            'name_admin_bar' => 'Member',
            'archives' => 'Member Archives',
            'attributes' => 'Member Attributes',
            'parent_item_colon' => 'Parent Member:',
            'all_items' => 'All Members',
            'add_new_item' => 'Add New Member',
            'add_new' => 'Add New',
            'new_item' => 'New Member',
            'edit_item' => 'Edit Member',
            'update_item' => 'Update Member',
            'view_item' => 'View Member',
            'view_items' => 'View Members',
            'search_items' => 'Search Members',
            'not_found' => 'Not found',
            'not_found_in_trash' => 'Not found in Trash',
            'featured_image' => 'Profile Photo',
            'set_featured_image' => 'Set profile photo',
            'remove_featured_image' => 'Remove profile photo',
            'use_featured_image' => 'Use as profile photo',
            'insert_into_item' => 'Insert into member',
            'uploaded_to_this_item' => 'Uploaded to this member',
            'items_list' => 'Members list',
            'items_list_navigation' => 'Members list navigation',
            'filter_items_list' => 'Filter members list',
        );
        
        $args = array(
            'label' => 'Members',
            'description' => 'BCN Members',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields'),
            'taxonomies' => array('member_type'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 7,
            'menu_icon' => 'dashicons-groups',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => 'members',
                'with_front' => false,
            ),
        );
        
        register_post_type('bcn_member', $args);
    }
    
    private function register_resources_post_type() {
        $labels = array(
            'name' => 'Resources',
            'singular_name' => 'Resource',
            'menu_name' => 'Resources',
            'name_admin_bar' => 'Resource',
            'archives' => 'Resource Archives',
            'attributes' => 'Resource Attributes',
            'parent_item_colon' => 'Parent Resource:',
            'all_items' => 'All Resources',
            'add_new_item' => 'Add New Resource',
            'add_new' => 'Add New',
            'new_item' => 'New Resource',
            'edit_item' => 'Edit Resource',
            'update_item' => 'Update Resource',
            'view_item' => 'View Resource',
            'view_items' => 'View Resources',
            'search_items' => 'Search Resources',
            'not_found' => 'Not found',
            'not_found_in_trash' => 'Not found in Trash',
            'featured_image' => 'Featured Image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
            'insert_into_item' => 'Insert into resource',
            'uploaded_to_this_item' => 'Uploaded to this resource',
            'items_list' => 'Resources list',
            'items_list_navigation' => 'Resources list navigation',
            'filter_items_list' => 'Filter resources list',
        );
        
        $args = array(
            'label' => 'Resources',
            'description' => 'BCN Member Resources',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 8,
            'menu_icon' => 'dashicons-portfolio',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => 'resources',
                'with_front' => false,
            ),
        );
        
        register_post_type('bcn_resource', $args);
    }
    
    private function register_testimonials_post_type() {
        $labels = array(
            'name' => 'Testimonials',
            'singular_name' => 'Testimonial',
            'menu_name' => 'Testimonials',
            'name_admin_bar' => 'Testimonial',
            'archives' => 'Testimonial Archives',
            'attributes' => 'Testimonial Attributes',
            'parent_item_colon' => 'Parent Testimonial:',
            'all_items' => 'All Testimonials',
            'add_new_item' => 'Add New Testimonial',
            'add_new' => 'Add New',
            'new_item' => 'New Testimonial',
            'edit_item' => 'Edit Testimonial',
            'update_item' => 'Update Testimonial',
            'view_item' => 'View Testimonial',
            'view_items' => 'View Testimonials',
            'search_items' => 'Search Testimonials',
            'not_found' => 'Not found',
            'not_found_in_trash' => 'Not found in Trash',
            'featured_image' => 'Featured Image',
            'set_featured_image' => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image' => 'Use as featured image',
            'insert_into_item' => 'Insert into testimonial',
            'uploaded_to_this_item' => 'Uploaded to this testimonial',
            'items_list' => 'Testimonials list',
            'items_list_navigation' => 'Testimonials list navigation',
            'filter_items_list' => 'Filter testimonials list',
        );
        
        $args = array(
            'label' => 'Testimonials',
            'description' => 'BCN Testimonials',
            'labels' => $labels,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields'),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 9,
            'menu_icon' => 'dashicons-format-quote',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => 'testimonials',
                'with_front' => false,
            ),
        );
        
        register_post_type('bcn_testimonial', $args);
    }
    
    private function register_event_types_taxonomy() {
        $labels = array(
            'name' => 'Event Types',
            'singular_name' => 'Event Type',
            'menu_name' => 'Event Types',
            'all_items' => 'All Event Types',
            'parent_item' => 'Parent Event Type',
            'parent_item_colon' => 'Parent Event Type:',
            'new_item_name' => 'New Event Type Name',
            'add_new_item' => 'Add New Event Type',
            'edit_item' => 'Edit Event Type',
            'update_item' => 'Update Event Type',
            'view_item' => 'View Event Type',
            'separate_items_with_commas' => 'Separate event types with commas',
            'add_or_remove_items' => 'Add or remove event types',
            'choose_from_most_used' => 'Choose from the most used',
            'popular_items' => 'Popular Event Types',
            'search_items' => 'Search Event Types',
            'not_found' => 'Not Found',
            'no_terms' => 'No event types',
            'items_list' => 'Event types list',
            'items_list_navigation' => 'Event types list navigation',
        );
        
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => 'event-type',
                'with_front' => false,
            ),
        );
        
        register_taxonomy('event_type', array('bcn_event'), $args);
    }
    
    private function register_news_categories_taxonomy() {
        $labels = array(
            'name' => 'News Categories',
            'singular_name' => 'News Category',
            'menu_name' => 'News Categories',
            'all_items' => 'All News Categories',
            'parent_item' => 'Parent News Category',
            'parent_item_colon' => 'Parent News Category:',
            'new_item_name' => 'New News Category Name',
            'add_new_item' => 'Add New News Category',
            'edit_item' => 'Edit News Category',
            'update_item' => 'Update News Category',
            'view_item' => 'View News Category',
            'separate_items_with_commas' => 'Separate news categories with commas',
            'add_or_remove_items' => 'Add or remove news categories',
            'choose_from_most_used' => 'Choose from the most used',
            'popular_items' => 'Popular News Categories',
            'search_items' => 'Search News Categories',
            'not_found' => 'Not Found',
            'no_terms' => 'No news categories',
            'items_list' => 'News categories list',
            'items_list_navigation' => 'News categories list navigation',
        );
        
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => 'news-category',
                'with_front' => false,
            ),
        );
        
        register_taxonomy('news_category', array('bcn_news'), $args);
    }
    
    private function register_member_types_taxonomy() {
        $labels = array(
            'name' => 'Member Types',
            'singular_name' => 'Member Type',
            'menu_name' => 'Member Types',
            'all_items' => 'All Member Types',
            'parent_item' => 'Parent Member Type',
            'parent_item_colon' => 'Parent Member Type:',
            'new_item_name' => 'New Member Type Name',
            'add_new_item' => 'Add New Member Type',
            'edit_item' => 'Edit Member Type',
            'update_item' => 'Update Member Type',
            'view_item' => 'View Member Type',
            'separate_items_with_commas' => 'Separate member types with commas',
            'add_or_remove_items' => 'Add or remove member types',
            'choose_from_most_used' => 'Choose from the most used',
            'popular_items' => 'Popular Member Types',
            'search_items' => 'Search Member Types',
            'not_found' => 'Not Found',
            'no_terms' => 'No member types',
            'items_list' => 'Member types list',
            'items_list_navigation' => 'Member types list navigation',
        );
        
        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => array(
                'slug' => 'member-type',
                'with_front' => false,
            ),
        );
        
        register_taxonomy('member_type', array('bcn_member'), $args);
    }
    
    public function add_rewrite_rules() {
        // Flush rewrite rules on theme activation
        if (get_option('bcn_flush_rewrite_rules')) {
            flush_rewrite_rules();
            delete_option('bcn_flush_rewrite_rules');
        }
    }
    
    public function custom_post_type_messages($messages) {
        $post = get_post();
        $post_type = get_post_type($post);
        $post_type_object = get_post_type_object($post_type);
        
        if ($post_type_object) {
            $messages['bcn_event'] = array(
                0 => '',
                1 => 'Event updated.',
                2 => 'Custom field updated.',
                3 => 'Custom field deleted.',
                4 => 'Event updated.',
                5 => isset($_GET['revision']) ? sprintf('Event restored to revision from %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
                6 => 'Event published.',
                7 => 'Event saved.',
                8 => 'Event submitted.',
                9 => sprintf('Event scheduled for: <strong>%1$s</strong>.', date_i18n('M j, Y @ G:i', strtotime($post->post_date))),
                10 => 'Event draft updated.',
            );
            
            $messages['bcn_news'] = array(
                0 => '',
                1 => 'News article updated.',
                2 => 'Custom field updated.',
                3 => 'Custom field deleted.',
                4 => 'News article updated.',
                5 => isset($_GET['revision']) ? sprintf('News article restored to revision from %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
                6 => 'News article published.',
                7 => 'News article saved.',
                8 => 'News article submitted.',
                9 => sprintf('News article scheduled for: <strong>%1$s</strong>.', date_i18n('M j, Y @ G:i', strtotime($post->post_date))),
                10 => 'News article draft updated.',
            );
            
            $messages['bcn_member'] = array(
                0 => '',
                1 => 'Member updated.',
                2 => 'Custom field updated.',
                3 => 'Custom field deleted.',
                4 => 'Member updated.',
                5 => isset($_GET['revision']) ? sprintf('Member restored to revision from %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
                6 => 'Member published.',
                7 => 'Member saved.',
                8 => 'Member submitted.',
                9 => sprintf('Member scheduled for: <strong>%1$s</strong>.', date_i18n('M j, Y @ G:i', strtotime($post->post_date))),
                10 => 'Member draft updated.',
            );
        }
        
        return $messages;
    }
}

// Initialize the custom post types
new BCN_Custom_Post_Types();

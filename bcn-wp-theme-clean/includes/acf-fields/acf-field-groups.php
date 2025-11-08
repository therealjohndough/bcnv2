<?php
/**
 * ACF Field Groups for BCN Theme
 * 
 * @package BCN_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_ACF_Field_Groups {
    
    public function __construct() {
        add_action('acf/init', array($this, 'register_field_groups'));
        add_action('acf/init', array($this, 'register_options_pages'));
    }
    
    public function register_field_groups() {
        $this->register_event_fields();
        $this->register_member_fields();
        $this->register_news_fields();
        $this->register_page_fields();
        $this->register_global_fields();
    }
    
    public function register_options_pages() {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(array(
                'page_title' => 'BCN Site Options',
                'menu_title' => 'Site Options',
                'menu_slug' => 'bcn-site-options',
                'capability' => 'manage_options',
                'icon_url' => 'dashicons-admin-settings',
                'position' => 2
            ));
        }
    }
    
    private function register_event_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_bcn_event',
                'title' => 'Event Details',
                'fields' => array(
                    array(
                        'key' => 'field_event_title',
                        'label' => 'Event Title',
                        'name' => 'event_title',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => 'Enter event title',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_event_description',
                        'label' => 'Event Description',
                        'name' => 'event_description',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'tabs' => 'all',
                        'toolbar' => 'full',
                        'media_upload' => 1,
                        'delay' => 1,
                    ),
                    array(
                        'key' => 'field_event_date',
                        'label' => 'Event Date',
                        'name' => 'event_date',
                        'type' => 'date_time_picker',
                        'required' => 1,
                        'display_format' => 'F j, Y g:i a',
                        'return_format' => 'Y-m-d H:i:s',
                        'first_day' => 1,
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_event_location',
                        'label' => 'Event Location',
                        'name' => 'event_location',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => 'Enter event location',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_event_capacity',
                        'label' => 'Event Capacity',
                        'name' => 'event_capacity',
                        'type' => 'number',
                        'min' => 1,
                        'placeholder' => 'Enter maximum capacity',
                        'wrapper' => array(
                            'width' => '33',
                        ),
                    ),
                    array(
                        'key' => 'field_event_price',
                        'label' => 'Event Price',
                        'name' => 'event_price',
                        'type' => 'number',
                        'min' => 0,
                        'step' => 0.01,
                        'placeholder' => '0.00',
                        'wrapper' => array(
                            'width' => '33',
                        ),
                    ),
                    array(
                        'key' => 'field_event_type',
                        'label' => 'Event Type',
                        'name' => 'event_type',
                        'type' => 'select',
                        'required' => 1,
                        'choices' => array(
                            'workshop' => 'Workshop',
                            'networking' => 'Networking',
                            'education' => 'Educational',
                            'social' => 'Social',
                            'meeting' => 'Meeting',
                            'other' => 'Other',
                        ),
                        'default_value' => 'workshop',
                        'wrapper' => array(
                            'width' => '34',
                        ),
                    ),
                    array(
                        'key' => 'field_event_image',
                        'label' => 'Event Image',
                        'name' => 'event_image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_registration_required',
                        'label' => 'Registration Required',
                        'name' => 'registration_required',
                        'type' => 'true_false',
                        'default_value' => 1,
                        'ui' => 1,
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_registration_url',
                        'label' => 'Registration URL',
                        'name' => 'registration_url',
                        'type' => 'url',
                        'placeholder' => 'https://example.com/register',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_registration_required',
                                    'operator' => '==',
                                    'value' => '1',
                                ),
                            ),
                        ),
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'bcn_event',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => array(
                    0 => 'the_content',
                ),
            ));
        }
    }
    
    private function register_member_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_bcn_member',
                'title' => 'Member Details',
                'fields' => array(
                    array(
                        'key' => 'field_member_first_name',
                        'label' => 'First Name',
                        'name' => 'member_first_name',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => 'Enter first name',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_member_last_name',
                        'label' => 'Last Name',
                        'name' => 'member_last_name',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => 'Enter last name',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_member_email',
                        'label' => 'Email Address',
                        'name' => 'member_email',
                        'type' => 'email',
                        'required' => 1,
                        'placeholder' => 'Enter email address',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_member_phone',
                        'label' => 'Phone Number',
                        'name' => 'member_phone',
                        'type' => 'text',
                        'placeholder' => 'Enter phone number',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_member_company',
                        'label' => 'Company',
                        'name' => 'member_company',
                        'type' => 'text',
                        'placeholder' => 'Enter company name',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_member_title',
                        'label' => 'Job Title',
                        'name' => 'member_title',
                        'type' => 'text',
                        'placeholder' => 'Enter job title',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_member_bio',
                        'label' => 'Biography',
                        'name' => 'member_bio',
                        'type' => 'wysiwyg',
                        'tabs' => 'all',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                        'delay' => 1,
                    ),
                    array(
                        'key' => 'field_member_photo',
                        'label' => 'Profile Photo',
                        'name' => 'member_photo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'bcn_member',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => array(
                    0 => 'the_content',
                ),
            ));
        }
    }
    
    private function register_news_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_bcn_news',
                'title' => 'News Article Details',
                'fields' => array(
                    array(
                        'key' => 'field_article_excerpt',
                        'label' => 'Article Excerpt',
                        'name' => 'article_excerpt',
                        'type' => 'textarea',
                        'rows' => 3,
                        'placeholder' => 'Enter article excerpt',
                        'maxlength' => 160,
                    ),
                    array(
                        'key' => 'field_article_featured_image',
                        'label' => 'Featured Image',
                        'name' => 'article_featured_image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_article_author',
                        'label' => 'Article Author',
                        'name' => 'article_author',
                        'type' => 'user',
                        'role' => array(
                            0 => 'administrator',
                            1 => 'editor',
                            2 => 'author',
                        ),
                        'return_format' => 'array',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_article_featured',
                        'label' => 'Featured Article',
                        'name' => 'article_featured',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'bcn_news',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
            ));
        }
    }
    
    private function register_page_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_bcn_page',
                'title' => 'Page Settings',
                'fields' => array(
                    array(
                        'key' => 'field_page_hero_title',
                        'label' => 'Hero Title',
                        'name' => 'page_hero_title',
                        'type' => 'text',
                        'placeholder' => 'Enter hero title',
                    ),
                    array(
                        'key' => 'field_page_hero_subtitle',
                        'label' => 'Hero Subtitle',
                        'name' => 'page_hero_subtitle',
                        'type' => 'text',
                        'placeholder' => 'Enter hero subtitle',
                    ),
                    array(
                        'key' => 'field_page_hero_image',
                        'label' => 'Hero Image',
                        'name' => 'page_hero_image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'large',
                        'library' => 'all',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'page',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
            ));
        }
    }
    
    private function register_global_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_bcn_site_options',
                'title' => 'Site Options',
                'fields' => array(
                    array(
                        'key' => 'field_site_logo',
                        'label' => 'Site Logo',
                        'name' => 'site_logo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_contact_email',
                        'label' => 'Contact Email',
                        'name' => 'contact_email',
                        'type' => 'email',
                        'placeholder' => 'Enter contact email',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                    array(
                        'key' => 'field_contact_phone',
                        'label' => 'Contact Phone',
                        'name' => 'contact_phone',
                        'type' => 'text',
                        'placeholder' => 'Enter contact phone',
                        'wrapper' => array(
                            'width' => '50',
                        ),
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'bcn-site-options',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
            ));
        }
    }
}

// Initialize the ACF field groups
new BCN_ACF_Field_Groups();

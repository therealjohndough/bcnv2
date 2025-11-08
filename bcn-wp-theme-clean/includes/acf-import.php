<?php
/**
 * ACF Field Group Import
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Import ACF field groups from JSON files
 */
function bcn_import_acf_field_groups() {
    // Check if ACF is active
    if (!function_exists('acf_get_setting')) {
        return;
    }

    // Get the ACF JSON directory
    $acf_json_dir = get_template_directory() . '/acf-json';
    
    if (!is_dir($acf_json_dir)) {
        return;
    }

    // Get all JSON files
    $json_files = glob($acf_json_dir . '/*.json');
    
    if (empty($json_files)) {
        return;
    }

    foreach ($json_files as $json_file) {
        $json_data = file_get_contents($json_file);
        $field_groups = json_decode($json_data, true);
        
        if (is_array($field_groups)) {
            foreach ($field_groups as $field_group) {
                // Check if field group already exists
                $existing = acf_get_field_group($field_group['key']);
                
                if (!$existing) {
                    // Import the field group
                    acf_import_field_group($field_group);
                }
            }
        }
    }
}

/**
 * Auto-import ACF field groups on theme activation
 */
function bcn_auto_import_acf_field_groups() {
    bcn_import_acf_field_groups();
    
    // Flush rewrite rules to ensure new post types work
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'bcn_auto_import_acf_field_groups');

/**
 * Admin notice for ACF field group import
 */
function bcn_acf_import_admin_notice() {
    if (!function_exists('acf_get_setting')) {
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>BCN Theme:</strong> Advanced Custom Fields Pro is required for full functionality. Please install and activate ACF Pro.</p>';
        echo '</div>';
        return;
    }

    // Check if field groups are imported
    $member_fields = acf_get_field_group('group_bcn_member_details');
    
    if (!$member_fields) {
        echo '<div class="notice notice-info is-dismissible">';
        echo '<p><strong>BCN Theme:</strong> <a href="' . admin_url('admin.php?page=acf-tools&tab=import') . '">Import ACF field groups</a> for full member functionality.</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'bcn_acf_import_admin_notice');

/**
 * Create default membership level terms
 */
function bcn_create_default_membership_levels() {
    $default_levels = array(
        'premier-member' => 'Premier Member',
        'pro-member' => 'Pro Member',
        'basic-member' => 'Basic Member'
    );

    foreach ($default_levels as $slug => $name) {
        if (!term_exists($slug, 'bcn_membership_level')) {
            wp_insert_term($name, 'bcn_membership_level', array('slug' => $slug));
        }
    }
}
add_action('init', 'bcn_create_default_membership_levels');

/**
 * Add ACF options page for theme settings
 */
function bcn_add_acf_options_page() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title' => 'BCN Theme Settings',
            'menu_title' => 'BCN Settings',
            'menu_slug' => 'bcn-theme-settings',
            'capability' => 'edit_posts',
            'icon_url' => 'dashicons-admin-settings',
            'position' => 30
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Member Settings',
            'menu_title' => 'Member Settings',
            'menu_slug' => 'bcn-member-settings',
            'parent_slug' => 'bcn-theme-settings',
        ));
    }
}
add_action('acf/init', 'bcn_add_acf_options_page');

/**
 * Create member settings field group
 */
function bcn_create_member_settings_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_bcn_member_settings',
        'title' => 'Member Settings',
        'fields' => array(
            array(
                'key' => 'field_bcn_marquee_speed',
                'label' => 'Marquee Speed',
                'name' => 'bcn_marquee_speed',
                'type' => 'select',
                'choices' => array(
                    'slow' => 'Slow',
                    'medium' => 'Medium',
                    'fast' => 'Fast'
                ),
                'default_value' => 'medium'
            ),
            array(
                'key' => 'field_bcn_members_per_page',
                'label' => 'Members Per Page',
                'name' => 'bcn_members_per_page',
                'type' => 'number',
                'default_value' => 12,
                'min' => 1,
                'max' => 50
            ),
            array(
                'key' => 'field_bcn_show_featured_first',
                'label' => 'Show Featured Members First',
                'name' => 'bcn_show_featured_first',
                'type' => 'true_false',
                'default_value' => 1
            ),
            array(
                'key' => 'field_bcn_enable_member_registration',
                'label' => 'Enable Member Registration',
                'name' => 'bcn_enable_member_registration',
                'type' => 'true_false',
                'default_value' => 1
            ),
            array(
                'key' => 'field_bcn_require_approval',
                'label' => 'Require Admin Approval',
                'name' => 'bcn_require_approval',
                'type' => 'true_false',
                'default_value' => 1
            )
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'bcn-member-settings'
                )
            )
        )
    ));
}
add_action('acf/init', 'bcn_create_member_settings_fields');

/**
 * Helper function to get member data using ACF
 */
function bcn_get_member_data($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (get_post_type($post_id) !== 'bcn_member') {
        return false;
    }

    return array(
        'company' => get_field('bcn_member_company', $post_id),
        'contact_person' => get_field('bcn_member_contact_person', $post_id),
        'email' => get_field('bcn_member_email', $post_id),
        'phone' => get_field('bcn_member_phone', $post_id),
        'website' => get_field('bcn_member_website', $post_id),
        'address' => get_field('bcn_member_address', $post_id),
        'instagram' => get_field('bcn_member_instagram', $post_id),
        'facebook' => get_field('bcn_member_facebook', $post_id),
        'twitter' => get_field('bcn_member_twitter', $post_id),
        'linkedin' => get_field('bcn_member_linkedin', $post_id),
        'youtube' => get_field('bcn_member_youtube', $post_id),
        'tiktok' => get_field('bcn_member_tiktok', $post_id),
        'featured' => get_field('bcn_member_featured', $post_id),
        'can_submit' => get_field('bcn_member_can_submit_content', $post_id),
        'status' => get_field('bcn_member_status', $post_id),
        'business_type' => get_field('bcn_member_business_type', $post_id),
        'license_number' => get_field('bcn_member_license_number', $post_id),
        'services' => get_field('bcn_member_services', $post_id),
        'areas_served' => get_field('bcn_member_areas_served', $post_id),
        'registration_date' => get_field('bcn_member_registration_date', $post_id),
        'last_activity' => get_field('bcn_member_last_activity', $post_id)
    );
}

/**
 * Update member registration to use ACF fields
 */
function bcn_update_member_registration_acf($member_id, $member_data) {
    // Update ACF fields
    update_field('bcn_member_company', $member_data['company'], $member_id);
    update_field('bcn_member_contact_person', $member_data['contact_person'], $member_id);
    update_field('bcn_member_email', $member_data['email'], $member_id);
    update_field('bcn_member_phone', $member_data['phone'], $member_id);
    update_field('bcn_member_website', $member_data['website'], $member_id);
    update_field('bcn_member_address', $member_data['address'], $member_id);
    update_field('bcn_member_featured', $member_data['featured'], $member_id);
    update_field('bcn_member_status', 'pending', $member_id);
    update_field('bcn_member_registration_date', current_time('Y-m-d H:i:s'), $member_id);
    update_field('bcn_member_last_activity', current_time('Y-m-d H:i:s'), $member_id);
}
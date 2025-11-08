
// Include Custom Post Types
require_once get_template_directory() . '/includes/custom-post-types/events.php';

// Include Admin Theme
require_once get_template_directory() . '/admin-theme/admin-theme.php';

// Include Helper Functions
require_once get_template_directory() . '/includes/helper-functions.php';

// Include ACF Field Groups
require_once get_template_directory() . '/includes/acf-fields/field-groups.php';

// Include Automation
require_once get_template_directory() . '/includes/automation/automation.php';

// Include CRM Integration
require_once get_template_directory() . '/includes/crm-integration/crm-client.php';

/**
 * Enqueue admin theme scripts and styles
 */
function bcn_enqueue_admin_assets() {
    // Only load on admin pages
    if (!is_admin()) {
        return;
    }
    
    // Enqueue admin theme
    wp_enqueue_style('bcn-admin-theme', get_template_directory_uri() . '/admin-theme/assets/css/admin-main.css', array(), '1.0.0');
    wp_enqueue_script('bcn-admin-theme', get_template_directory_uri() . '/admin-theme/assets/js/admin-main.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'bcn_enqueue_admin_assets');

/**
 * Helper function to get new members this month
 */
function bcn_get_new_members_this_month() {
    $users = get_users(array(
        'meta_query' => array(
            array(
                'key' => 'user_registered',
                'value' => date('Y-m-01'),
                'compare' => '>='
            )
        )
    ));
    return count($users);
}

/**
 * Helper function to get active members
 */
function bcn_get_active_members() {
    $users = get_users(array(
        'meta_query' => array(
            array(
                'key' => 'last_activity',
                'value' => date('Y-m-d', strtotime('-30 days')),
                'compare' => '>='
            )
        )
    ));
    return count($users);
}

/**
 * Helper function to get upcoming events
 */
function bcn_get_upcoming_events($limit = 5) {
    return get_posts(array(
        'post_type' => 'bcn_event',
        'post_status' => 'publish',
        'numberposts' => $limit,
        'meta_query' => array(
            array(
                'key' => '_bcn_event_date',
                'value' => date('Y-m-d'),
                'compare' => '>='
            )
        ),
        'orderby' => 'meta_value',
        'order' => 'ASC'
    ));
}

/**
 * Helper function to get recent activity
 */
function bcn_get_recent_activity($limit = 10) {
    $activities = array();
    
    // Get recent posts
    $recent_posts = get_posts(array('numberposts' => 5, 'post_status' => 'publish'));
    foreach ($recent_posts as $post) {
        $activities[] = array(
            'icon' => '📝',
            'text' => 'New post published: ' . $post->post_title,
            'time' => human_time_diff(strtotime($post->post_date)) . ' ago'
        );
    }
    
    // Get recent events
    $recent_events = get_posts(array('post_type' => 'bcn_event', 'numberposts' => 3, 'post_status' => 'publish'));
    foreach ($recent_events as $event) {
        $activities[] = array(
            'icon' => '📅',
            'text' => 'New event created: ' . $event->post_title,
            'time' => human_time_diff(strtotime($event->post_date)) . ' ago'
        );
    }
    
    // Sort by time
    usort($activities, function($a, $b) {
        return strtotime($a['time']) - strtotime($b['time']);
    });
    
    return array_slice($activities, 0, $limit);
}

/**
 * Helper function to get last backup date
 */
function bcn_get_last_backup_date() {
    // This would integrate with your backup plugin
    $last_backup = get_option('bcn_last_backup_date');
    if ($last_backup) {
        return date('M j, Y g:i A', strtotime($last_backup));
    }
    return 'Never';
}

/**
 * AJAX handler for updating member stats
 */
function bcn_ajax_update_member_stats() {
    check_ajax_referer('bcn_admin_nonce', 'nonce');
    
    $stats = array(
        'total' => wp_count_users()['total_users'],
        'new' => bcn_get_new_members_this_month(),
        'active' => bcn_get_active_members()
    );
    
    wp_send_json_success($stats);
}
add_action('wp_ajax_bcn_update_member_stats', 'bcn_ajax_update_member_stats');

/**
 * AJAX handler for auto-saving forms
 */
function bcn_ajax_auto_save_form() {
    check_ajax_referer('bcn_admin_nonce', 'nonce');
    
    // Handle form auto-save logic here
    wp_send_json_success(array('message' => 'Form auto-saved'));
}
add_action('wp_ajax_bcn_auto_save_form', 'bcn_ajax_auto_save_form');

/**
 * AJAX handler for bulk actions
 */
function bcn_ajax_bulk_action() {
    check_ajax_referer('bcn_admin_nonce', 'nonce');
    
    $action = sanitize_text_field($_POST['bulk_action']);
    $item_ids = array_map('intval', $_POST['item_ids']);
    
    $processed = 0;
    foreach ($item_ids as $item_id) {
        switch ($action) {
            case 'delete':
                if (wp_delete_post($item_id)) {
                    $processed++;
                }
                break;
            case 'publish':
                if (wp_publish_post($item_id)) {
                    $processed++;
                }
                break;
            // Add more bulk actions as needed
        }
    }
    
    wp_send_json_success(array('message' => "Processed {$processed} items"));
}
add_action('wp_ajax_bcn_bulk_action', 'bcn_ajax_bulk_action');

/**
 * AJAX handler for checking scheduled content
 */
function bcn_ajax_check_scheduled_content() {
    check_ajax_referer('bcn_admin_nonce', 'nonce');
    
    $scheduled_posts = get_posts(array(
        'post_status' => 'future',
        'numberposts' => -1,
        'meta_query' => array(
            array(
                'key' => '_bcn_auto_publish',
                'value' => '1',
                'compare' => '='
            )
        )
    ));
    
    $published = 0;
    foreach ($scheduled_posts as $post) {
        if (strtotime($post->post_date) <= current_time('timestamp')) {
            wp_publish_post($post->ID);
            $published++;
        }
    }
    
    wp_send_json_success(array('published' => $published));
}
add_action('wp_ajax_bcn_check_scheduled_content', 'bcn_ajax_check_scheduled_content');

/**
 * AJAX handler for syncing member data
 */
function bcn_ajax_sync_member_data() {
    check_ajax_referer('bcn_admin_nonce', 'nonce');
    
    // This would integrate with your CRM
    $synced = 0;
    // Add CRM sync logic here
    
    wp_send_json_success(array('synced' => $synced));
}
add_action('wp_ajax_bcn_sync_member_data', 'bcn_ajax_sync_member_data');

/**
 * AJAX handler for sending event reminders
 */
function bcn_ajax_send_event_reminders() {
    check_ajax_referer('bcn_admin_nonce', 'nonce');
    
    $sent = 0;
    // Add event reminder logic here
    
    wp_send_json_success(array('sent' => $sent));
}
add_action('wp_ajax_bcn_send_event_reminders', 'bcn_ajax_send_event_reminders');

/**
 * AJAX handler for processing email queue
 */
function bcn_ajax_process_email_queue() {
    check_ajax_referer('bcn_admin_nonce', 'nonce');
    
    $processed = 0;
    // Add email queue processing logic here
    
    wp_send_json_success(array('processed' => $processed));
}
add_action('wp_ajax_bcn_process_email_queue', 'bcn_ajax_process_email_queue');

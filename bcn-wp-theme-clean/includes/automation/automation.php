<?php
/**
 * Automation Features for BCN Theme
 * 
 * @package BCN_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_Automation {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_ajax_bcn_auto_save_form', array($this, 'auto_save_form'));
        add_action('wp_ajax_bcn_bulk_action', array($this, 'bulk_action'));
        add_action('wp_ajax_bcn_update_member_stats', array($this, 'update_member_stats'));
        add_action('wp_ajax_bcn_check_scheduled_content', array($this, 'check_scheduled_content'));
        add_action('wp_ajax_bcn_sync_member_data', array($this, 'sync_member_data'));
        add_action('wp_ajax_bcn_send_event_reminders', array($this, 'send_event_reminders'));
        add_action('wp_ajax_bcn_process_email_queue', array($this, 'process_email_queue'));
        
        // Cron jobs
        add_action('bcn_daily_cron', array($this, 'daily_automation'));
        add_action('bcn_hourly_cron', array($this, 'hourly_automation'));
        
        // Schedule cron jobs
        if (!wp_next_scheduled('bcn_daily_cron')) {
            wp_schedule_event(time(), 'daily', 'bcn_daily_cron');
        }
        if (!wp_next_scheduled('bcn_hourly_cron')) {
            wp_schedule_event(time(), 'hourly', 'bcn_hourly_cron');
        }
    }
    
    public function init() {
        // Initialize automation features
        $this->setup_auto_publish();
        $this->setup_member_sync();
        $this->setup_event_automation();
        $this->setup_email_automation();
    }
    
    private function setup_auto_publish() {
        // Auto-publish scheduled content
        add_action('wp_scheduled_auto_draft', array($this, 'auto_publish_content'));
        add_action('transition_post_status', array($this, 'handle_post_status_change'), 10, 3);
    }
    
    private function setup_member_sync() {
        // Auto-sync member data with CRM
        add_action('user_register', array($this, 'sync_new_member'));
        add_action('profile_update', array($this, 'sync_member_update'));
        add_action('delete_user', array($this, 'sync_member_delete'));
    }
    
    private function setup_event_automation() {
        // Auto-send event reminders
        add_action('publish_bcn_event', array($this, 'schedule_event_reminders'));
        add_action('bcn_send_event_reminders', array($this, 'send_event_reminders'));
    }
    
    private function setup_email_automation() {
        // Auto-send emails
        add_action('bcn_process_email_queue', array($this, 'process_email_queue'));
    }
    
    // AJAX Handlers
    public function auto_save_form() {
        check_ajax_referer('bcn_admin_nonce', 'nonce');
        
        $form_data = $_POST;
        $post_id = intval($form_data['post_id']);
        
        if (!$post_id) {
            wp_send_json_error('Invalid post ID');
        }
        
        // Update post meta
        foreach ($form_data as $key => $value) {
            if (strpos($key, 'acf') === 0) {
                update_field($key, $value, $post_id);
            }
        }
        
        wp_send_json_success('Form auto-saved');
    }
    
    public function bulk_action() {
        check_ajax_referer('bcn_admin_nonce', 'nonce');
        
        $action = sanitize_text_field($_POST['bulk_action']);
        $item_ids = array_map('intval', $_POST['item_ids']);
        
        $processed = 0;
        $message = '';
        
        switch ($action) {
            case 'publish':
                foreach ($item_ids as $id) {
                    wp_publish_post($id);
                    $processed++;
                }
                $message = "Published {$processed} items";
                break;
                
            case 'trash':
                foreach ($item_ids as $id) {
                    wp_trash_post($id);
                    $processed++;
                }
                $message = "Moved {$processed} items to trash";
                break;
                
            case 'delete':
                foreach ($item_ids as $id) {
                    wp_delete_post($id, true);
                    $processed++;
                }
                $message = "Permanently deleted {$processed} items";
                break;
        }
        
        wp_send_json_success($message);
    }
    
    public function update_member_stats() {
        check_ajax_referer('bcn_admin_nonce', 'nonce');
        
        $total_members = wp_count_users()['total_users'];
        $new_members = $this->get_new_members_this_month();
        $active_members = $this->get_active_members();
        
        wp_send_json_success(array(
            'total' => $total_members,
            'new' => $new_members,
            'active' => $active_members
        ));
    }
    
    public function check_scheduled_content() {
        check_ajax_referer('bcn_admin_nonce', 'nonce');
        
        $scheduled_posts = get_posts(array(
            'post_status' => 'future',
            'numberposts' => -1,
            'meta_query' => array(
                array(
                    'key' => 'auto_publish',
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
    
    public function sync_member_data() {
        check_ajax_referer('bcn_admin_nonce', 'nonce');
        
        // This would integrate with your CRM API
        $synced = $this->sync_members_with_crm();
        
        wp_send_json_success(array('synced' => $synced));
    }
    
    public function send_event_reminders() {
        check_ajax_referer('bcn_admin_nonce', 'nonce');
        
        $sent = $this->process_event_reminders();
        
        wp_send_json_success(array('sent' => $sent));
    }
    
    public function process_email_queue() {
        check_ajax_referer('bcn_admin_nonce', 'nonce');
        
        $processed = $this->process_queued_emails();
        
        wp_send_json_success(array('processed' => $processed));
    }
    
    // Automation Methods
    public function auto_publish_content() {
        $scheduled_posts = get_posts(array(
            'post_status' => 'future',
            'numberposts' => -1,
            'meta_query' => array(
                array(
                    'key' => 'auto_publish',
                    'value' => '1',
                    'compare' => '='
                )
            )
        ));
        
        foreach ($scheduled_posts as $post) {
            if (strtotime($post->post_date) <= current_time('timestamp')) {
                wp_publish_post($post->ID);
                
                // Send notification
                $this->send_notification('Content Published', "Post '{$post->post_title}' was automatically published.");
            }
        }
    }
    
    public function handle_post_status_change($new_status, $old_status, $post) {
        if ($new_status === 'publish' && $old_status !== 'publish') {
            // Auto-generate meta description
            $this->auto_generate_meta_description($post->ID);
            
            // Auto-create social media posts
            $this->auto_create_social_media($post->ID);
            
            // Auto-send notifications
            $this->auto_send_notifications($post->ID);
        }
    }
    
    public function sync_new_member($user_id) {
        $user = get_userdata($user_id);
        
        // Sync with CRM
        $this->sync_member_with_crm($user);
        
        // Send welcome email
        $this->send_welcome_email($user);
        
        // Assign member permissions
        $this->assign_member_permissions($user_id);
    }
    
    public function sync_member_update($user_id) {
        $user = get_userdata($user_id);
        
        // Sync updated data with CRM
        $this->sync_member_with_crm($user);
    }
    
    public function sync_member_delete($user_id) {
        // Remove from CRM
        $this->remove_member_from_crm($user_id);
    }
    
    public function schedule_event_reminders($post_id) {
        $event_date = get_field('event_date', $post_id);
        
        if ($event_date) {
            // Schedule reminder 24 hours before event
            $reminder_time = strtotime($event_date) - (24 * 60 * 60);
            wp_schedule_single_event($reminder_time, 'bcn_send_event_reminders', array($post_id));
        }
    }
    
    public function daily_automation() {
        // Daily automation tasks
        $this->sync_members_with_crm();
        $this->process_queued_emails();
        $this->cleanup_old_data();
        $this->generate_daily_reports();
    }
    
    public function hourly_automation() {
        // Hourly automation tasks
        $this->check_scheduled_content();
        $this->process_event_reminders();
        $this->sync_event_data();
    }
    
    // Helper Methods
    private function get_new_members_this_month() {
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
    
    private function get_active_members() {
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
    
    private function auto_generate_meta_description($post_id) {
        $post = get_post($post_id);
        $excerpt = wp_trim_words($post->post_content, 25);
        
        update_post_meta($post_id, '_yoast_wpseo_metadesc', $excerpt);
    }
    
    private function auto_create_social_media($post_id) {
        $post = get_post($post_id);
        $title = $post->post_title;
        $url = get_permalink($post_id);
        
        // This would integrate with social media APIs
        // For now, just log the action
        error_log("Social media post created for: {$title} - {$url}");
    }
    
    private function auto_send_notifications($post_id) {
        $post = get_post($post_id);
        
        // Send notification to administrators
        $this->send_notification('New Content Published', "New content '{$post->post_title}' has been published.");
    }
    
    private function sync_member_with_crm($user) {
        // This would integrate with your CRM API
        // For now, just log the action
        error_log("Syncing member with CRM: {$user->user_email}");
    }
    
    private function sync_members_with_crm() {
        // Sync all members with CRM
        $users = get_users();
        $synced = 0;
        
        foreach ($users as $user) {
            $this->sync_member_with_crm($user);
            $synced++;
        }
        
        return $synced;
    }
    
    private function remove_member_from_crm($user_id) {
        // Remove member from CRM
        error_log("Removing member from CRM: {$user_id}");
    }
    
    private function send_welcome_email($user) {
        $subject = 'Welcome to Buffalo Cannabis Network!';
        $message = "Hello {$user->display_name},\n\nWelcome to the Buffalo Cannabis Network! We're excited to have you join our community.\n\nBest regards,\nThe BCN Team";
        
        wp_mail($user->user_email, $subject, $message);
    }
    
    private function assign_member_permissions($user_id) {
        $user = new WP_User($user_id);
        $user->add_role('subscriber');
    }
    
    private function process_event_reminders() {
        $events = get_posts(array(
            'post_type' => 'bcn_event',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'value' => date('Y-m-d H:i:s', strtotime('+24 hours')),
                    'compare' => '<='
                )
            )
        ));
        
        $sent = 0;
        foreach ($events as $event) {
            $this->send_event_reminder($event);
            $sent++;
        }
        
        return $sent;
    }
    
    private function send_event_reminder($event) {
        $event_title = $event->post_title;
        $event_date = get_field('event_date', $event->ID);
        $event_location = get_field('event_location', $event->ID);
        
        $subject = "Reminder: {$event_title} Tomorrow";
        $message = "Don't forget about tomorrow's event:\n\n";
        $message .= "Event: {$event_title}\n";
        $message .= "Date: " . date('F j, Y g:i a', strtotime($event_date)) . "\n";
        $message .= "Location: {$event_location}\n\n";
        $message .= "We look forward to seeing you there!\n\n";
        $message .= "Best regards,\nThe BCN Team";
        
        // Send to all members
        $members = get_users(array('role' => 'subscriber'));
        foreach ($members as $member) {
            wp_mail($member->user_email, $subject, $message);
        }
    }
    
    private function process_queued_emails() {
        // Process queued emails
        $queued_emails = get_posts(array(
            'post_type' => 'bcn_email_queue',
            'post_status' => 'publish',
            'numberposts' => 10
        ));
        
        $processed = 0;
        foreach ($queued_emails as $email) {
            $this->send_queued_email($email);
            wp_delete_post($email->ID, true);
            $processed++;
        }
        
        return $processed;
    }
    
    private function send_queued_email($email) {
        $subject = get_field('email_subject', $email->ID);
        $message = get_field('email_message', $email->ID);
        $recipients = get_field('email_recipients', $email->ID);
        
        foreach ($recipients as $recipient) {
            wp_mail($recipient, $subject, $message);
        }
    }
    
    private function cleanup_old_data() {
        // Clean up old data
        $this->cleanup_old_revisions();
        $this->cleanup_old_autosaves();
        $this->cleanup_old_transients();
    }
    
    private function cleanup_old_revisions() {
        global $wpdb;
        
        $wpdb->query("
            DELETE FROM {$wpdb->posts} 
            WHERE post_type = 'revision' 
            AND post_date < DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
    }
    
    private function cleanup_old_autosaves() {
        global $wpdb;
        
        $wpdb->query("
            DELETE FROM {$wpdb->posts} 
            WHERE post_type = 'revision' 
            AND post_name LIKE '%autosave%'
            AND post_date < DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
    }
    
    private function cleanup_old_transients() {
        global $wpdb;
        
        $wpdb->query("
            DELETE FROM {$wpdb->options} 
            WHERE option_name LIKE '_transient_timeout_%' 
            AND option_value < UNIX_TIMESTAMP()
        ");
    }
    
    private function generate_daily_reports() {
        // Generate daily reports
        $report = $this->generate_member_report();
        $this->send_daily_report($report);
    }
    
    private function generate_member_report() {
        $total_members = wp_count_users()['total_users'];
        $new_members = $this->get_new_members_this_month();
        $active_members = $this->get_active_members();
        
        return array(
            'total_members' => $total_members,
            'new_members' => $new_members,
            'active_members' => $active_members,
            'date' => date('Y-m-d')
        );
    }
    
    private function send_daily_report($report) {
        $subject = 'Daily BCN Report - ' . $report['date'];
        $message = "Daily BCN Report\n\n";
        $message .= "Total Members: {$report['total_members']}\n";
        $message .= "New Members This Month: {$report['new_members']}\n";
        $message .= "Active Members: {$report['active_members']}\n\n";
        $message .= "Generated on: " . date('Y-m-d H:i:s');
        
        // Send to administrators
        $admins = get_users(array('role' => 'administrator'));
        foreach ($admins as $admin) {
            wp_mail($admin->user_email, $subject, $message);
        }
    }
    
    private function send_notification($title, $message) {
        // Send notification to administrators
        $admins = get_users(array('role' => 'administrator'));
        foreach ($admins as $admin) {
            wp_mail($admin->user_email, $title, $message);
        }
    }
}

// Initialize the automation features
new BCN_Automation();

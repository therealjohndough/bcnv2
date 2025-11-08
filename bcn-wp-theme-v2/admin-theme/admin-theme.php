<?php
/**
 * BCN Custom Admin Theme
 * 
 * @package BCN_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_Admin_Theme {
    
    public function __construct() {
        add_action('admin_init', array($this, 'init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('admin_menu', array($this, 'customize_admin_menu'));
        add_action('wp_dashboard_setup', array($this, 'custom_dashboard_widgets'));
        add_action('admin_head', array($this, 'custom_admin_styles'));
        add_action('admin_footer', array($this, 'custom_admin_scripts'));
        add_filter('admin_footer_text', array($this, 'custom_admin_footer'));
        add_action('admin_bar_menu', array($this, 'remove_admin_bar_items'), 999);
    }
    
    public function init() {
        // Initialize admin theme
        $this->add_custom_roles();
        $this->add_custom_capabilities();
    }
    
    public function enqueue_admin_scripts($hook) {
        // Enqueue admin CSS
        wp_enqueue_style(
            'bcn-admin-main',
            get_template_directory_uri() . '/admin-theme/assets/css/admin-main.css',
            array(),
            '1.0.0'
        );
        
        wp_enqueue_style(
            'bcn-admin-components',
            get_template_directory_uri() . '/admin-theme/assets/css/admin-components.css',
            array('bcn-admin-main'),
            '1.0.0'
        );
        
        wp_enqueue_style(
            'bcn-admin-forms',
            get_template_directory_uri() . '/admin-theme/assets/css/admin-forms.css',
            array('bcn-admin-main'),
            '1.0.0'
        );
        
        wp_enqueue_style(
            'bcn-admin-responsive',
            get_template_directory_uri() . '/admin-theme/assets/css/admin-responsive.css',
            array('bcn-admin-main'),
            '1.0.0'
        );
        
        // Enqueue admin JavaScript
        wp_enqueue_script(
            'bcn-admin-main',
            get_template_directory_uri() . '/admin-theme/assets/js/admin-main.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_enqueue_script(
            'bcn-admin-forms',
            get_template_directory_uri() . '/admin-theme/assets/js/admin-forms.js',
            array('jquery', 'bcn-admin-main'),
            '1.0.0',
            true
        );
        
        wp_enqueue_script(
            'bcn-admin-dashboard',
            get_template_directory_uri() . '/admin-theme/assets/js/admin-dashboard.js',
            array('jquery', 'bcn-admin-main'),
            '1.0.0',
            true
        );
        
        wp_enqueue_script(
            'bcn-admin-automation',
            get_template_directory_uri() . '/admin-theme/assets/js/admin-automation.js',
            array('jquery', 'bcn-admin-main'),
            '1.0.0',
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('bcn-admin-main', 'bcn_admin_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bcn_admin_nonce'),
            'current_user' => get_current_user_id()
        ));
    }
    
    public function customize_admin_menu() {
        // Remove default WordPress menu items
        remove_menu_page('edit-comments.php');
        remove_menu_page('tools.php');
        
        // Add custom menu items
        add_menu_page(
            'BCN Dashboard',
            'Dashboard',
            'read',
            'bcn-dashboard',
            array($this, 'custom_dashboard_page'),
            'dashicons-chart-line',
            1
        );
        
        add_menu_page(
            'Events',
            'Events',
            'manage_events',
            'bcn-events',
            array($this, 'events_page'),
            'dashicons-calendar-alt',
            2
        );
        
        add_menu_page(
            'Members',
            'Members',
            'manage_members',
            'bcn-members',
            array($this, 'members_page'),
            'dashicons-groups',
            3
        );
        
        add_menu_page(
            'Content',
            'Content',
            'edit_posts',
            'bcn-content',
            array($this, 'content_page'),
            'dashicons-edit',
            4
        );
        
        add_menu_page(
            'Analytics',
            'Analytics',
            'view_analytics',
            'bcn-analytics',
            array($this, 'analytics_page'),
            'dashicons-chart-bar',
            5
        );
        
        add_menu_page(
            'Settings',
            'Settings',
            'manage_options',
            'bcn-settings',
            array($this, 'settings_page'),
            'dashicons-admin-settings',
            6
        );
    }
    
    public function custom_dashboard_widgets() {
        // Remove default WordPress widgets
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_secondary', 'dashboard', 'side');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
        remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        
        // Add custom widgets
        wp_add_dashboard_widget(
            'bcn_member_overview',
            'Member Overview',
            array($this, 'member_overview_widget')
        );
        
        wp_add_dashboard_widget(
            'bcn_event_calendar',
            'Upcoming Events',
            array($this, 'event_calendar_widget')
        );
        
        wp_add_dashboard_widget(
            'bcn_content_pipeline',
            'Content Pipeline',
            array($this, 'content_pipeline_widget')
        );
        
        wp_add_dashboard_widget(
            'bcn_quick_actions',
            'Quick Actions',
            array($this, 'quick_actions_widget')
        );
        
        wp_add_dashboard_widget(
            'bcn_system_status',
            'System Status',
            array($this, 'system_status_widget')
        );
    }
    
    public function custom_admin_styles() {
        ?>
        <style>
        /* Hide WordPress branding */
        #wpadminbar { display: none !important; }
        .wp-admin #wpcontent { margin-left: 0; }
        
        /* Custom admin header */
        .bcn-admin-header {
            background: var(--bcn-admin-primary, #2c3e50);
            color: white;
            padding: 1rem 2rem;
            margin: -20px -20px 20px -20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .bcn-admin-header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .bcn-admin-header .bcn-user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* Custom admin sidebar */
        .bcn-admin-sidebar {
            background: var(--bcn-admin-light, #ecf0f1);
            border-right: 1px solid #ddd;
            min-height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .bcn-admin-content {
            margin-left: 250px;
            padding: 2rem;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .bcn-admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .bcn-admin-sidebar.open {
                transform: translateX(0);
            }
            
            .bcn-admin-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
        </style>
        <?php
    }
    
    public function custom_admin_scripts() {
        ?>
        <script>
        // Custom admin functionality
        class BCNAdmin {
            constructor() {
                this.init();
            }
            
            init() {
                this.setupDashboard();
                this.setupForms();
                this.setupAutomation();
                this.setupNotifications();
            }
            
            setupDashboard() {
                // Custom dashboard widgets
                this.createMemberOverview();
                this.createEventCalendar();
                this.createContentPipeline();
            }
            
            setupForms() {
                // Enhanced form functionality
                this.setupAutoSave();
                this.setupValidation();
                this.setupBulkActions();
            }
            
            setupAutomation() {
                // Automation workflows
                this.setupContentAutomation();
                this.setupMemberAutomation();
                this.setupEventAutomation();
            }
            
            setupNotifications() {
                // Notification system
                this.setupToastNotifications();
                this.setupProgressBars();
            }
        }
        
        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new BCNAdmin();
        });
        </script>
        <?php
    }
    
    public function custom_admin_footer() {
        return 'Powered by <a href="https://buffalocannabisnetwork.com">Buffalo Cannabis Network</a>';
    }
    
    public function remove_admin_bar_items($wp_admin_bar) {
        // Remove WordPress logo
        $wp_admin_bar->remove_node('wp-logo');
        
        // Remove comments
        $wp_admin_bar->remove_node('comments');
        
        // Remove updates
        $wp_admin_bar->remove_node('updates');
    }
    
    public function add_custom_roles() {
        // Event Manager role
        add_role('event_manager', 'Event Manager', array(
            'read' => true,
            'edit_posts' => true,
            'edit_pages' => true,
            'edit_others_posts' => true,
            'publish_posts' => true,
            'manage_events' => true,
            'manage_members' => true,
            'view_analytics' => true
        ));
        
        // Content Creator role
        add_role('content_creator', 'Content Creator', array(
            'read' => true,
            'edit_posts' => true,
            'edit_pages' => true,
            'publish_posts' => true,
            'upload_files' => true
        ));
    }
    
    public function add_custom_capabilities() {
        $roles = array('administrator', 'editor', 'event_manager');
        
        foreach($roles as $role) {
            $role_obj = get_role($role);
            if ($role_obj) {
                $role_obj->add_cap('manage_events');
                $role_obj->add_cap('manage_members');
                $role_obj->add_cap('view_analytics');
            }
        }
    }
    
    // Custom page methods
    public function custom_dashboard_page() {
        include get_template_directory() . '/template-parts/admin/dashboard.php';
    }
    
    public function events_page() {
        include get_template_directory() . '/template-parts/admin/events.php';
    }
    
    public function members_page() {
        include get_template_directory() . '/template-parts/admin/members.php';
    }
    
    public function content_page() {
        include get_template_directory() . '/template-parts/admin/content.php';
    }
    
    public function analytics_page() {
        include get_template_directory() . '/template-parts/admin/analytics.php';
    }
    
    public function settings_page() {
        include get_template_directory() . '/template-parts/admin/settings.php';
    }
    
    // Widget methods
    public function member_overview_widget() {
        $total_members = wp_count_users()['total_users'];
        $new_members = $this->get_new_members_this_month();
        $active_members = $this->get_active_members();
        
        echo "<div class='bcn-analytics-widget'>";
        echo "<div class='stats-grid'>";
        echo "<div class='stat'><span class='number'>{$total_members}</span><span class='label'>Total Members</span></div>";
        echo "<div class='stat'><span class='number'>{$new_members}</span><span class='label'>New This Month</span></div>";
        echo "<div class='stat'><span class='number'>{$active_members}</span><span class='label'>Active Members</span></div>";
        echo "</div></div>";
    }
    
    public function event_calendar_widget() {
        $upcoming_events = $this->get_upcoming_events(5);
        
        echo "<div class='bcn-event-calendar'>";
        echo "<h3>Upcoming Events</h3>";
        foreach($upcoming_events as $event) {
            echo "<div class='event-item'>";
            echo "<strong>{$event->post_title}</strong>";
            echo "<span class='event-date'>" . get_the_date('M j, Y', $event) . "</span>";
            echo "</div>";
        }
        echo "</div>";
    }
    
    public function content_pipeline_widget() {
        $drafts = get_posts(array('post_status' => 'draft', 'numberposts' => 5));
        $scheduled = get_posts(array('post_status' => 'future', 'numberposts' => 5));
        
        echo "<div class='bcn-content-pipeline'>";
        echo "<h3>Content Pipeline</h3>";
        echo "<div class='pipeline-section'>";
        echo "<h4>Drafts (" . count($drafts) . ")</h4>";
        foreach($drafts as $draft) {
            echo "<div class='pipeline-item'><a href='" . get_edit_post_link($draft->ID) . "'>{$draft->post_title}</a></div>";
        }
        echo "</div></div>";
    }
    
    public function quick_actions_widget() {
        echo "<div class='bcn-quick-actions'>";
        echo "<h3>Quick Actions</h3>";
        echo "<div class='action-buttons'>";
        echo "<a href='" . admin_url('post-new.php?post_type=bcn_event') . "' class='button button-primary'>New Event</a>";
        echo "<a href='" . admin_url('post-new.php') . "' class='button'>New Post</a>";
        echo "<a href='" . admin_url('user-new.php') . "' class='button'>Add Member</a>";
        echo "</div></div>";
    }
    
    public function system_status_widget() {
        $memory_usage = memory_get_usage(true);
        $memory_limit = ini_get('memory_limit');
        $php_version = PHP_VERSION;
        $wp_version = get_bloginfo('version');
        
        echo "<div class='bcn-system-status'>";
        echo "<h3>System Status</h3>";
        echo "<div class='status-item'><span class='label'>PHP Version:</span> <span class='value'>{$php_version}</span></div>";
        echo "<div class='status-item'><span class='label'>WordPress:</span> <span class='value'>{$wp_version}</span></div>";
        echo "<div class='status-item'><span class='label'>Memory:</span> <span class='value'>" . size_format($memory_usage) . " / {$memory_limit}</span></div>";
        echo "</div>";
    }
    
    // Helper methods
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
    
    private function get_upcoming_events($limit = 5) {
        return get_posts(array(
            'post_type' => 'bcn_event',
            'post_status' => 'publish',
            'numberposts' => $limit,
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>='
                )
            ),
            'orderby' => 'meta_value',
            'order' => 'ASC'
        ));
    }
}

// Initialize the admin theme
new BCN_Admin_Theme();

<?php
/**
 * Member Dashboard System with Achievement Badges
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_Member_Dashboard_System {
    
    public function __construct() {
        add_action('init', array($this, 'register_dashboard_fields'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_dashboard_scripts'));
        add_action('wp_ajax_get_member_dashboard_data', array($this, 'get_member_dashboard_data'));
        add_action('wp_ajax_update_member_profile', array($this, 'update_member_profile'));
        add_shortcode('bcn_member_dashboard', array($this, 'member_dashboard_shortcode'));
        add_shortcode('bcn_member_achievements', array($this, 'member_achievements_shortcode'));
        add_action('init', array($this, 'define_achievement_system'));
    }
    
    /**
     * Register dashboard-specific ACF fields
     */
    public function register_dashboard_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_bcn_member_dashboard',
                'title' => 'Member Dashboard Data',
                'fields' => array(
                    array(
                        'key' => 'field_member_points',
                        'label' => 'Member Points',
                        'name' => 'member_points',
                        'type' => 'number',
                        'default_value' => 0,
                        'readonly' => 1,
                        'instructions' => 'Points earned through various activities',
                    ),
                    array(
                        'key' => 'field_member_level',
                        'label' => 'Member Level',
                        'name' => 'member_level',
                        'type' => 'select',
                        'choices' => array(
                            'bronze' => 'Bronze',
                            'silver' => 'Silver',
                            'gold' => 'Gold',
                            'platinum' => 'Platinum',
                            'diamond' => 'Diamond',
                        ),
                        'default_value' => 'bronze',
                    ),
                    array(
                        'key' => 'field_member_achievements',
                        'label' => 'Achievements',
                        'name' => 'member_achievements',
                        'type' => 'checkbox',
                        'choices' => array(
                            'first_testimonial' => 'First Testimonial',
                            'testimonial_contributor' => 'Testimonial Contributor',
                            'testimonial_champion' => 'Testimonial Champion',
                            'testimonial_expert' => 'Testimonial Expert',
                            'first_blog_post' => 'First Blog Post',
                            'blog_contributor' => 'Blog Contributor',
                            'blog_writer' => 'Blog Writer',
                            'blog_author' => 'Blog Author',
                            'blog_expert' => 'Blog Expert',
                            'event_attendee' => 'Event Attendee',
                            'event_regular' => 'Event Regular',
                            'event_champion' => 'Event Champion',
                            'networking_pro' => 'Networking Pro',
                            'community_builder' => 'Community Builder',
                            'industry_leader' => 'Industry Leader',
                            'premier_member' => 'Premier Member',
                            'pro_member' => 'Pro Member',
                            'early_adopter' => 'Early Adopter',
                            'social_media_advocate' => 'Social Media Advocate',
                            'content_creator' => 'Content Creator',
                        ),
                        'layout' => 'horizontal',
                        'toggle' => 0,
                        'return_format' => 'value',
                    ),
                    array(
                        'key' => 'field_member_stats',
                        'label' => 'Member Statistics',
                        'name' => 'member_stats',
                        'type' => 'group',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_testimonials_submitted',
                                'label' => 'Testimonials Submitted',
                                'name' => 'testimonials_submitted',
                                'type' => 'number',
                                'default_value' => 0,
                            ),
                            array(
                                'key' => 'field_blog_posts_published',
                                'label' => 'Blog Posts Published',
                                'name' => 'blog_posts_published',
                                'type' => 'number',
                                'default_value' => 0,
                            ),
                            array(
                                'key' => 'field_events_attended',
                                'label' => 'Events Attended',
                                'name' => 'events_attended',
                                'type' => 'number',
                                'default_value' => 0,
                            ),
                            array(
                                'key' => 'field_connections_made',
                                'label' => 'Connections Made',
                                'name' => 'connections_made',
                                'type' => 'number',
                                'default_value' => 0,
                            ),
                            array(
                                'key' => 'field_days_member',
                                'label' => 'Days as Member',
                                'name' => 'days_member',
                                'type' => 'number',
                                'default_value' => 0,
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_member_preferences',
                        'label' => 'Member Preferences',
                        'name' => 'member_preferences',
                        'type' => 'group',
                        'sub_fields' => array(
                            array(
                                'key' => 'field_email_notifications',
                                'label' => 'Email Notifications',
                                'name' => 'email_notifications',
                                'type' => 'true_false',
                                'default_value' => 1,
                            ),
                            array(
                                'key' => 'field_event_reminders',
                                'label' => 'Event Reminders',
                                'name' => 'event_reminders',
                                'type' => 'true_false',
                                'default_value' => 1,
                            ),
                            array(
                                'key' => 'field_newsletter_subscription',
                                'label' => 'Newsletter Subscription',
                                'name' => 'newsletter_subscription',
                                'type' => 'true_false',
                                'default_value' => 1,
                            ),
                            array(
                                'key' => 'field_profile_visibility',
                                'label' => 'Profile Visibility',
                                'name' => 'profile_visibility',
                                'type' => 'select',
                                'choices' => array(
                                    'public' => 'Public',
                                    'members_only' => 'Members Only',
                                    'private' => 'Private',
                                ),
                                'default_value' => 'public',
                            ),
                        ),
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
                'menu_order' => 1,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
            ));
        }
    }
    
    /**
     * Define achievement system
     */
    public function define_achievement_system() {
        // This will be called during init to set up achievement definitions
        $this->achievement_definitions = array(
            // Testimonial Achievements
            'first_testimonial' => array(
                'name' => 'First Testimonial',
                'description' => 'Submitted your first testimonial',
                'icon' => '💬',
                'points' => 10,
                'category' => 'testimonial',
                'tier' => 'bronze'
            ),
            'testimonial_contributor' => array(
                'name' => 'Testimonial Contributor',
                'description' => 'Submitted 5 testimonials',
                'icon' => '📝',
                'points' => 25,
                'category' => 'testimonial',
                'tier' => 'silver'
            ),
            'testimonial_champion' => array(
                'name' => 'Testimonial Champion',
                'description' => 'Submitted 10 testimonials',
                'icon' => '🏆',
                'points' => 50,
                'category' => 'testimonial',
                'tier' => 'gold'
            ),
            'testimonial_expert' => array(
                'name' => 'Testimonial Expert',
                'description' => 'Submitted 25 testimonials',
                'icon' => '⭐',
                'points' => 100,
                'category' => 'testimonial',
                'tier' => 'platinum'
            ),
            
            // Blog Achievements
            'first_blog_post' => array(
                'name' => 'First Blog Post',
                'description' => 'Published your first blog post',
                'icon' => '✍️',
                'points' => 50,
                'category' => 'content',
                'tier' => 'bronze'
            ),
            'blog_contributor' => array(
                'name' => 'Blog Contributor',
                'description' => 'Published 3 blog posts',
                'icon' => '📰',
                'points' => 75,
                'category' => 'content',
                'tier' => 'silver'
            ),
            'blog_writer' => array(
                'name' => 'Blog Writer',
                'description' => 'Published 5 blog posts',
                'icon' => '📖',
                'points' => 100,
                'category' => 'content',
                'tier' => 'gold'
            ),
            'blog_author' => array(
                'name' => 'Blog Author',
                'description' => 'Published 10 blog posts',
                'icon' => '📚',
                'points' => 200,
                'category' => 'content',
                'tier' => 'platinum'
            ),
            'blog_expert' => array(
                'name' => 'Blog Expert',
                'description' => 'Published 25 blog posts',
                'icon' => '🎓',
                'points' => 500,
                'category' => 'content',
                'tier' => 'diamond'
            ),
            
            // Event Achievements
            'event_attendee' => array(
                'name' => 'Event Attendee',
                'description' => 'Attended your first event',
                'icon' => '🎟️',
                'points' => 15,
                'category' => 'events',
                'tier' => 'bronze'
            ),
            'event_regular' => array(
                'name' => 'Event Regular',
                'description' => 'Attended 5 events',
                'icon' => '🎪',
                'points' => 50,
                'category' => 'events',
                'tier' => 'silver'
            ),
            'event_champion' => array(
                'name' => 'Event Champion',
                'description' => 'Attended 15 events',
                'icon' => '🏅',
                'points' => 150,
                'category' => 'events',
                'tier' => 'gold'
            ),
            
            // Networking Achievements
            'networking_pro' => array(
                'name' => 'Networking Pro',
                'description' => 'Made 10 connections',
                'icon' => '🤝',
                'points' => 100,
                'category' => 'networking',
                'tier' => 'gold'
            ),
            'community_builder' => array(
                'name' => 'Community Builder',
                'description' => 'Made 25 connections',
                'icon' => '🌐',
                'points' => 250,
                'category' => 'networking',
                'tier' => 'platinum'
            ),
            
            // Special Achievements
            'industry_leader' => array(
                'name' => 'Industry Leader',
                'description' => 'Recognized industry leadership',
                'icon' => '👑',
                'points' => 500,
                'category' => 'special',
                'tier' => 'diamond'
            ),
            'premier_member' => array(
                'name' => 'Premier Member',
                'description' => 'Premier membership tier',
                'icon' => '💎',
                'points' => 0,
                'category' => 'membership',
                'tier' => 'diamond'
            ),
            'pro_member' => array(
                'name' => 'Pro Member',
                'description' => 'Pro membership tier',
                'icon' => '⚡',
                'points' => 0,
                'category' => 'membership',
                'tier' => 'platinum'
            ),
            'early_adopter' => array(
                'name' => 'Early Adopter',
                'description' => 'Joined BCN in the first year',
                'icon' => '🚀',
                'points' => 100,
                'category' => 'special',
                'tier' => 'gold'
            ),
            'social_media_advocate' => array(
                'name' => 'Social Media Advocate',
                'description' => 'Active on social media',
                'icon' => '📱',
                'points' => 75,
                'category' => 'social',
                'tier' => 'silver'
            ),
            'content_creator' => array(
                'name' => 'Content Creator',
                'description' => 'Created various types of content',
                'icon' => '🎨',
                'points' => 150,
                'category' => 'content',
                'tier' => 'gold'
            ),
        );
    }
    
    /**
     * Enqueue dashboard scripts and styles
     */
    public function enqueue_dashboard_scripts() {
        wp_enqueue_script('bcn-member-dashboard', get_template_directory_uri() . '/assets/js/member-dashboard.js', array('jquery', 'chart-js'), '1.0.0', true);
        wp_enqueue_style('bcn-member-dashboard', get_template_directory_uri() . '/assets/css/member-dashboard.css', array(), '1.0.0');
        
        wp_localize_script('bcn-member-dashboard', 'bcnDashboard', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bcn_dashboard_nonce'),
        ));
    }
    
    /**
     * Get member dashboard data
     */
    public function get_member_dashboard_data() {
        check_ajax_referer('bcn_dashboard_nonce', 'nonce');
        
        $member_id = intval($_POST['member_id']);
        
        // Get member data
        $member = get_post($member_id);
        $points = get_post_meta($member_id, 'member_points', true) ?: 0;
        $level = get_post_meta($member_id, 'member_level', true) ?: 'bronze';
        $achievements = get_post_meta($member_id, 'member_achievements', true) ?: array();
        $stats = get_post_meta($member_id, 'member_stats', true) ?: array();
        
        // Get recent activity
        $recent_activity = $this->get_recent_activity($member_id);
        
        // Get upcoming events
        $upcoming_events = $this->get_upcoming_events();
        
        // Get member's testimonials and blog posts
        $testimonials = get_posts(array(
            'post_type' => 'bcn_testimonial',
            'meta_query' => array(
                array(
                    'key' => 'testimonial_member',
                    'value' => $member_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 5,
            'post_status' => array('publish', 'pending')
        ));
        
        $blog_posts = get_posts(array(
            'post_type' => 'bcn_news',
            'meta_query' => array(
                array(
                    'key' => 'blog_submission_member',
                    'value' => $member_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 5,
            'post_status' => array('publish', 'pending')
        ));
        
        // Calculate progress to next level
        $next_level = $this->get_next_level($level);
        $progress = $this->calculate_level_progress($points, $level);
        
        wp_send_json_success(array(
            'member' => array(
                'id' => $member_id,
                'name' => $member->post_title,
                'points' => $points,
                'level' => $level,
                'next_level' => $next_level,
                'progress' => $progress
            ),
            'achievements' => $achievements,
            'stats' => $stats,
            'recent_activity' => $recent_activity,
            'upcoming_events' => $upcoming_events,
            'testimonials' => $testimonials,
            'blog_posts' => $blog_posts
        ));
    }
    
    /**
     * Update member profile
     */
    public function update_member_profile() {
        check_ajax_referer('bcn_dashboard_nonce', 'nonce');
        
        $member_id = intval($_POST['member_id']);
        $preferences = $_POST['preferences'];
        
        // Update preferences
        update_post_meta($member_id, 'member_preferences', $preferences);
        
        wp_send_json_success('Profile updated successfully');
    }
    
    /**
     * Member dashboard shortcode
     */
    public function member_dashboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'member_id' => '',
        ), $atts);
        
        if (empty($atts['member_id'])) {
            return '<p>Error: Member ID required</p>';
        }
        
        ob_start();
        ?>
        <div class="bcn-member-dashboard" data-member-id="<?php echo esc_attr($atts['member_id']); ?>">
            <div class="dashboard-header">
                <div class="member-info">
                    <h2 id="member-name">Loading...</h2>
                    <div class="member-level">
                        <span class="level-badge" id="member-level">Bronze</span>
                        <span class="points-count" id="member-points">0 points</span>
                    </div>
                </div>
                <div class="level-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
                    </div>
                    <div class="progress-text">
                        <span id="current-level">Bronze</span> → <span id="next-level">Silver</span>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-grid">
                <div class="dashboard-card achievements-card">
                    <h3>Recent Achievements</h3>
                    <div class="achievements-list" id="achievements-list">
                        <div class="loading">Loading achievements...</div>
                    </div>
                </div>
                
                <div class="dashboard-card stats-card">
                    <h3>Your Statistics</h3>
                    <div class="stats-grid" id="stats-grid">
                        <div class="stat-item">
                            <span class="stat-number" id="testimonials-count">0</span>
                            <span class="stat-label">Testimonials</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" id="blog-posts-count">0</span>
                            <span class="stat-label">Blog Posts</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" id="events-attended-count">0</span>
                            <span class="stat-label">Events</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" id="connections-count">0</span>
                            <span class="stat-label">Connections</span>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card activity-card">
                    <h3>Recent Activity</h3>
                    <div class="activity-feed" id="activity-feed">
                        <div class="loading">Loading activity...</div>
                    </div>
                </div>
                
                <div class="dashboard-card events-card">
                    <h3>Upcoming Events</h3>
                    <div class="events-list" id="events-list">
                        <div class="loading">Loading events...</div>
                    </div>
                </div>
                
                <div class="dashboard-card content-card">
                    <h3>Your Content</h3>
                    <div class="content-tabs">
                        <button class="tab-button active" data-tab="testimonials">Testimonials</button>
                        <button class="tab-button" data-tab="blog-posts">Blog Posts</button>
                    </div>
                    <div class="content-panel" id="testimonials-panel">
                        <div class="content-list" id="testimonials-list">
                            <div class="loading">Loading testimonials...</div>
                        </div>
                    </div>
                    <div class="content-panel" id="blog-posts-panel" style="display: none;">
                        <div class="content-list" id="blog-posts-list">
                            <div class="loading">Loading blog posts...</div>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card settings-card">
                    <h3>Settings</h3>
                    <form id="member-preferences-form">
                        <div class="preference-item">
                            <label>
                                <input type="checkbox" name="email_notifications" value="1" checked>
                                Email Notifications
                            </label>
                        </div>
                        <div class="preference-item">
                            <label>
                                <input type="checkbox" name="event_reminders" value="1" checked>
                                Event Reminders
                            </label>
                        </div>
                        <div class="preference-item">
                            <label>
                                <input type="checkbox" name="newsletter_subscription" value="1" checked>
                                Newsletter Subscription
                            </label>
                        </div>
                        <div class="preference-item">
                            <label>Profile Visibility:</label>
                            <select name="profile_visibility">
                                <option value="public">Public</option>
                                <option value="members_only">Members Only</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                        <button type="submit" class="button button-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Member achievements shortcode
     */
    public function member_achievements_shortcode($atts) {
        $atts = shortcode_atts(array(
            'member_id' => '',
            'category' => '',
            'tier' => '',
        ), $atts);
        
        if (empty($atts['member_id'])) {
            return '<p>Error: Member ID required</p>';
        }
        
        $achievements = get_post_meta($atts['member_id'], 'member_achievements', true) ?: array();
        
        ob_start();
        ?>
        <div class="bcn-member-achievements" data-member-id="<?php echo esc_attr($atts['member_id']); ?>">
            <h3>Your Achievements</h3>
            <div class="achievements-grid">
                <?php foreach ($this->achievement_definitions as $slug => $achievement) : 
                    $earned = in_array($slug, $achievements);
                    $filtered = false;
                    
                    // Apply filters
                    if (!empty($atts['category']) && $achievement['category'] !== $atts['category']) {
                        $filtered = true;
                    }
                    if (!empty($atts['tier']) && $achievement['tier'] !== $atts['tier']) {
                        $filtered = true;
                    }
                    
                    if (!$filtered) :
                ?>
                <div class="achievement-item achievement-item--<?php echo esc_attr($achievement['tier']); ?> <?php echo $earned ? 'achievement-earned' : 'achievement-locked'; ?>">
                    <div class="achievement-icon"><?php echo $achievement['icon']; ?></div>
                    <div class="achievement-info">
                        <h4><?php echo esc_html($achievement['name']); ?></h4>
                        <p><?php echo esc_html($achievement['description']); ?></p>
                        <div class="achievement-points"><?php echo $achievement['points']; ?> points</div>
                    </div>
                    <?php if ($earned) : ?>
                        <div class="achievement-badge">✓</div>
                    <?php endif; ?>
                </div>
                <?php endif; endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get recent activity for member
     */
    private function get_recent_activity($member_id) {
        $activity = array();
        
        // Get recent testimonials
        $testimonials = get_posts(array(
            'post_type' => 'bcn_testimonial',
            'meta_query' => array(
                array(
                    'key' => 'testimonial_member',
                    'value' => $member_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 3,
            'post_status' => array('publish', 'pending')
        ));
        
        foreach ($testimonials as $testimonial) {
            $activity[] = array(
                'type' => 'testimonial',
                'title' => 'Submitted testimonial',
                'date' => $testimonial->post_date,
                'status' => get_field('testimonial_approval_status', $testimonial->ID)
            );
        }
        
        // Get recent blog posts
        $blog_posts = get_posts(array(
            'post_type' => 'bcn_news',
            'meta_query' => array(
                array(
                    'key' => 'blog_submission_member',
                    'value' => $member_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => 3,
            'post_status' => array('publish', 'pending')
        ));
        
        foreach ($blog_posts as $post) {
            $activity[] = array(
                'type' => 'blog',
                'title' => 'Published: ' . $post->post_title,
                'date' => $post->post_date,
                'status' => get_field('blog_submission_status', $post->ID)
            );
        }
        
        // Sort by date
        usort($activity, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return array_slice($activity, 0, 10);
    }
    
    /**
     * Get upcoming events
     */
    private function get_upcoming_events() {
        return get_posts(array(
            'post_type' => 'bcn_event',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'value' => date('Y-m-d H:i:s'),
                    'compare' => '>='
                )
            ),
            'posts_per_page' => 5,
            'post_status' => 'publish',
            'meta_key' => 'event_date',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        ));
    }
    
    /**
     * Get next level
     */
    private function get_next_level($current_level) {
        $levels = array('bronze', 'silver', 'gold', 'platinum', 'diamond');
        $current_index = array_search($current_level, $levels);
        
        if ($current_index !== false && $current_index < count($levels) - 1) {
            return $levels[$current_index + 1];
        }
        
        return $current_level; // Already at max level
    }
    
    /**
     * Calculate level progress
     */
    private function calculate_level_progress($points, $level) {
        $level_thresholds = array(
            'bronze' => 0,
            'silver' => 100,
            'gold' => 500,
            'platinum' => 1000,
            'diamond' => 2500
        );
        
        $current_threshold = $level_thresholds[$level];
        $next_threshold = $this->get_next_level_threshold($level);
        
        if ($next_threshold === $current_threshold) {
            return 100; // Max level reached
        }
        
        $progress_points = $points - $current_threshold;
        $threshold_range = $next_threshold - $current_threshold;
        
        return min(100, max(0, ($progress_points / $threshold_range) * 100));
    }
    
    /**
     * Get next level threshold
     */
    private function get_next_level_threshold($level) {
        $level_thresholds = array(
            'bronze' => 100,
            'silver' => 500,
            'gold' => 1000,
            'platinum' => 2500,
            'diamond' => 2500
        );
        
        $next_level = $this->get_next_level($level);
        return $level_thresholds[$next_level];
    }
}

// Initialize the member dashboard system
new BCN_Member_Dashboard_System();

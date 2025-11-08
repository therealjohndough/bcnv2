<?php
/**
 * Submission Workflows and Approval System
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_Submission_Workflows {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_submission_admin_menu'));
        add_action('wp_ajax_approve_submission', array($this, 'handle_approval_action'));
        add_action('wp_ajax_reject_submission', array($this, 'handle_rejection_action'));
        add_action('wp_ajax_feature_submission', array($this, 'handle_feature_action'));
        add_action('add_meta_boxes', array($this, 'add_submission_meta_boxes'));
        add_action('save_post', array($this, 'save_submission_notes'));
        add_filter('manage_bcn_testimonial_posts_columns', array($this, 'add_testimonial_columns'));
        add_action('manage_bcn_testimonial_posts_custom_column', array($this, 'populate_testimonial_columns'), 10, 2);
        add_filter('manage_bcn_news_posts_columns', array($this, 'add_blog_columns'));
        add_action('manage_bcn_news_posts_custom_column', array($this, 'populate_blog_columns'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('init', array($this, 'create_submission_status_taxonomy'));
    }
    
    /**
     * Add submission management admin menu
     */
    public function add_submission_admin_menu() {
        add_menu_page(
            'Submission Management',
            'Submissions',
            'manage_options',
            'bcn-submissions',
            array($this, 'submission_management_page'),
            'dashicons-admin-page',
            30
        );
        
        add_submenu_page(
            'bcn-submissions',
            'Pending Testimonials',
            'Pending Testimonials',
            'manage_options',
            'bcn-pending-testimonials',
            array($this, 'pending_testimonials_page')
        );
        
        add_submenu_page(
            'bcn-submissions',
            'Pending Blog Posts',
            'Pending Blog Posts',
            'manage_options',
            'bcn-pending-blogs',
            array($this, 'pending_blogs_page')
        );
        
        add_submenu_page(
            'bcn-submissions',
            'Approval Settings',
            'Settings',
            'manage_options',
            'bcn-submission-settings',
            array($this, 'submission_settings_page')
        );
    }
    
    /**
     * Main submission management page
     */
    public function submission_management_page() {
        // Get pending counts
        $pending_testimonials = get_posts(array(
            'post_type' => 'bcn_testimonial',
            'meta_query' => array(
                array(
                    'key' => 'testimonial_approval_status',
                    'value' => 'pending',
                    'compare' => '='
                )
            ),
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));
        
        $pending_blogs = get_posts(array(
            'post_type' => 'bcn_news',
            'meta_query' => array(
                array(
                    'key' => 'blog_submission_status',
                    'value' => 'submitted',
                    'compare' => '='
                )
            ),
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));
        
        $total_pending = count($pending_testimonials) + count($pending_blogs);
        ?>
        <div class="wrap">
            <h1>Submission Management</h1>
            
            <div class="submission-overview">
                <div class="overview-card">
                    <h2><?php echo count($pending_testimonials); ?></h2>
                    <p>Pending Testimonials</p>
                    <a href="<?php echo admin_url('admin.php?page=bcn-pending-testimonials'); ?>" class="button button-primary">Review</a>
                </div>
                
                <div class="overview-card">
                    <h2><?php echo count($pending_blogs); ?></h2>
                    <p>Pending Blog Posts</p>
                    <a href="<?php echo admin_url('admin.php?page=bcn-pending-blogs'); ?>" class="button button-primary">Review</a>
                </div>
                
                <div class="overview-card">
                    <h2><?php echo $total_pending; ?></h2>
                    <p>Total Pending</p>
                </div>
            </div>
            
            <div class="submission-stats">
                <h2>Recent Activity</h2>
                <div class="activity-feed">
                    <?php $this->display_recent_submissions(); ?>
                </div>
            </div>
        </div>
        
        <style>
        .submission-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .overview-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .overview-card h2 {
            font-size: 2rem;
            margin: 0 0 10px 0;
            color: #3498db;
        }
        
        .overview-card p {
            margin: 0 0 15px 0;
            color: #666;
        }
        </style>
        <?php
    }
    
    /**
     * Pending testimonials page
     */
    public function pending_testimonials_page() {
        $testimonials = get_posts(array(
            'post_type' => 'bcn_testimonial',
            'meta_query' => array(
                array(
                    'key' => 'testimonial_approval_status',
                    'value' => 'pending',
                    'compare' => '='
                )
            ),
            'posts_per_page' => 20,
            'orderby' => 'date',
            'order' => 'ASC'
        ));
        ?>
        <div class="wrap">
            <h1>Pending Testimonials</h1>
            
            <?php if (empty($testimonials)) : ?>
                <div class="notice notice-success">
                    <p>No pending testimonials at this time.</p>
                </div>
            <?php else : ?>
                <div class="pending-testimonials">
                    <?php foreach ($testimonials as $testimonial) : ?>
                        <?php $this->render_testimonial_review_card($testimonial); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Pending blog posts page
     */
    public function pending_blogs_page() {
        $blogs = get_posts(array(
            'post_type' => 'bcn_news',
            'meta_query' => array(
                array(
                    'key' => 'blog_submission_status',
                    'value' => 'submitted',
                    'compare' => '='
                )
            ),
            'posts_per_page' => 20,
            'orderby' => 'date',
            'order' => 'ASC'
        ));
        ?>
        <div class="wrap">
            <h1>Pending Blog Posts</h1>
            
            <?php if (empty($blogs)) : ?>
                <div class="notice notice-success">
                    <p>No pending blog posts at this time.</p>
                </div>
            <?php else : ?>
                <div class="pending-blogs">
                    <?php foreach ($blogs as $blog) : ?>
                        <?php $this->render_blog_review_card($blog); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Render testimonial review card
     */
    private function render_testimonial_review_card($testimonial) {
        $member = get_field('testimonial_member', $testimonial->ID);
        $rating = get_field('testimonial_rating', $testimonial->ID);
        $type = get_field('testimonial_type', $testimonial->ID);
        ?>
        <div class="submission-card testimonial-card" data-id="<?php echo $testimonial->ID; ?>">
            <div class="submission-header">
                <h3><?php echo esc_html($testimonial->post_title); ?></h3>
                <div class="submission-meta">
                    <span class="submission-date"><?php echo get_the_date('M j, Y', $testimonial->ID); ?></span>
                    <span class="submission-type"><?php echo ucfirst($type); ?> Testimonial</span>
                    <?php if ($rating) : ?>
                        <span class="submission-rating">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <span class="star <?php echo $i <= $rating ? 'filled' : ''; ?>">★</span>
                            <?php endfor; ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="submission-content">
                <div class="testimonial-text">
                    <?php echo wp_kses_post($testimonial->post_content); ?>
                </div>
                
                <?php if ($member) : ?>
                    <div class="submission-author">
                        <strong>From:</strong> <?php echo esc_html($member->post_title); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="submission-actions">
                <button class="button button-primary approve-btn" data-id="<?php echo $testimonial->ID; ?>" data-type="testimonial">
                    Approve
                </button>
                <button class="button button-secondary feature-btn" data-id="<?php echo $testimonial->ID; ?>" data-type="testimonial">
                    Feature
                </button>
                <button class="button button-link-delete reject-btn" data-id="<?php echo $testimonial->ID; ?>" data-type="testimonial">
                    Reject
                </button>
                <a href="<?php echo get_edit_post_link($testimonial->ID); ?>" class="button">
                    Edit
                </a>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render blog review card
     */
    private function render_blog_review_card($blog) {
        $member = get_field('blog_submission_member', $blog->ID);
        $word_count = get_field('blog_submission_word_count', $blog->ID);
        $type = get_field('blog_submission_type', $blog->ID);
        ?>
        <div class="submission-card blog-card" data-id="<?php echo $blog->ID; ?>">
            <div class="submission-header">
                <h3><?php echo esc_html($blog->post_title); ?></h3>
                <div class="submission-meta">
                    <span class="submission-date"><?php echo get_the_date('M j, Y', $blog->ID); ?></span>
                    <span class="submission-type"><?php echo ucfirst(str_replace('_', ' ', $type)); ?></span>
                    <?php if ($word_count) : ?>
                        <span class="word-count"><?php echo $word_count; ?> words</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="submission-content">
                <div class="blog-excerpt">
                    <?php echo wp_kses_post($blog->post_excerpt ?: wp_trim_words($blog->post_content, 50)); ?>
                </div>
                
                <?php if ($member) : ?>
                    <div class="submission-author">
                        <strong>By:</strong> <?php echo esc_html($member->post_title); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="submission-actions">
                <button class="button button-primary approve-btn" data-id="<?php echo $blog->ID; ?>" data-type="blog">
                    Approve & Publish
                </button>
                <button class="button button-secondary" onclick="window.open('<?php echo get_edit_post_link($blog->ID); ?>', '_blank')">
                    Edit
                </button>
                <button class="button button-link-delete reject-btn" data-id="<?php echo $blog->ID; ?>" data-type="blog">
                    Reject
                </button>
            </div>
        </div>
        <?php
    }
    
    /**
     * Handle approval action
     */
    public function handle_approval_action() {
        check_ajax_referer('bcn_submission_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $post_id = intval($_POST['post_id']);
        $type = sanitize_text_field($_POST['type']);
        
        if ($type === 'testimonial') {
            update_field('testimonial_approval_status', 'approved', $post_id);
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'publish'
            ));
            
            // Award points for approval
            $member = get_field('testimonial_member', $post_id);
            if ($member) {
                $this->award_approval_points($member->ID, 'testimonial');
            }
        } elseif ($type === 'blog') {
            update_field('blog_submission_status', 'approved', $post_id);
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'publish'
            ));
            
            // Award points for approval
            $member = get_field('blog_submission_member', $post_id);
            if ($member) {
                $this->award_approval_points($member->ID, 'blog');
            }
        }
        
        // Send notification email
        $this->send_approval_notification($post_id, $type);
        
        wp_send_json_success('Submission approved successfully');
    }
    
    /**
     * Handle rejection action
     */
    public function handle_rejection_action() {
        check_ajax_referer('bcn_submission_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $post_id = intval($_POST['post_id']);
        $type = sanitize_text_field($_POST['type']);
        $reason = sanitize_textarea_field($_POST['reason']);
        
        if ($type === 'testimonial') {
            update_field('testimonial_approval_status', 'rejected', $post_id);
        } elseif ($type === 'blog') {
            update_field('blog_submission_status', 'rejected', $post_id);
        }
        
        // Save rejection reason
        update_post_meta($post_id, 'submission_rejection_reason', $reason);
        
        // Send rejection notification
        $this->send_rejection_notification($post_id, $type, $reason);
        
        wp_send_json_success('Submission rejected');
    }
    
    /**
     * Handle feature action
     */
    public function handle_feature_action() {
        check_ajax_referer('bcn_submission_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $post_id = intval($_POST['post_id']);
        $type = sanitize_text_field($_POST['type']);
        
        if ($type === 'testimonial') {
            update_field('testimonial_approval_status', 'featured', $post_id);
            update_field('testimonial_featured', true, $post_id);
            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'publish'
            ));
            
            // Award bonus points for featuring
            $member = get_field('testimonial_member', $post_id);
            if ($member) {
                $this->award_feature_points($member->ID, 'testimonial');
            }
        }
        
        wp_send_json_success('Submission featured successfully');
    }
    
    /**
     * Add meta boxes for submission review
     */
    public function add_submission_meta_boxes() {
        add_meta_box(
            'bcn-submission-review',
            'Submission Review',
            array($this, 'submission_review_meta_box'),
            array('bcn_testimonial', 'bcn_news'),
            'side',
            'high'
        );
    }
    
    /**
     * Submission review meta box
     */
    public function submission_review_meta_box($post) {
        $status = '';
        $type = '';
        
        if ($post->post_type === 'bcn_testimonial') {
            $status = get_field('testimonial_approval_status', $post->ID);
            $type = 'testimonial';
        } elseif ($post->post_type === 'bcn_news') {
            $status = get_field('blog_submission_status', $post->ID);
            $type = 'blog';
        }
        
        wp_nonce_field('bcn_submission_review', 'bcn_submission_review_nonce');
        ?>
        <div class="submission-review-panel">
            <p><strong>Status:</strong> 
                <span class="status-badge status-<?php echo esc_attr($status); ?>">
                    <?php echo ucfirst($status); ?>
                </span>
            </p>
            
            <div class="review-actions">
                <button type="button" class="button button-primary approve-submission" 
                        data-id="<?php echo $post->ID; ?>" data-type="<?php echo $type; ?>">
                    Approve
                </button>
                
                <?php if ($type === 'testimonial') : ?>
                    <button type="button" class="button button-secondary feature-submission" 
                            data-id="<?php echo $post->ID; ?>" data-type="<?php echo $type; ?>">
                        Feature
                    </button>
                <?php endif; ?>
                
                <button type="button" class="button button-link-delete reject-submission" 
                        data-id="<?php echo $post->ID; ?>" data-type="<?php echo $type; ?>">
                    Reject
                </button>
            </div>
            
            <div class="reviewer-notes">
                <label for="reviewer_notes"><strong>Reviewer Notes:</strong></label>
                <textarea name="reviewer_notes" id="reviewer_notes" rows="3" 
                          placeholder="Add notes for the submitter..."><?php echo esc_textarea(get_post_meta($post->ID, 'reviewer_notes', true)); ?></textarea>
            </div>
        </div>
        
        <style>
        .submission-review-panel {
            margin: 10px 0;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-featured {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }
        
        .review-actions {
            margin: 15px 0;
        }
        
        .review-actions button {
            margin-right: 10px;
            margin-bottom: 10px;
        }
        
        .reviewer-notes textarea {
            width: 100%;
            margin-top: 5px;
        }
        </style>
        <?php
    }
    
    /**
     * Save submission notes
     */
    public function save_submission_notes($post_id) {
        if (!isset($_POST['bcn_submission_review_nonce']) || 
            !wp_verify_nonce($_POST['bcn_submission_review_nonce'], 'bcn_submission_review')) {
            return;
        }
        
        if (isset($_POST['reviewer_notes'])) {
            update_post_meta($post_id, 'reviewer_notes', sanitize_textarea_field($_POST['reviewer_notes']));
        }
    }
    
    /**
     * Award points for approval
     */
    private function award_approval_points($member_id, $type) {
        $points = $type === 'testimonial' ? 25 : 75; // Blog posts worth more points
        
        $current_points = get_post_meta($member_id, 'member_points', true) ?: 0;
        update_post_meta($member_id, 'member_points', $current_points + $points);
    }
    
    /**
     * Award bonus points for featuring
     */
    private function award_feature_points($member_id, $type) {
        $points = 50; // Bonus for being featured
        
        $current_points = get_post_meta($member_id, 'member_points', true) ?: 0;
        update_post_meta($member_id, 'member_points', $current_points + $points);
    }
    
    /**
     * Send approval notification
     */
    private function send_approval_notification($post_id, $type) {
        $member = null;
        $email = '';
        
        if ($type === 'testimonial') {
            $member_obj = get_field('testimonial_member', $post_id);
            $email = get_field('bcn_member_email', $member_obj->ID);
        } elseif ($type === 'blog') {
            $member_obj = get_field('blog_submission_member', $post_id);
            $email = get_field('blog_submission_contact_email', $post_id);
        }
        
        if ($email) {
            $subject = 'Your ' . ucfirst($type) . ' Has Been Approved';
            $message = "Great news! Your {$type} submission has been approved and published.\n\n";
            $message .= "View it here: " . get_permalink($post_id);
            
            wp_mail($email, $subject, $message);
        }
    }
    
    /**
     * Send rejection notification
     */
    private function send_rejection_notification($post_id, $type, $reason) {
        $member = null;
        $email = '';
        
        if ($type === 'testimonial') {
            $member_obj = get_field('testimonial_member', $post_id);
            $email = get_field('bcn_member_email', $member_obj->ID);
        } elseif ($type === 'blog') {
            $member_obj = get_field('blog_submission_member', $post_id);
            $email = get_field('blog_submission_contact_email', $post_id);
        }
        
        if ($email) {
            $subject = 'Your ' . ucfirst($type) . ' Submission';
            $message = "Thank you for your {$type} submission. Unfortunately, we cannot publish it at this time.\n\n";
            
            if ($reason) {
                $message .= "Reason: {$reason}\n\n";
            }
            
            $message .= "Please feel free to submit again in the future.";
            
            wp_mail($email, $subject, $message);
        }
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'bcn-submissions') !== false || strpos($hook, 'bcn_testimonial') !== false || strpos($hook, 'bcn_news') !== false) {
            wp_enqueue_script('bcn-submission-admin', get_template_directory_uri() . '/assets/js/submission-admin.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('bcn-submission-admin', get_template_directory_uri() . '/assets/css/submission-admin.css', array(), '1.0.0');
            
            wp_localize_script('bcn-submission-admin', 'bcnSubmissionAdmin', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('bcn_submission_nonce'),
            ));
        }
    }
    
    /**
     * Display recent submissions
     */
    private function display_recent_submissions() {
        $recent_submissions = get_posts(array(
            'post_type' => array('bcn_testimonial', 'bcn_news'),
            'posts_per_page' => 10,
            'orderby' => 'date',
            'order' => 'DESC'
        ));
        
        foreach ($recent_submissions as $submission) {
            $type = $submission->post_type === 'bcn_testimonial' ? 'testimonial' : 'blog';
            $status = '';
            
            if ($type === 'testimonial') {
                $status = get_field('testimonial_approval_status', $submission->ID);
            } else {
                $status = get_field('blog_submission_status', $submission->ID);
            }
            
            echo '<div class="activity-item">';
            echo '<span class="activity-type">' . ucfirst($type) . '</span>';
            echo '<span class="activity-title">' . esc_html($submission->post_title) . '</span>';
            echo '<span class="activity-status status-' . esc_attr($status) . '">' . ucfirst($status) . '</span>';
            echo '<span class="activity-date">' . get_the_date('M j', $submission->ID) . '</span>';
            echo '</div>';
        }
    }
}

// Initialize the submission workflows system
new BCN_Submission_Workflows();

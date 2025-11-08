<?php
/**
 * Enhanced Testimonial System for BCN Theme
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_Enhanced_Testimonial_System {
    
    public function __construct() {
        add_action('init', array($this, 'register_testimonial_fields'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_testimonial_scripts'));
        add_action('wp_ajax_submit_testimonial', array($this, 'handle_testimonial_submission'));
        add_action('wp_ajax_nopriv_submit_testimonial', array($this, 'handle_testimonial_submission'));
        add_action('wp_ajax_get_member_testimonials', array($this, 'get_member_testimonials'));
        add_shortcode('bcn_testimonial_form', array($this, 'testimonial_form_shortcode'));
        add_shortcode('bcn_testimonial_display', array($this, 'testimonial_display_shortcode'));
        add_shortcode('bcn_member_testimonial_dashboard', array($this, 'member_testimonial_dashboard_shortcode'));
    }
    
    /**
     * Register enhanced ACF fields for testimonials
     */
    public function register_testimonial_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_bcn_enhanced_testimonial',
                'title' => 'Enhanced Testimonial Details',
                'fields' => array(
                    array(
                        'key' => 'field_testimonial_type',
                        'label' => 'Testimonial Type',
                        'name' => 'testimonial_type',
                        'type' => 'select',
                        'required' => 1,
                        'choices' => array(
                            'written' => 'Written Testimonial',
                            'video' => 'Video Testimonial',
                            'photo' => 'Photo Testimonial',
                            'audio' => 'Audio Testimonial',
                        ),
                        'default_value' => 'written',
                        'wrapper' => array(
                            'width' => '25',
                        ),
                    ),
                    array(
                        'key' => 'field_testimonial_source',
                        'label' => 'Submission Source',
                        'name' => 'testimonial_source',
                        'type' => 'select',
                        'choices' => array(
                            'website' => 'Website Form',
                            'email' => 'Email Submission',
                            'event' => 'Event Follow-up',
                            'social_media' => 'Social Media',
                            'member_portal' => 'Member Portal',
                        ),
                        'default_value' => 'member_portal',
                        'wrapper' => array(
                            'width' => '25',
                        ),
                    ),
                    array(
                        'key' => 'field_testimonial_rating',
                        'label' => 'Rating',
                        'name' => 'testimonial_rating',
                        'type' => 'number',
                        'min' => 1,
                        'max' => 5,
                        'default_value' => 5,
                        'wrapper' => array(
                            'width' => '25',
                        ),
                    ),
                    array(
                        'key' => 'field_testimonial_featured',
                        'label' => 'Featured Testimonial',
                        'name' => 'testimonial_featured',
                        'type' => 'true_false',
                        'default_value' => 0,
                        'ui' => 1,
                        'wrapper' => array(
                            'width' => '25',
                        ),
                    ),
                    array(
                        'key' => 'field_testimonial_member',
                        'label' => 'Member',
                        'name' => 'testimonial_member',
                        'type' => 'post_object',
                        'post_type' => array('bcn_member'),
                        'return_format' => 'object',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_testimonial_event_context',
                        'label' => 'Related Event',
                        'name' => 'testimonial_event_context',
                        'type' => 'post_object',
                        'post_type' => array('bcn_event'),
                        'return_format' => 'object',
                        'allow_null' => 1,
                    ),
                    array(
                        'key' => 'field_testimonial_content',
                        'label' => 'Testimonial Content',
                        'name' => 'testimonial_content',
                        'type' => 'textarea',
                        'required' => 1,
                        'rows' => 6,
                        'placeholder' => 'Share your experience with BCN...',
                    ),
                    array(
                        'key' => 'field_testimonial_video_url',
                        'label' => 'Video URL',
                        'name' => 'testimonial_video_url',
                        'type' => 'url',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_testimonial_type',
                                    'operator' => '==',
                                    'value' => 'video',
                                ),
                            ),
                        ),
                        'placeholder' => 'https://youtube.com/watch?v=...',
                    ),
                    array(
                        'key' => 'field_testimonial_photo',
                        'label' => 'Photo',
                        'name' => 'testimonial_photo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_testimonial_type',
                                    'operator' => '==',
                                    'value' => 'photo',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_testimonial_audio_file',
                        'label' => 'Audio File',
                        'name' => 'testimonial_audio_file',
                        'type' => 'file',
                        'return_format' => 'array',
                        'mime_types' => 'mp3,wav,ogg',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_testimonial_type',
                                    'operator' => '==',
                                    'value' => 'audio',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_testimonial_industry_focus',
                        'label' => 'Industry Focus',
                        'name' => 'testimonial_industry_focus',
                        'type' => 'select',
                        'choices' => array(
                            'cultivation' => 'Cultivation',
                            'retail' => 'Retail',
                            'advocacy' => 'Advocacy',
                            'education' => 'Education',
                            'networking' => 'Networking',
                            'general' => 'General',
                        ),
                        'default_value' => 'general',
                    ),
                    array(
                        'key' => 'field_testimonial_approval_status',
                        'label' => 'Approval Status',
                        'name' => 'testimonial_approval_status',
                        'type' => 'select',
                        'choices' => array(
                            'pending' => 'Pending Review',
                            'approved' => 'Approved',
                            'featured' => 'Featured',
                            'rejected' => 'Rejected',
                        ),
                        'default_value' => 'pending',
                    ),
                    array(
                        'key' => 'field_testimonial_member_photo',
                        'label' => 'Member Photo (Optional)',
                        'name' => 'testimonial_member_photo',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'instructions' => 'Optional photo of the member giving the testimonial',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'bcn_testimonial',
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
    
    /**
     * Enqueue testimonial scripts and styles
     */
    public function enqueue_testimonial_scripts() {
        wp_enqueue_script('bcn-testimonial', get_template_directory_uri() . '/assets/js/testimonial-system.js', array('jquery'), '1.0.0', true);
        wp_enqueue_style('bcn-testimonial', get_template_directory_uri() . '/assets/css/testimonial-estimonial.css', array(), '1.0.0');
        
        wp_localize_script('bcn-testimonial', 'bcnTestimonial', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bcn_testimonial_nonce'),
        ));
    }
    
    /**
     * Handle testimonial form submission
     */
    public function handle_testimonial_submission() {
        check_ajax_referer('bcn_testimonial_nonce', 'nonce');
        
        $member_id = intval($_POST['member_id']);
        $content = sanitize_textarea_field($_POST['content']);
        $type = sanitize_text_field($_POST['type']);
        $rating = intval($_POST['rating']);
        $event_id = !empty($_POST['event_id']) ? intval($_POST['event_id']) : null;
        $industry_focus = sanitize_text_field($_POST['industry_focus']);
        
        if (empty($content) || empty($member_id)) {
            wp_send_json_error('Missing required fields');
        }
        
        // Create testimonial post
        $post_id = wp_insert_post(array(
            'post_type' => 'bcn_testimonial',
            'post_title' => 'Testimonial from ' . get_the_title($member_id),
            'post_content' => $content,
            'post_status' => 'pending',
            'meta_input' => array(
                'testimonial_type' => $type,
                'testimonial_source' => 'member_portal',
                'testimonial_rating' => $rating,
                'testimonial_industry_focus' => $industry_focus,
                'testimonial_approval_status' => 'pending',
            )
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            // Update ACF fields
            update_field('testimonial_member', $member_id, $post_id);
            if ($event_id) {
                update_field('testimonial_event_context', $event_id, $post_id);
            }
            
            // Award points for testimonial submission
            $this->award_testimonial_points($member_id, $type);
            
            // Send notification email
            $this->send_testimonial_notification($post_id, $member_id);
            
            wp_send_json_success('Testimonial submitted successfully');
        } else {
            wp_send_json_error('Failed to submit testimonial');
        }
    }
    
    /**
     * Get member testimonials for dashboard
     */
    public function get_member_testimonials() {
        check_ajax_referer('bcn_testimonial_nonce', 'nonce');
        
        $member_id = intval($_POST['member_id']);
        $testimonials = get_posts(array(
            'post_type' => 'bcn_testimonial',
            'meta_query' => array(
                array(
                    'key' => 'testimonial_member',
                    'value' => $member_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => -1,
            'post_status' => array('publish', 'pending')
        ));
        
        $formatted_testimonials = array();
        foreach ($testimonials as $testimonial) {
            $formatted_testimonials[] = array(
                'id' => $testimonial->ID,
                'content' => $testimonial->post_content,
                'status' => get_field('testimonial_approval_status', $testimonial->ID),
                'type' => get_field('testimonial_type', $testimonial->ID),
                'rating' => get_field('testimonial_rating', $testimonial->ID),
                'date' => $testimonial->post_date,
                'featured' => get_field('testimonial_featured', $testimonial->ID)
            );
        }
        
        wp_send_json_success($formatted_testimonials);
    }
    
    /**
     * Testimonial form shortcode
     */
    public function testimonial_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'member_id' => '',
            'event_id' => '',
            'type' => 'written',
            'show_rating' => 'true',
        ), $atts);
        
        if (empty($atts['member_id'])) {
            return '<p>Error: Member ID required</p>';
        }
        
        ob_start();
        ?>
        <div class="bcn-testimonial-form" data-member-id="<?php echo esc_attr($atts['member_id']); ?>" data-event-id="<?php echo esc_attr($atts['event_id']); ?>">
            <h3>Share Your Experience</h3>
            <form id="testimonial-form">
                <div class="testimonial-type-selection">
                    <label>Testimonial Type:</label>
                    <select name="type" required>
                        <option value="written" <?php selected($atts['type'], 'written'); ?>>Written</option>
                        <option value="video">Video</option>
                        <option value="photo">Photo</option>
                        <option value="audio">Audio</option>
                    </select>
                </div>
                
                <?php if ($atts['show_rating'] === 'true') : ?>
                <div class="testimonial-rating">
                    <label>Rating:</label>
                    <div class="star-rating">
                        <input type="radio" name="rating" value="5" id="star5" checked>
                        <label for="star5">★</label>
                        <input type="radio" name="rating" value="4" id="star4">
                        <label for="star4">★</label>
                        <input type="radio" name="rating" value="3" id="star3">
                        <label for="star3">★</label>
                        <input type="radio" name="rating" value="2" id="star2">
                        <label for="star2">★</label>
                        <input type="radio" name="rating" value="1" id="star1">
                        <label for="star1">★</label>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="testimonial-content">
                    <label for="content">Your Testimonial:</label>
                    <textarea name="content" id="content" rows="6" placeholder="Share your experience with BCN..." required></textarea>
                </div>
                
                <div class="testimonial-industry-focus">
                    <label for="industry_focus">Industry Focus:</label>
                    <select name="industry_focus" id="industry_focus">
                        <option value="general">General</option>
                        <option value="cultivation">Cultivation</option>
                        <option value="retail">Retail</option>
                        <option value="advocacy">Advocacy</option>
                        <option value="education">Education</option>
                        <option value="networking">Networking</option>
                    </select>
                </div>
                
                <div class="testimonial-submit">
                    <button type="submit" class="button button-primary">Submit Testimonial</button>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Testimonial display shortcode
     */
    public function testimonial_display_shortcode($atts) {
        $atts = shortcode_atts(array(
            'type' => '',
            'featured' => 'false',
            'limit' => 10,
            'member_id' => '',
            'industry_focus' => '',
        ), $atts);
        
        $args = array(
            'post_type' => 'bcn_testimonial',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'testimonial_approval_status',
                    'value' => array('approved', 'featured'),
                    'compare' => 'IN'
                )
            )
        );
        
        if ($atts['featured'] === 'true') {
            $args['meta_query'][] = array(
                'key' => 'testimonial_featured',
                'value' => 1,
                'compare' => '='
            );
        }
        
        if (!empty($atts['type'])) {
            $args['meta_query'][] = array(
                'key' => 'testimonial_type',
                'value' => $atts['type'],
                'compare' => '='
            );
        }
        
        if (!empty($atts['industry_focus'])) {
            $args['meta_query'][] = array(
                'key' => 'testimonial_industry_focus',
                'value' => $atts['industry_focus'],
                'compare' => '='
            );
        }
        
        $testimonials = get_posts($args);
        
        if (empty($testimonials)) {
            return '<p>No testimonials found.</p>';
        }
        
        ob_start();
        ?>
        <div class="bcn-testimonials-display">
            <?php foreach ($testimonials as $testimonial) : 
                $member = get_field('testimonial_member', $testimonial->ID);
                $rating = get_field('testimonial_rating', $testimonial->ID);
                $type = get_field('testimonial_type', $testimonial->ID);
            ?>
            <div class="testimonial-item testimonial-item--<?php echo esc_attr($type); ?>">
                <div class="testimonial-content">
                    <?php echo wp_kses_post($testimonial->post_content); ?>
                </div>
                
                <div class="testimonial-meta">
                    <div class="testimonial-rating">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <span class="star <?php echo $i <= $rating ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    
                    <div class="testimonial-author">
                        <?php if ($member) : ?>
                            <strong><?php echo esc_html($member->post_title); ?></strong>
                        <?php endif; ?>
                        <span class="testimonial-date"><?php echo get_the_date('M j, Y', $testimonial->ID); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Member testimonial dashboard shortcode
     */
    public function member_testimonial_dashboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'member_id' => '',
        ), $atts);
        
        if (empty($atts['member_id'])) {
            return '<p>Error: Member ID required</p>';
        }
        
        ob_start();
        ?>
        <div class="bcn-member-testimonial-dashboard" data-member-id="<?php echo esc_attr($atts['member_id']); ?>">
            <h3>Your Testimonials</h3>
            <div class="testimonial-stats">
                <div class="stat-item">
                    <span class="stat-number" id="total-testimonials">0</span>
                    <span class="stat-label">Total Testimonials</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="featured-testimonials">0</span>
                    <span class="stat-label">Featured</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="pending-testimonials">0</span>
                    <span class="stat-label">Pending</span>
                </div>
            </div>
            
            <div class="testimonial-list" id="testimonial-list">
                <div class="loading">Loading testimonials...</div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Award points for testimonial submission
     */
    private function award_testimonial_points($member_id, $type) {
        $points = 10; // Base points for testimonial
        
        // Bonus points for different types
        switch ($type) {
            case 'video':
                $points += 15;
                break;
            case 'photo':
                $points += 10;
                break;
            case 'audio':
                $points += 5;
                break;
        }
        
        // Update member points
        $current_points = get_post_meta($member_id, 'member_points', true) ?: 0;
        update_post_meta($member_id, 'member_points', $current_points + $points);
        
        // Check for achievement unlocks
        $this->check_testimonial_achievements($member_id);
    }
    
    /**
     * Check for testimonial-related achievements
     */
    private function check_testimonial_achievements($member_id) {
        $testimonial_count = get_posts(array(
            'post_type' => 'bcn_testimonial',
            'meta_query' => array(
                array(
                    'key' => 'testimonial_member',
                    'value' => $member_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => -1,
            'fields' => 'ids'
        ));
        
        $count = count($testimonial_count);
        
        // Award achievements based on testimonial count
        $achievements = array(
            1 => 'first_testimonial',
            5 => 'testimonial_contributor',
            10 => 'testimonial_champion',
            25 => 'testimonial_expert'
        );
        
        foreach ($achievements as $threshold => $achievement) {
            if ($count >= $threshold) {
                $this->award_achievement($member_id, $achievement);
            }
        }
    }
    
    /**
     * Award achievement to member
     */
    private function award_achievement($member_id, $achievement_slug) {
        $current_achievements = get_post_meta($member_id, 'member_achievements', true) ?: array();
        
        if (!in_array($achievement_slug, $current_achievements)) {
            $current_achievements[] = $achievement_slug;
            update_post_meta($member_id, 'member_achievements', $current_achievements);
            
            // Send achievement notification
            $this->send_achievement_notification($member_id, $achievement_slug);
        }
    }
    
    /**
     * Send testimonial notification email
     */
    private function send_testimonial_notification($testimonial_id, $member_id) {
        $admin_email = get_option('admin_email');
        $member_name = get_the_title($member_id);
        
        $subject = 'New Testimonial Submitted - ' . $member_name;
        $message = "A new testimonial has been submitted by {$member_name}.\n\n";
        $message .= "Review it at: " . admin_url("post.php?post={$testimonial_id}&action=edit");
        
        wp_mail($admin_email, $subject, $message);
    }
    
    /**
     * Send achievement notification
     */
    private function send_achievement_notification($member_id, $achievement_slug) {
        // This will be implemented in the member dashboard system
        // For now, just log the achievement
        error_log("Achievement awarded: {$achievement_slug} to member {$member_id}");
    }
}

// Initialize the enhanced testimonial system
new BCN_Enhanced_Testimonial_System();

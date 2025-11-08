<?php
/**
 * Member Experience Features
 * Handles testimonials, reviews, and engagement tracking
 *
 * @package BCN_WP_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register testimonial meta fields for members
 */
function bcn_register_testimonial_meta() {
    register_post_meta(
        'bcn_member',
        'bcn_member_testimonials',
        array(
            'type'         => 'array',
            'description'  => __('Member testimonials and reviews', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
            'default'      => array(),
        )
    );

    register_post_meta(
        'bcn_member',
        'bcn_member_rating',
        array(
            'type'         => 'number',
            'description'  => __('Average member rating (1-5)', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
            'default'      => 0,
        )
    );

    register_post_meta(
        'bcn_member',
        'bcn_member_review_count',
        array(
            'type'         => 'integer',
            'description'  => __('Total number of reviews', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
            'default'      => 0,
        )
    );

    register_post_meta(
        'bcn_member',
        'bcn_member_engagement_metrics',
        array(
            'type'         => 'array',
            'description'  => __('Member engagement metrics and analytics', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => false,
            'default'      => array(),
        )
    );
}
add_action('init', 'bcn_register_testimonial_meta');

/**
 * Add testimonial meta box to member edit screen
 */
function bcn_add_testimonial_meta_box() {
    add_meta_box(
        'bcn-member-testimonials',
        __('Testimonials & Reviews', 'bcn-wp-theme'),
        'bcn_testimonial_meta_box_callback',
        'bcn_member',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'bcn_add_testimonial_meta_box');

/**
 * Render testimonial meta box
 */
function bcn_testimonial_meta_box_callback($post) {
    wp_nonce_field('bcn_testimonial_meta_box', 'bcn_testimonial_meta_box_nonce');
    
    $testimonials = get_post_meta($post->ID, 'bcn_member_testimonials', true);
    $rating = get_post_meta($post->ID, 'bcn_member_rating', true);
    $review_count = get_post_meta($post->ID, 'bcn_member_review_count', true);
    
    if (!is_array($testimonials)) {
        $testimonials = array();
    }
    ?>
    <div class="bcn-testimonial-admin">
        <div class="bcn-testimonial-stats">
            <div class="bcn-testimonial-stat">
                <label><strong><?php esc_html_e('Average Rating', 'bcn-wp-theme'); ?></strong></label>
                <div class="bcn-rating-display">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <span class="bcn-star <?php echo $i <= $rating ? 'filled' : ''; ?>">★</span>
                    <?php endfor; ?>
                    <span class="bcn-rating-text"><?php echo esc_html(number_format($rating, 1)); ?>/5</span>
                </div>
            </div>
            <div class="bcn-testimonial-stat">
                <label><strong><?php esc_html_e('Total Reviews', 'bcn-wp-theme'); ?></strong></label>
                <span class="bcn-review-count"><?php echo esc_html($review_count); ?></span>
            </div>
        </div>

        <div class="bcn-testimonials-list">
            <h4><?php esc_html_e('Testimonials', 'bcn-wp-theme'); ?></h4>
            <div id="bcn-testimonials-container">
                <?php foreach ($testimonials as $index => $testimonial) : ?>
                    <div class="bcn-testimonial-item" data-index="<?php echo esc_attr($index); ?>">
                        <div class="bcn-testimonial-header">
                            <input type="text" 
                                   name="bcn_testimonials[<?php echo esc_attr($index); ?>][author]" 
                                   value="<?php echo esc_attr($testimonial['author'] ?? ''); ?>" 
                                   placeholder="<?php esc_attr_e('Author name', 'bcn-wp-theme'); ?>"
                                   class="bcn-testimonial-author" />
                            <div class="bcn-testimonial-rating">
                                <label><?php esc_html_e('Rating', 'bcn-wp-theme'); ?></label>
                                <select name="bcn_testimonials[<?php echo esc_attr($index); ?>][rating]">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <option value="<?php echo esc_attr($i); ?>" 
                                                <?php selected($testimonial['rating'] ?? 0, $i); ?>>
                                            <?php echo esc_html($i); ?> <?php esc_html_e('stars', 'bcn-wp-theme'); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <button type="button" class="bcn-remove-testimonial"><?php esc_html_e('Remove', 'bcn-wp-theme'); ?></button>
                        </div>
                        <textarea name="bcn_testimonials[<?php echo esc_attr($index); ?>][content]" 
                                  placeholder="<?php esc_attr_e('Testimonial content...', 'bcn-wp-theme'); ?>"
                                  class="bcn-testimonial-content"><?php echo esc_textarea($testimonial['content'] ?? ''); ?></textarea>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" id="bcn-add-testimonial" class="button"><?php esc_html_e('Add Testimonial', 'bcn-wp-theme'); ?></button>
        </div>
    </div>

    <style>
    .bcn-testimonial-admin {
        max-width: 800px;
    }
    .bcn-testimonial-stats {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
    }
    .bcn-testimonial-stat {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .bcn-rating-display {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .bcn-star {
        color: #d1d5db;
        font-size: 1.2rem;
    }
    .bcn-star.filled {
        color: #fbbf24;
    }
    .bcn-rating-text {
        margin-left: 0.5rem;
        font-weight: 600;
    }
    .bcn-review-count {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--bcn-blue);
    }
    .bcn-testimonial-item {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: white;
    }
    .bcn-testimonial-header {
        display: flex;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1rem;
    }
    .bcn-testimonial-author {
        flex: 1;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
    }
    .bcn-testimonial-rating {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .bcn-testimonial-rating select {
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
    }
    .bcn-testimonial-content {
        width: 100%;
        min-height: 80px;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        resize: vertical;
    }
    .bcn-remove-testimonial {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
    }
    .bcn-remove-testimonial:hover {
        background: #dc2626;
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        let testimonialIndex = <?php echo count($testimonials); ?>;
        
        $('#bcn-add-testimonial').on('click', function() {
            const template = `
                <div class="bcn-testimonial-item" data-index="${testimonialIndex}">
                    <div class="bcn-testimonial-header">
                        <input type="text" 
                               name="bcn_testimonials[${testimonialIndex}][author]" 
                               placeholder="<?php esc_attr_e('Author name', 'bcn-wp-theme'); ?>"
                               class="bcn-testimonial-author" />
                        <div class="bcn-testimonial-rating">
                            <label><?php esc_html_e('Rating', 'bcn-wp-theme'); ?></label>
                            <select name="bcn_testimonials[${testimonialIndex}][rating]">
                                <option value="1">1 <?php esc_html_e('star', 'bcn-wp-theme'); ?></option>
                                <option value="2">2 <?php esc_html_e('stars', 'bcn-wp-theme'); ?></option>
                                <option value="3">3 <?php esc_html_e('stars', 'bcn-wp-theme'); ?></option>
                                <option value="4">4 <?php esc_html_e('stars', 'bcn-wp-theme'); ?></option>
                                <option value="5" selected>5 <?php esc_html_e('stars', 'bcn-wp-theme'); ?></option>
                            </select>
                        </div>
                        <button type="button" class="bcn-remove-testimonial"><?php esc_html_e('Remove', 'bcn-wp-theme'); ?></button>
                    </div>
                    <textarea name="bcn_testimonials[${testimonialIndex}][content]" 
                              placeholder="<?php esc_attr_e('Testimonial content...', 'bcn-wp-theme'); ?>"
                              class="bcn-testimonial-content"></textarea>
                </div>
            `;
            
            $('#bcn-testimonials-container').append(template);
            testimonialIndex++;
        });
        
        $(document).on('click', '.bcn-remove-testimonial', function() {
            $(this).closest('.bcn-testimonial-item').remove();
        });
    });
    </script>
    <?php
}

/**
 * Save testimonial meta data
 */
function bcn_save_testimonial_meta($post_id) {
    if (!isset($_POST['bcn_testimonial_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['bcn_testimonial_meta_box_nonce'], 'bcn_testimonial_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save testimonials
    if (isset($_POST['bcn_testimonials']) && is_array($_POST['bcn_testimonials'])) {
        $testimonials = array();
        $total_rating = 0;
        $rating_count = 0;

        foreach ($_POST['bcn_testimonials'] as $testimonial) {
            if (!empty($testimonial['content']) && !empty($testimonial['author'])) {
                $testimonials[] = array(
                    'author' => sanitize_text_field($testimonial['author']),
                    'content' => sanitize_textarea_field($testimonial['content']),
                    'rating' => intval($testimonial['rating']),
                    'date' => current_time('mysql'),
                );
                
                $total_rating += intval($testimonial['rating']);
                $rating_count++;
            }
        }

        update_post_meta($post_id, 'bcn_member_testimonials', $testimonials);
        
        // Calculate average rating
        $average_rating = $rating_count > 0 ? $total_rating / $rating_count : 0;
        update_post_meta($post_id, 'bcn_member_rating', $average_rating);
        update_post_meta($post_id, 'bcn_member_review_count', $rating_count);
    } else {
        update_post_meta($post_id, 'bcn_member_testimonials', array());
        update_post_meta($post_id, 'bcn_member_rating', 0);
        update_post_meta($post_id, 'bcn_member_review_count', 0);
    }
}
add_action('save_post_bcn_member', 'bcn_save_testimonial_meta');

/**
 * Track member engagement
 */
function bcn_track_member_engagement($member_id, $action, $data = array()) {
    $metrics = get_post_meta($member_id, 'bcn_member_engagement_metrics', true);
    if (!is_array($metrics)) {
        $metrics = array();
    }

    $today = current_time('Y-m-d');
    if (!isset($metrics[$today])) {
        $metrics[$today] = array();
    }

    if (!isset($metrics[$today][$action])) {
        $metrics[$today][$action] = 0;
    }

    $metrics[$today][$action]++;
    $metrics[$today]['last_activity'] = current_time('mysql');

    // Keep only last 30 days of data
    $cutoff_date = date('Y-m-d', strtotime('-30 days'));
    foreach ($metrics as $date => $day_metrics) {
        if ($date < $cutoff_date) {
            unset($metrics[$date]);
        }
    }

    update_post_meta($member_id, 'bcn_member_engagement_metrics', $metrics);
    update_post_meta($member_id, 'bcn_member_last_activity', current_time('mysql'));
}

/**
 * Get member engagement score
 */
function bcn_get_member_engagement_score($member_id) {
    $metrics = get_post_meta($member_id, 'bcn_member_engagement_metrics', true);
    if (!is_array($metrics)) {
        return 0;
    }

    $total_engagement = 0;
    $days_counted = 0;

    foreach ($metrics as $date => $day_metrics) {
        if (isset($day_metrics['last_activity'])) {
            $day_engagement = 0;
            foreach ($day_metrics as $action => $count) {
                if ($action !== 'last_activity') {
                    $day_engagement += $count;
                }
            }
            $total_engagement += $day_engagement;
            $days_counted++;
        }
    }

    return $days_counted > 0 ? round($total_engagement / $days_counted) : 0;
}

/**
 * Shortcode for member testimonials display
 */
function bcn_member_testimonials_shortcode($atts) {
    $atts = shortcode_atts(array(
        'member_id' => get_the_ID(),
        'limit' => 3,
        'show_rating' => 'true',
    ), $atts, 'member_testimonials');

    $member_id = intval($atts['member_id']);
    $limit = intval($atts['limit']);
    $show_rating = filter_var($atts['show_rating'], FILTER_VALIDATE_BOOLEAN);

    $testimonials = get_post_meta($member_id, 'bcn_member_testimonials', true);
    if (!is_array($testimonials) || empty($testimonials)) {
        return '';
    }

    $testimonials = array_slice($testimonials, 0, $limit);
    $rating = get_post_meta($member_id, 'bcn_member_rating', true);

    ob_start();
    ?>
    <div class="bcn-member-testimonials">
        <?php if ($show_rating && $rating > 0) : ?>
            <div class="bcn-testimonials-rating">
                <div class="bcn-rating-stars">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <span class="bcn-star <?php echo $i <= $rating ? 'filled' : ''; ?>">★</span>
                    <?php endfor; ?>
                </div>
                <span class="bcn-rating-text"><?php echo esc_html(number_format($rating, 1)); ?>/5</span>
            </div>
        <?php endif; ?>

        <div class="bcn-testimonials-list">
            <?php foreach ($testimonials as $testimonial) : ?>
                <div class="bcn-testimonial">
                    <blockquote class="bcn-testimonial-quote">
                        "<?php echo esc_html($testimonial['content']); ?>"
                    </blockquote>
                    <cite class="bcn-testimonial-author">
                        — <?php echo esc_html($testimonial['author']); ?>
                    </cite>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
    .bcn-member-testimonials {
        margin: 2rem 0;
    }
    .bcn-testimonials-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .bcn-rating-stars {
        display: flex;
        gap: 0.25rem;
    }
    .bcn-star {
        color: #d1d5db;
        font-size: 1.2rem;
    }
    .bcn-star.filled {
        color: #fbbf24;
    }
    .bcn-rating-text {
        font-weight: 600;
        color: #374151;
    }
    .bcn-testimonials-list {
        display: grid;
        gap: 1rem;
    }
    .bcn-testimonial {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
        border-left: 3px solid var(--bcn-blue);
    }
    .bcn-testimonial-quote {
        margin: 0 0 0.5rem 0;
        font-style: italic;
        color: #374151;
    }
    .bcn-testimonial-author {
        font-size: 0.9rem;
        color: #6b7280;
        font-weight: 500;
    }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('member_testimonials', 'bcn_member_testimonials_shortcode');

/**
 * AJAX handler for member interactions
 */
function bcn_handle_member_interaction() {
    check_ajax_referer('bcn_member_interaction', 'nonce');
    
    $member_id = intval($_POST['member_id']);
    $action = sanitize_text_field($_POST['action_type']);
    $data = isset($_POST['data']) ? $_POST['data'] : array();
    
    if ($member_id && $action) {
        bcn_track_member_engagement($member_id, $action, $data);
        wp_send_json_success(array('message' => 'Interaction tracked'));
    } else {
        wp_send_json_error(array('message' => 'Invalid data'));
    }
}
add_action('wp_ajax_bcn_member_interaction', 'bcn_handle_member_interaction');
add_action('wp_ajax_nopriv_bcn_member_interaction', 'bcn_handle_member_interaction');
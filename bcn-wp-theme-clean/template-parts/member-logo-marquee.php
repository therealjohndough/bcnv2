<?php
/**
 * Member Logo Marquee Template Part
 * 
 * Displays a scrolling marquee of member logos
 * 
 * @package BCN_WP_Theme
 */

defined('ABSPATH') || exit;

// Get marquee settings from shortcode attributes or defaults
$atts = wp_parse_args($atts ?? array(), array(
    'level' => '', // Filter by membership level
    'featured' => 'true', // Show only featured members
    'limit' => 20, // Maximum number of logos
    'speed' => 'slow', // Animation speed: slow, medium, fast
    'direction' => 'left', // Direction: left, right
    'pause_on_hover' => 'true', // Pause animation on hover
    'show_titles' => 'false', // Show member names below logos
    'height' => '120', // Marquee height in pixels
    'spacing' => '40', // Space between logos in pixels
));

// Query members
$args = array(
    'post_type' => 'bcn_member',
    'posts_per_page' => intval($atts['limit']),
    'post_status' => 'publish',
    'meta_query' => array(
        'relation' => 'AND',
    ),
);

// Filter by membership level
if (!empty($atts['level'])) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'bcn_membership_level',
            'field' => 'slug',
            'terms' => sanitize_text_field($atts['level']),
        ),
    );
}

// Filter by featured status
if ($atts['featured'] === 'true') {
    $args['meta_query'][] = array(
        'key' => 'bcn_member_featured',
        'value' => '1',
        'compare' => '=',
    );
}

$members = new WP_Query($args);

if (!$members->have_posts()) {
    return;
}

// Marquee settings
$speed_class = 'bcn-marquee--' . sanitize_html_class($atts['speed']);
$direction_class = 'bcn-marquee--' . sanitize_html_class($atts['direction']);
$pause_class = $atts['pause_on_hover'] === 'true' ? 'bcn-marquee--pause-hover' : '';
$show_titles_class = $atts['show_titles'] === 'true' ? 'bcn-marquee--show-titles' : '';

// Inline styles
$marquee_height = intval($atts['height']);
$logo_spacing = intval($atts['spacing']);
?>

<div class="bcn-member-logo-marquee <?php echo esc_attr($speed_class . ' ' . $direction_class . ' ' . $pause_class . ' ' . $show_titles_class); ?>" 
     style="--marquee-height: <?php echo esc_attr($marquee_height); ?>px; --logo-spacing: <?php echo esc_attr($logo_spacing); ?>px;">
    
    <div class="bcn-marquee__container">
        <div class="bcn-marquee__track">
            <?php
            // First set of logos
            while ($members->have_posts()) :
                $members->the_post();
                $logo_id = get_post_thumbnail_id();
                $logo_alt = get_post_meta($logo_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                $member_url = get_permalink();
                $member_title = get_the_title();
                ?>
                <div class="bcn-marquee__item">
                    <a href="<?php echo esc_url($member_url); ?>" 
                       class="bcn-marquee__logo-link" 
                       aria-label="<?php echo esc_attr($member_title); ?>">
                        <?php if ($logo_id) : ?>
                            <?php echo wp_get_attachment_image($logo_id, 'medium', false, array(
                                'class' => 'bcn-marquee__logo-image',
                                'alt' => esc_attr($logo_alt),
                                'loading' => 'lazy',
                                'decoding' => 'async'
                            )); ?>
                        <?php else : ?>
                            <div class="bcn-marquee__logo-placeholder">
                                <span class="bcn-marquee__logo-text"><?php echo esc_html(substr($member_title, 0, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($atts['show_titles'] === 'true') : ?>
                            <span class="bcn-marquee__logo-title"><?php echo esc_html($member_title); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                <?php
            endwhile;
            
            // Reset query for second set (for seamless loop)
            $members->rewind_posts();
            
            // Second set of logos (for seamless infinite scroll)
            while ($members->have_posts()) :
                $members->the_post();
                $logo_id = get_post_thumbnail_id();
                $logo_alt = get_post_meta($logo_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                $member_url = get_permalink();
                $member_title = get_the_title();
                ?>
                <div class="bcn-marquee__item">
                    <a href="<?php echo esc_url($member_url); ?>" 
                       class="bcn-marquee__logo-link" 
                       aria-label="<?php echo esc_attr($member_title); ?>">
                        <?php if ($logo_id) : ?>
                            <?php echo wp_get_attachment_image($logo_id, 'medium', false, array(
                                'class' => 'bcn-marquee__logo-image',
                                'alt' => esc_attr($logo_alt),
                                'loading' => 'lazy',
                                'decoding' => 'async'
                            )); ?>
                        <?php else : ?>
                            <div class="bcn-marquee__logo-placeholder">
                                <span class="bcn-marquee__logo-text"><?php echo esc_html(substr($member_title, 0, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($atts['show_titles'] === 'true') : ?>
                            <span class="bcn-marquee__logo-title"><?php echo esc_html($member_title); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                <?php
            endwhile;
            ?>
        </div>
    </div>
</div>

<?php
wp_reset_postdata();
?>
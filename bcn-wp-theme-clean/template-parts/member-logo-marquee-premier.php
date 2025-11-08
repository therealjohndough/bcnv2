<?php
/**
 * Premier Member Logo Marquee Template Part
 * 
 * Displays a scrolling marquee of Premier member logos
 * 
 * @package BCN_WP_Theme
 */

defined('ABSPATH') || exit;

// Get marquee settings from shortcode attributes or defaults
$atts = wp_parse_args($atts ?? array(), array(
    'limit' => 15, // Maximum number of Premier logos
    'speed' => 'medium', // Animation speed: slow, medium, fast
    'direction' => 'left', // Direction: left, right
    'pause_on_hover' => 'true', // Pause animation on hover
    'show_titles' => 'false', // Show member names below logos
    'height' => '140', // Marquee height in pixels
    'spacing' => '50', // Space between logos in pixels
    'featured_only' => 'true', // Show only featured Premier members
));

// Query Premier members
$args = array(
    'post_type' => 'bcn_member',
    'posts_per_page' => intval($atts['limit']),
    'post_status' => 'publish',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => '_thumbnail_id',
            'compare' => 'EXISTS'
        ),
    ),
    'tax_query' => array(
        array(
            'taxonomy' => 'bcn_membership_level',
            'field' => 'slug',
            'terms' => 'premier-member',
        ),
    ),
);

// Filter by featured status if requested
if ($atts['featured_only'] === 'true') {
    $args['meta_query'][] = array(
        'key' => 'bcn_member_featured',
        'value' => '1',
        'compare' => '=',
    );
}

$premier_members = new WP_Query($args);

if (!$premier_members->have_posts()) {
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

<div class="bcn-premier-marquee bcn-member-logo-marquee <?php echo esc_attr($speed_class . ' ' . $direction_class . ' ' . $pause_class . ' ' . $show_titles_class); ?>" 
     style="--marquee-height: <?php echo esc_attr($marquee_height); ?>px; --logo-spacing: <?php echo esc_attr($logo_spacing); ?>px;">
    
    <!-- Premier Marquee Header -->
    <div class="bcn-marquee__header">
        <h3 class="bcn-marquee__title">Premier Members</h3>
        <div class="bcn-marquee__badge bcn-marquee__badge--premier">Premier</div>
    </div>
    
    <div class="bcn-marquee__container">
        <div class="bcn-marquee__track">
            <?php
            // First set of Premier logos
            while ($premier_members->have_posts()) :
                $premier_members->the_post();
                $logo_id = get_post_thumbnail_id();
                $logo_alt = get_post_meta($logo_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                $member_url = get_permalink();
                $member_title = get_the_title();
                $is_featured = get_post_meta(get_the_ID(), 'bcn_member_featured', true);
                ?>
                <div class="bcn-marquee__item <?php echo $is_featured ? 'bcn-marquee__item--featured' : ''; ?>">
                    <a href="<?php echo esc_url($member_url); ?>" 
                       class="bcn-marquee__logo-link bcn-marquee__logo-link--premier" 
                       aria-label="<?php echo esc_attr($member_title); ?>">
                        <?php if ($logo_id) : ?>
                            <?php echo wp_get_attachment_image($logo_id, 'medium', false, array(
                                'class' => 'bcn-marquee__logo-image',
                                'alt' => esc_attr($logo_alt),
                                'loading' => 'lazy',
                                'decoding' => 'async'
                            )); ?>
                        <?php else : ?>
                            <div class="bcn-marquee__logo-placeholder bcn-marquee__logo-placeholder--premier">
                                <span class="bcn-marquee__logo-text"><?php echo esc_html(substr($member_title, 0, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($is_featured) : ?>
                            <div class="bcn-marquee__featured-badge">★</div>
                        <?php endif; ?>
                        
                        <?php if ($atts['show_titles'] === 'true') : ?>
                            <span class="bcn-marquee__logo-title"><?php echo esc_html($member_title); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                <?php
            endwhile;
            
            // Reset query for second set (for seamless loop)
            $premier_members->rewind_posts();
            
            // Second set of Premier logos (for seamless infinite scroll)
            while ($premier_members->have_posts()) :
                $premier_members->the_post();
                $logo_id = get_post_thumbnail_id();
                $logo_alt = get_post_meta($logo_id, '_wp_attachment_image_alt', true) ?: get_the_title();
                $member_url = get_permalink();
                $member_title = get_the_title();
                $is_featured = get_post_meta(get_the_ID(), 'bcn_member_featured', true);
                ?>
                <div class="bcn-marquee__item <?php echo $is_featured ? 'bcn-marquee__item--featured' : ''; ?>">
                    <a href="<?php echo esc_url($member_url); ?>" 
                       class="bcn-marquee__logo-link bcn-marquee__logo-link--premier" 
                       aria-label="<?php echo esc_attr($member_title); ?>">
                        <?php if ($logo_id) : ?>
                            <?php echo wp_get_attachment_image($logo_id, 'medium', false, array(
                                'class' => 'bcn-marquee__logo-image',
                                'alt' => esc_attr($logo_alt),
                                'loading' => 'lazy',
                                'decoding' => 'async'
                            )); ?>
                        <?php else : ?>
                            <div class="bcn-marquee__logo-placeholder bcn-marquee__logo-placeholder--premier">
                                <span class="bcn-marquee__logo-text"><?php echo esc_html(substr($member_title, 0, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($is_featured) : ?>
                            <div class="bcn-marquee__featured-badge">★</div>
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
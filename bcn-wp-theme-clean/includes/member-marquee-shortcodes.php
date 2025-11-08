<?php
/**
 * Member Marquee Shortcodes
 * 
 * Shortcodes for displaying member logo marquees
 * 
 * @package BCN_WP_Theme
 */

defined('ABSPATH') || exit;

/**
 * Premier Member Marquee Shortcode
 * 
 * Usage: [premier_member_marquee limit="15" speed="medium" height="140"]
 */
function bcn_premier_member_marquee_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 15,
        'speed' => 'medium',
        'direction' => 'left',
        'pause_on_hover' => 'true',
        'show_titles' => 'false',
        'height' => '140',
        'spacing' => '50',
        'featured_only' => 'true'
    ), $atts);
    
    ob_start();
    get_template_part('template-parts/member-logo-marquee-premier', null, $atts);
    return ob_get_clean();
}
add_shortcode('premier_member_marquee', 'bcn_premier_member_marquee_shortcode');

/**
 * Pro Member Marquee Shortcode
 * 
 * Usage: [pro_member_marquee limit="25" speed="fast" height="100"]
 */
function bcn_pro_member_marquee_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 25,
        'speed' => 'fast',
        'direction' => 'right',
        'pause_on_hover' => 'true',
        'show_titles' => 'false',
        'height' => '100',
        'spacing' => '30',
        'featured_only' => 'false'
    ), $atts);
    
    ob_start();
    get_template_part('template-parts/member-logo-marquee-pro', null, $atts);
    return ob_get_clean();
}
add_shortcode('pro_member_marquee', 'bcn_pro_member_marquee_shortcode');

/**
 * Combined Member Marquee Shortcode
 * 
 * Usage: [member_marquees show_premier="true" show_pro="true"]
 */
function bcn_member_marquees_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_premier' => 'true',
        'show_pro' => 'true',
        'premier_limit' => 15,
        'pro_limit' => 25,
        'premier_speed' => 'medium',
        'pro_speed' => 'fast'
    ), $atts);
    
    ob_start();
    ?>
    <div class="bcn-member-marquees">
        <?php if ($atts['show_premier'] === 'true') : ?>
            <section class="bcn-member-marquees__section bcn-member-marquees__section--premier">
                <div class="container">
                    <div class="bcn-member-marquees__header">
                        <h2 class="bcn-member-marquees__title">Premier Members</h2>
                        <p class="bcn-member-marquees__description">
                            Our top-tier members who have demonstrated exceptional commitment to the cannabis community.
                        </p>
                    </div>
                    <?php echo do_shortcode('[premier_member_marquee limit="' . $atts['premier_limit'] . '" speed="' . $atts['premier_speed'] . '"]'); ?>
                </div>
            </section>
        <?php endif; ?>
        
        <?php if ($atts['show_pro'] === 'true') : ?>
            <section class="bcn-member-marquees__section bcn-member-marquees__section--pro">
                <div class="container">
                    <div class="bcn-member-marquees__header">
                        <h2 class="bcn-member-marquees__title">Pro Members</h2>
                        <p class="bcn-member-marquees__description">
                            Professional members contributing to the growth and success of the cannabis industry.
                        </p>
                    </div>
                    <?php echo do_shortcode('[pro_member_marquee limit="' . $atts['pro_limit'] . '" speed="' . $atts['pro_speed'] . '"]'); ?>
                </div>
            </section>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('member_marquees', 'bcn_member_marquees_shortcode');

/**
 * Member Directory Shortcode
 * 
 * Usage: [member_directory columns="3" limit="12" show_filters="true"]
 */
function bcn_member_directory_shortcode($atts) {
    $atts = shortcode_atts(array(
        'columns' => 3,
        'limit' => 12,
        'show_filters' => 'true',
        'membership_level' => '',
        'featured_only' => 'false',
        'show_search' => 'true'
    ), $atts);
    
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
    if (!empty($atts['membership_level'])) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'bcn_membership_level',
                'field' => 'slug',
                'terms' => sanitize_text_field($atts['membership_level']),
            ),
        );
    }
    
    // Filter by featured status
    if ($atts['featured_only'] === 'true') {
        $args['meta_query'][] = array(
            'key' => 'bcn_member_featured',
            'value' => '1',
            'compare' => '=',
        );
    }
    
    $members = new WP_Query($args);
    
    if (!$members->have_posts()) {
        return '<p>No members found.</p>';
    }
    
    ob_start();
    ?>
    <div class="bcn-member-directory-shortcode">
        <?php if ($atts['show_filters'] === 'true' || $atts['show_search'] === 'true') : ?>
            <div class="bcn-member-directory-shortcode__filters">
                <?php if ($atts['show_search'] === 'true') : ?>
                    <div class="bcn-member-directory-shortcode__search">
                        <input type="text" class="bcn-member-directory-shortcode__search-input" 
                               placeholder="Search members..." id="directory-search">
                    </div>
                <?php endif; ?>
                
                <?php if ($atts['show_filters'] === 'true') : ?>
                    <div class="bcn-member-directory-shortcode__filter-group">
                        <select class="bcn-member-directory-shortcode__filter" id="directory-membership-filter">
                            <option value="">All Members</option>
                            <option value="premier-member">Premier Members</option>
                            <option value="pro-member">Pro Members</option>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="bcn-member-directory-shortcode__grid" 
             style="grid-template-columns: repeat(<?php echo esc_attr($atts['columns']); ?>, 1fr);">
            <?php while ($members->have_posts()) : $members->the_post(); ?>
                <?php
                $member_data = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'excerpt' => get_the_excerpt(),
                    'logo_id' => get_post_thumbnail_id(),
                    'logo_alt' => get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true),
                    'membership_level' => wp_get_post_terms(get_the_ID(), 'bcn_membership_level', array('fields' => 'slugs')),
                    'is_featured' => get_post_meta(get_the_ID(), 'bcn_member_featured', true),
                    'company' => get_post_meta(get_the_ID(), 'bcn_member_company', true),
                );
                
                $membership_class = '';
                if (!empty($member_data['membership_level'])) {
                    $membership_class = 'bcn-member-card--' . sanitize_html_class($member_data['membership_level'][0]);
                }
                
                $featured_class = $member_data['is_featured'] ? 'bcn-member-card--featured' : '';
                ?>
                
                <article class="bcn-member-card <?php echo esc_attr($membership_class . ' ' . $featured_class); ?>">
                    <div class="bcn-member-card__logo">
                        <a href="<?php echo esc_url($member_data['permalink']); ?>" class="bcn-member-card__logo-link">
                            <?php if ($member_data['logo_id']) : ?>
                                <?php echo wp_get_attachment_image($member_data['logo_id'], 'medium', false, array(
                                    'class' => 'bcn-member-card__logo-image',
                                    'alt' => esc_attr($member_data['logo_alt'] ?: $member_data['title']),
                                    'loading' => 'lazy',
                                    'decoding' => 'async'
                                )); ?>
                            <?php else : ?>
                                <div class="bcn-member-card__logo-placeholder">
                                    <span class="bcn-member-card__logo-text"><?php echo esc_html(substr($member_data['title'], 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <div class="bcn-member-card__content">
                        <h3 class="bcn-member-card__title">
                            <a href="<?php echo esc_url($member_data['permalink']); ?>"><?php echo esc_html($member_data['title']); ?></a>
                        </h3>
                        
                        <?php if ($member_data['company']) : ?>
                            <p class="bcn-member-card__company"><?php echo esc_html($member_data['company']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($member_data['membership_level'])) : ?>
                            <div class="bcn-member-card__membership-badge bcn-member-card__membership-badge--<?php echo esc_attr($member_data['membership_level'][0]); ?>">
                                <?php echo esc_html(ucfirst(str_replace('-', ' ', $member_data['membership_level'][0]))); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                
            <?php endwhile; ?>
        </div>
    </div>
    <?php
    
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('member_directory', 'bcn_member_directory_shortcode');
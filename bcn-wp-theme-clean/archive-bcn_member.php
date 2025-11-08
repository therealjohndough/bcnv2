<?php
/**
 * Member Archive Template
 * 
 * Beautifully styled archive page for BCN members
 * 
 * @package BCN_WP_Theme
 */

defined('ABSPATH') || exit;

get_header(); ?>

<div class="bcn-member-archive">
    <!-- Hero Section -->
    <section class="bcn-member-archive__hero">
        <div class="container">
            <div class="bcn-member-archive__hero-content">
                <h1 class="bcn-member-archive__title">Our Members</h1>
                <p class="bcn-member-archive__subtitle">
                    Discover the incredible businesses and professionals that make up the Buffalo Cannabis Network community.
                </p>
                
                <!-- Member Stats -->
                <div class="bcn-member-archive__stats">
                    <?php
                    $premier_count = wp_count_posts('bcn_member')->publish;
                    $featured_count = get_posts(array(
                        'post_type' => 'bcn_member',
                        'meta_key' => 'bcn_member_featured',
                        'meta_value' => '1',
                        'posts_per_page' => -1,
                        'fields' => 'ids'
                    ));
                    $featured_count = count($featured_count);
                    ?>
                    <div class="bcn-member-archive__stat">
                        <span class="bcn-member-archive__stat-number"><?php echo esc_html($premier_count); ?></span>
                        <span class="bcn-member-archive__stat-label">Total Members</span>
                    </div>
                    <div class="bcn-member-archive__stat">
                        <span class="bcn-member-archive__stat-number"><?php echo esc_html($featured_count); ?></span>
                        <span class="bcn-member-archive__stat-label">Featured</span>
                    </div>
                    <div class="bcn-member-archive__stat">
                        <span class="bcn-member-archive__stat-number">2</span>
                        <span class="bcn-member-archive__stat-label">Membership Levels</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Premier Members Marquee -->
    <section class="bcn-member-archive__marquee-section bcn-member-archive__marquee-section--premier">
        <div class="container">
            <div class="bcn-member-archive__marquee-header">
                <h2 class="bcn-member-archive__marquee-title">Premier Members</h2>
                <p class="bcn-member-archive__marquee-description">
                    Our top-tier members who have demonstrated exceptional commitment to the cannabis community.
                </p>
            </div>
            <?php
            // Include Premier marquee
            get_template_part('template-parts/member-logo-marquee-premier', null, array(
                'limit' => 15,
                'speed' => 'medium',
                'direction' => 'left',
                'height' => '140',
                'spacing' => '50',
                'featured_only' => 'true'
            ));
            ?>
        </div>
    </section>

    <!-- Pro Members Marquee -->
    <section class="bcn-member-archive__marquee-section bcn-member-archive__marquee-section--pro">
        <div class="container">
            <div class="bcn-member-archive__marquee-header">
                <h2 class="bcn-member-archive__marquee-title">Pro Members</h2>
                <p class="bcn-member-archive__marquee-description">
                    Professional members contributing to the growth and success of the cannabis industry.
                </p>
            </div>
            <?php
            // Include Pro marquee
            get_template_part('template-parts/member-logo-marquee-pro', null, array(
                'limit' => 25,
                'speed' => 'fast',
                'direction' => 'right',
                'height' => '100',
                'spacing' => '30',
                'featured_only' => 'false'
            ));
            ?>
        </div>
    </section>

    <!-- Member Directory -->
    <section class="bcn-member-archive__directory">
        <div class="container">
            <div class="bcn-member-archive__directory-header">
                <h2 class="bcn-member-archive__directory-title">Member Directory</h2>
                <p class="bcn-member-archive__directory-description">
                    Browse our complete member directory to find businesses and professionals in your area.
                </p>
                
                <!-- Filter Controls -->
                <div class="bcn-member-archive__filters">
                    <div class="bcn-member-archive__filter-group">
                        <label for="membership-filter" class="bcn-member-archive__filter-label">Membership Level:</label>
                        <select id="membership-filter" class="bcn-member-archive__filter-select">
                            <option value="">All Members</option>
                            <option value="premier-member">Premier Members</option>
                            <option value="pro-member">Pro Members</option>
                        </select>
                    </div>
                    
                    <div class="bcn-member-archive__filter-group">
                        <label for="featured-filter" class="bcn-member-archive__filter-label">Show:</label>
                        <select id="featured-filter" class="bcn-member-archive__filter-select">
                            <option value="">All Members</option>
                            <option value="featured">Featured Only</option>
                        </select>
                    </div>
                    
                    <div class="bcn-member-archive__filter-group">
                        <label for="search-members" class="bcn-member-archive__filter-label">Search:</label>
                        <input type="text" id="search-members" class="bcn-member-archive__filter-search" placeholder="Search members...">
                    </div>
                </div>
            </div>

            <!-- Member Grid -->
            <div class="bcn-member-archive__grid" id="member-grid">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php
                        // Get member data
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
                            'website' => get_post_meta(get_the_ID(), 'bcn_member_website', true),
                            'phone' => get_post_meta(get_the_ID(), 'bcn_member_phone', true),
                            'email' => get_post_meta(get_the_ID(), 'bcn_member_email', true),
                        );
                        
                        // Get membership level class
                        $membership_class = '';
                        if (!empty($member_data['membership_level'])) {
                            $membership_class = 'bcn-member-card--' . sanitize_html_class($member_data['membership_level'][0]);
                        }
                        
                        // Featured class
                        $featured_class = $member_data['is_featured'] ? 'bcn-member-card--featured' : '';
                        ?>
                        
                        <article class="bcn-member-card <?php echo esc_attr($membership_class . ' ' . $featured_class); ?>" 
                                 data-membership="<?php echo esc_attr(implode(' ', $member_data['membership_level'])); ?>"
                                 data-featured="<?php echo $member_data['is_featured'] ? 'true' : 'false'; ?>"
                                 data-title="<?php echo esc_attr(strtolower($member_data['title'])); ?>">
                            
                            <!-- Member Logo -->
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
                                    
                                    <?php if ($member_data['is_featured']) : ?>
                                        <div class="bcn-member-card__featured-badge">★</div>
                                    <?php endif; ?>
                                </a>
                            </div>
                            
                            <!-- Member Info -->
                            <div class="bcn-member-card__content">
                                <h3 class="bcn-member-card__title">
                                    <a href="<?php echo esc_url($member_data['permalink']); ?>"><?php echo esc_html($member_data['title']); ?></a>
                                </h3>
                                
                                <?php if ($member_data['company']) : ?>
                                    <p class="bcn-member-card__company"><?php echo esc_html($member_data['company']); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($member_data['excerpt']) : ?>
                                    <p class="bcn-member-card__excerpt"><?php echo esc_html(wp_trim_words($member_data['excerpt'], 20)); ?></p>
                                <?php endif; ?>
                                
                                <!-- Membership Level Badge -->
                                <?php if (!empty($member_data['membership_level'])) : ?>
                                    <div class="bcn-member-card__membership-badge bcn-member-card__membership-badge--<?php echo esc_attr($member_data['membership_level'][0]); ?>">
                                        <?php echo esc_html(ucfirst(str_replace('-', ' ', $member_data['membership_level'][0]))); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Contact Info -->
                                <div class="bcn-member-card__contact">
                                    <?php if ($member_data['website']) : ?>
                                        <a href="<?php echo esc_url($member_data['website']); ?>" class="bcn-member-card__contact-link" target="_blank" rel="noopener">
                                            <span class="bcn-member-card__contact-icon">🌐</span>
                                            Website
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($member_data['phone']) : ?>
                                        <a href="tel:<?php echo esc_attr($member_data['phone']); ?>" class="bcn-member-card__contact-link">
                                            <span class="bcn-member-card__contact-icon">📞</span>
                                            Call
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($member_data['email']) : ?>
                                        <a href="mailto:<?php echo esc_attr($member_data['email']); ?>" class="bcn-member-card__contact-link">
                                            <span class="bcn-member-card__contact-icon">✉️</span>
                                            Email
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                        
                    <?php endwhile; ?>
                <?php else : ?>
                    <div class="bcn-member-archive__no-results">
                        <h3>No members found</h3>
                        <p>Try adjusting your filters or search terms.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('← Previous', 'bcn-wp-theme'),
                'next_text' => __('Next →', 'bcn-wp-theme'),
                'class' => 'bcn-member-archive__pagination'
            ));
            ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>
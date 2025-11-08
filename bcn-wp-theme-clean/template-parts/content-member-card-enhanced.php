<?php
/**
 * Enhanced template part for displaying a member card with modern UX features.
 *
 * @package BCN_WP_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$member_data = bcn_get_member_card_data(get_the_ID());
$engagement_score = $member_data['engagement_score'];
$is_featured = $member_data['featured'];
$social_links = $member_data['social_links'];
$testimonials = $member_data['testimonials'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('member-card-enhanced' . ($is_featured ? ' member-card-enhanced--featured' : '')); ?> 
         data-engagement="<?php echo esc_attr($engagement_score); ?>"
         data-member-id="<?php echo esc_attr(get_the_ID()); ?>">
    
    <!-- Card Header with Logo and Status -->
    <div class="member-card-enhanced__header">
        <div class="member-card-enhanced__logo-container">
            <a class="member-card-enhanced__logo-link" href="<?php echo esc_url($member_data['permalink']); ?>" 
               aria-label="<?php echo esc_attr($member_data['title']); ?>">
                <?php if ($member_data['thumbnail']) : ?>
                    <img src="<?php echo esc_url($member_data['thumbnail']); ?>" 
                         alt="<?php echo esc_attr($member_data['logo_alt'] ?: $member_data['title']); ?>"
                         class="member-card-enhanced__logo" 
                         loading="lazy">
                <?php else : ?>
                    <div class="member-card-enhanced__logo-placeholder">
                        <span class="member-card-enhanced__logo-text"><?php echo esc_html(substr($member_data['title'], 0, 2)); ?></span>
                    </div>
                <?php endif; ?>
            </a>
            
            <!-- Engagement Score Indicator -->
            <div class="member-card-enhanced__engagement" 
                 title="<?php printf(esc_attr__('Profile completeness: %d%%', 'bcn-wp-theme'), $engagement_score); ?>">
                <div class="member-card-enhanced__engagement-ring">
                    <svg class="member-card-enhanced__engagement-svg" viewBox="0 0 36 36">
                        <path class="member-card-enhanced__engagement-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                        <path class="member-card-enhanced__engagement-fill" 
                              stroke-dasharray="<?php echo esc_attr($engagement_score); ?>, 100" 
                              d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                    </svg>
                    <span class="member-card-enhanced__engagement-text"><?php echo esc_html($engagement_score); ?>%</span>
                </div>
            </div>
        </div>
        
        <!-- Featured Badge -->
        <?php if ($is_featured) : ?>
            <div class="member-card-enhanced__badge member-card-enhanced__badge--featured">
                <span class="member-card-enhanced__badge-icon" aria-hidden="true">★</span>
                <span class="member-card-enhanced__badge-text"><?php esc_html_e('Featured', 'bcn-wp-theme'); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Card Content -->
    <div class="member-card-enhanced__content">
        <h2 class="member-card-enhanced__title">
            <a href="<?php echo esc_url($member_data['permalink']); ?>" class="member-card-enhanced__title-link">
                <?php echo esc_html($member_data['title']); ?>
            </a>
        </h2>
        
        <!-- Membership Levels -->
        <?php if (!empty($member_data['levels']) && !is_wp_error($member_data['levels'])) : ?>
            <div class="member-card-enhanced__levels">
                <?php foreach ($member_data['levels'] as $level) : ?>
                    <span class="member-card-enhanced__level member-card-enhanced__level--<?php echo esc_attr($level->slug); ?>">
                        <?php echo esc_html($level->name); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Excerpt -->
        <div class="member-card-enhanced__excerpt">
            <?php echo wp_kses_post($member_data['excerpt']); ?>
        </div>

        <!-- Contact Information -->
        <div class="member-card-enhanced__contact">
            <?php if (!empty($member_data['website'])) : ?>
                <a href="<?php echo esc_url($member_data['website']); ?>" 
                   target="_blank" 
                   rel="noopener" 
                   class="member-card-enhanced__contact-link member-card-enhanced__contact-link--website"
                   title="<?php esc_attr_e('Visit website', 'bcn-wp-theme'); ?>">
                    <span class="member-card-enhanced__contact-icon" aria-hidden="true">🌐</span>
                    <span class="member-card-enhanced__contact-text"><?php esc_html_e('Website', 'bcn-wp-theme'); ?></span>
                </a>
            <?php endif; ?>
            
            <?php if (!empty($member_data['email'])) : ?>
                <a href="mailto:<?php echo esc_attr($member_data['email']); ?>" 
                   class="member-card-enhanced__contact-link member-card-enhanced__contact-link--email"
                   title="<?php esc_attr_e('Send email', 'bcn-wp-theme'); ?>">
                    <span class="member-card-enhanced__contact-icon" aria-hidden="true">✉️</span>
                    <span class="member-card-enhanced__contact-text"><?php esc_html_e('Email', 'bcn-wp-theme'); ?></span>
                </a>
            <?php endif; ?>
        </div>

        <!-- Social Media Links -->
        <?php if (!empty(array_filter($social_links))) : ?>
            <div class="member-card-enhanced__social">
                <?php foreach ($social_links as $platform => $url) : ?>
                    <?php if (!empty($url)) : ?>
                        <a href="<?php echo esc_url($url); ?>" 
                           target="_blank" 
                           rel="noopener" 
                           class="member-card-enhanced__social-link member-card-enhanced__social-link--<?php echo esc_attr($platform); ?>"
                           title="<?php printf(esc_attr__('Follow on %s', 'bcn-wp-theme'), ucfirst($platform)); ?>"
                           aria-label="<?php printf(esc_attr__('Follow on %s', 'bcn-wp-theme'), ucfirst($platform)); ?>">
                            <span class="member-card-enhanced__social-icon" aria-hidden="true">
                                <?php echo bcn_get_social_icon($platform); ?>
                            </span>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Testimonials Preview -->
        <?php if (!empty($testimonials)) : ?>
            <div class="member-card-enhanced__testimonials">
                <div class="member-card-enhanced__testimonial-preview">
                    <blockquote class="member-card-enhanced__testimonial-quote">
                        "<?php echo esc_html(wp_trim_words($testimonials[0]['content'] ?? '', 15)); ?>"
                    </blockquote>
                    <cite class="member-card-enhanced__testimonial-author">
                        — <?php echo esc_html($testimonials[0]['author'] ?? ''); ?>
                    </cite>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Card Actions -->
    <div class="member-card-enhanced__actions">
        <a href="<?php echo esc_url($member_data['permalink']); ?>" 
           class="member-card-enhanced__action member-card-enhanced__action--primary">
            <?php esc_html_e('View Profile', 'bcn-wp-theme'); ?>
        </a>
        
        <button type="button" 
                class="member-card-enhanced__action member-card-enhanced__action--secondary member-card-enhanced__quick-contact"
                data-member-id="<?php echo esc_attr(get_the_ID()); ?>"
                data-member-name="<?php echo esc_attr($member_data['title']); ?>">
            <?php esc_html_e('Quick Contact', 'bcn-wp-theme'); ?>
        </button>
    </div>

    <!-- Quick Contact Modal (hidden by default) -->
    <div class="member-card-enhanced__quick-contact-modal" 
         id="quick-contact-<?php echo esc_attr(get_the_ID()); ?>" 
         style="display: none;">
        <div class="member-card-enhanced__quick-contact-content">
            <h3><?php printf(esc_html__('Contact %s', 'bcn-wp-theme'), $member_data['title']); ?></h3>
            <div class="member-card-enhanced__quick-contact-options">
                <?php if (!empty($member_data['email'])) : ?>
                    <a href="mailto:<?php echo esc_attr($member_data['email']); ?>" 
                       class="member-card-enhanced__quick-contact-option">
                        <span class="member-card-enhanced__quick-contact-icon">✉️</span>
                        <span><?php esc_html_e('Send Email', 'bcn-wp-theme'); ?></span>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($member_data['phone'])) : ?>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $member_data['phone'])); ?>" 
                       class="member-card-enhanced__quick-contact-option">
                        <span class="member-card-enhanced__quick-contact-icon">📞</span>
                        <span><?php esc_html_e('Call', 'bcn-wp-theme'); ?></span>
                    </a>
                <?php endif; ?>
                
                <?php if (!empty($member_data['website'])) : ?>
                    <a href="<?php echo esc_url($member_data['website']); ?>" 
                       target="_blank" 
                       rel="noopener" 
                       class="member-card-enhanced__quick-contact-option">
                        <span class="member-card-enhanced__quick-contact-icon">🌐</span>
                        <span><?php esc_html_e('Visit Website', 'bcn-wp-theme'); ?></span>
                    </a>
                <?php endif; ?>
            </div>
            <button type="button" class="member-card-enhanced__quick-contact-close" aria-label="<?php esc_attr_e('Close', 'bcn-wp-theme'); ?>">×</button>
        </div>
    </div>
</article>
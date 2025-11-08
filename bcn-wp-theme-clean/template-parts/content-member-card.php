<?php
/**
 * Template part for displaying a member card within the directory grid.
 *
 * @package BCN_WP_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

$member_fields = bcn_get_member_profile_fields(get_the_ID());
$levels        = $member_fields['levels'];
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('member-card'); ?>>
    <a class="member-card__logo" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
        <?php
        if (has_post_thumbnail()) {
            the_post_thumbnail('medium', array('class' => 'member-card__logo-image'));
        } else {
            echo '<span class="member-card__logo-placeholder">' . esc_html(get_the_title()) . '</span>';
        }
        ?>
    </a>
    <div class="member-card__content">
        <h2 class="member-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php if (!empty($levels) && !is_wp_error($levels)) : ?>
            <ul class="member-card__levels">
                <?php foreach ($levels as $level) : ?>
                    <li class="member-card__level member-card__level--<?php echo esc_attr($level->slug); ?>"><?php echo esc_html($level->name); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="member-card__excerpt">
            <?php the_excerpt(); ?>
        </div>

        <div class="member-card__meta">
            <?php if (!empty($member_fields['website'])) : ?>
                <a class="member-card__website" href="<?php echo esc_url($member_fields['website']); ?>" target="_blank" rel="noopener">
                    <?php esc_html_e('Visit website', 'bcn-wp-theme'); ?>
                </a>
            <?php endif; ?>

            <?php if (!empty($member_fields['email'])) : ?>
                <a class="member-card__email" href="mailto:<?php echo esc_attr($member_fields['email']); ?>">
                    <?php esc_html_e('Email', 'bcn-wp-theme'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>

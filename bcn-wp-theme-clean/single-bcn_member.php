<?php
/**
 * Template for displaying individual member profiles.
 *
 * @package BCN_WP_Theme
 */

get_header();
?>

<main id="primary" class="site-main single-member">
    <?php
    while (have_posts()) :
        the_post();
        $member_fields = bcn_get_member_profile_fields(get_the_ID());
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-member__article'); ?>>
            <header class="single-member__header">
                <div class="single-member__logo">
                    <?php
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('large', array('class' => 'single-member__logo-image'));
                    } else {
                        echo '<span class="single-member__logo-placeholder">' . esc_html(get_the_title()) . '</span>';
                    }
                    ?>
                </div>
                <div class="single-member__intro">
                    <h1 class="single-member__title"><?php the_title(); ?></h1>
                    <?php if (!empty($member_fields['levels'])) : ?>
                        <ul class="single-member__levels">
                            <?php foreach ($member_fields['levels'] as $level) : ?>
                                <li class="single-member__level single-member__level--<?php echo esc_attr($level->slug); ?>"><?php echo esc_html($level->name); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="single-member__actions">
                        <?php if (!empty($member_fields['website'])) : ?>
                            <a class="button button-primary" href="<?php echo esc_url($member_fields['website']); ?>" target="_blank" rel="noopener">
                                <?php esc_html_e('Visit website', 'bcn-wp-theme'); ?>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($member_fields['email'])) : ?>
                            <a class="button" href="mailto:<?php echo esc_attr($member_fields['email']); ?>">
                                <?php esc_html_e('Contact', 'bcn-wp-theme'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <div class="single-member__content">
                <?php the_content(); ?>
            </div>

            <aside class="single-member__sidebar">
                <h2><?php esc_html_e('Member details', 'bcn-wp-theme'); ?></h2>
                <dl class="single-member__details">
                    <?php if (!empty($member_fields['phone'])) : ?>
                        <div class="single-member__detail">
                            <dt><?php esc_html_e('Phone', 'bcn-wp-theme'); ?></dt>
                            <dd><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $member_fields['phone'])); ?>"><?php echo esc_html($member_fields['phone']); ?></a></dd>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($member_fields['email'])) : ?>
                        <div class="single-member__detail">
                            <dt><?php esc_html_e('Email', 'bcn-wp-theme'); ?></dt>
                            <dd><a href="mailto:<?php echo esc_attr($member_fields['email']); ?>"><?php echo esc_html($member_fields['email']); ?></a></dd>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($member_fields['address'])) : ?>
                        <div class="single-member__detail">
                            <dt><?php esc_html_e('Address', 'bcn-wp-theme'); ?></dt>
                            <dd><?php echo nl2br(esc_html($member_fields['address'])); ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </aside>
        </article>

        <section class="single-member__cta">
            <h2><?php esc_html_e('Want to be listed here?', 'bcn-wp-theme'); ?></h2>
            <p><?php esc_html_e('Join the network to access community benefits, co-marketing opportunities, and directory visibility.', 'bcn-wp-theme'); ?></p>
            <a class="button button-primary" href="<?php echo esc_url(home_url('/join')); ?>"><?php esc_html_e('Become a member', 'bcn-wp-theme'); ?></a>
        </section>

        <?php
        if (comments_open() || get_comments_number()) {
            comments_template();
        }
    endwhile;
    ?>
</main>

<?php
get_sidebar();
get_footer();

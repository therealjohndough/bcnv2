<?php
/**
 * Template part for displaying posts
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-list-item'); ?>>
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;
        ?>

        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta">
                <?php
                bcn_posted_on();
                bcn_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail() && !is_singular()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('bcn-featured'); ?>
            </a>
        </div><!-- .post-thumbnail -->
    <?php endif; ?>

    <div class="entry-content">
        <?php
        if (is_singular()) {
            the_content();
        } else {
            the_excerpt();
        }

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'bcn-wp-theme'),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <?php if (is_singular()) : ?>
        <footer class="entry-footer">
            <?php bcn_entry_footer(); ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->

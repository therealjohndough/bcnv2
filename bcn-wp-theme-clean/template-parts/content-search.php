<?php
/**
 * Template part for displaying search results
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-list-item'); ?>>
    <header class="entry-header">
        <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta">
                <?php
                bcn_posted_on();
                bcn_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('bcn-thumbnail'); ?>
            </a>
        </div><!-- .post-thumbnail -->
    <?php endif; ?>

    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->

    <footer class="entry-footer">
        <a href="<?php the_permalink(); ?>" class="button">
            <?php esc_html_e('Read More', 'bcn-wp-theme'); ?>
        </a>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->

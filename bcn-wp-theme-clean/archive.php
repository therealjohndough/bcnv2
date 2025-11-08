<?php
/**
 * The template for displaying archive pages
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php if (have_posts()) : ?>

        <header class="page-header">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header><!-- .page-header -->

        <div class="post-list">
            <?php
            // Start the Loop
            while (have_posts()) :
                the_post();

                /*
                 * Include the Post-Type-specific template for the content.
                 */
                get_template_part('template-parts/content', get_post_type());

            endwhile;
            ?>
        </div>

        <?php
        the_posts_pagination(
            array(
                'prev_text' => __('Previous', 'bcn-wp-theme'),
                'next_text' => __('Next', 'bcn-wp-theme'),
            )
        );
        ?>

    <?php else : ?>

        <?php get_template_part('template-parts/content', 'none'); ?>

    <?php endif; ?>

</main><!-- #primary -->

<?php
get_sidebar();
get_footer();

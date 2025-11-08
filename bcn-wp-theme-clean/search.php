<?php
/**
 * The template for displaying search results pages
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php if (have_posts()) : ?>

        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    /* translators: %s: search query. */
                    esc_html__('Search Results for: %s', 'bcn-wp-theme'),
                    '<span>' . get_search_query() . '</span>'
                );
                ?>
            </h1>
        </header><!-- .page-header -->

        <div class="post-list">
            <?php
            // Start the Loop
            while (have_posts()) :
                the_post();

                /**
                 * Run the loop for the search to output the results.
                 */
                get_template_part('template-parts/content', 'search');

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

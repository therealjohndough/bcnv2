<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'bcn-wp-theme'); ?></h1>
        </header><!-- .page-header -->

        <div class="page-content">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'bcn-wp-theme'); ?></p>

            <?php get_search_form(); ?>

            <div class="widget-area">
                <h2><?php esc_html_e('Try looking in the monthly archives.', 'bcn-wp-theme'); ?></h2>
                <?php
                wp_get_archives(
                    array(
                        'type'  => 'monthly',
                        'limit' => 12,
                    )
                );
                ?>
            </div>

            <?php
            // Categories
            $categories = get_categories(array('number' => 10));
            if ($categories) :
                ?>
                <div class="widget-area">
                    <h2><?php esc_html_e('Most Used Categories', 'bcn-wp-theme'); ?></h2>
                    <ul>
                        <?php
                        foreach ($categories as $category) {
                            echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>

        </div><!-- .page-content -->
    </section><!-- .error-404 -->

</main><!-- #primary -->

<?php
get_footer();

<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

?>

    <footer id="colophon" class="site-footer">
        <?php if (is_active_sidebar('footer-1')) : ?>
            <div class="footer-widgets">
                <?php dynamic_sidebar('footer-1'); ?>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <p>
                &copy; <?php echo date('Y'); ?> 
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php bloginfo('name'); ?>
                </a>
                <?php
                printf(
                    esc_html__('| Powered by %s', 'bcn-wp-theme'),
                    '<a href="https://wordpress.org/">WordPress</a>'
                );
                ?>
            </p>
            
            <?php
            if (has_nav_menu('footer')) {
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'footer-menu',
                        'depth'          => 1,
                        'container'      => 'nav',
                        'container_class'=> 'footer-navigation',
                    )
                );
            }
            ?>
        </div><!-- .site-info -->
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>

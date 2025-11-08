<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site-container">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'bcn-wp-theme'); ?></a>

    <header id="masthead" class="site-header">
        <div class="site-branding">
            <?php
            if (has_custom_logo()) {
                the_custom_logo();
            } else {
                ?>
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
                <?php
            }

            $description = get_bloginfo('description', 'display');
            if ($description || is_customize_preview()) :
                ?>
                <p class="site-description"><?php echo $description; ?></p>
            <?php endif; ?>

            <nav id="site-navigation" class="main-navigation">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </nav><!-- #site-navigation -->
        </div><!-- .site-branding -->
    </header><!-- #masthead -->

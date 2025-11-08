<?php
/**
 * Enhanced archive template for member directory with modern UX
 *
 * @package BCN_WP_Theme
 */

get_header();
?>

<main id="primary" class="site-main member-archive-enhanced">
    <header class="page-header member-archive-enhanced__header">
        <div class="member-archive-enhanced__hero">
            <h1 class="page-title"><?php esc_html_e('Member Directory', 'bcn-wp-theme'); ?></h1>
            <p class="page-subtitle"><?php esc_html_e('Discover the companies and partners who power our cannabis community.', 'bcn-wp-theme'); ?></p>
            
            <!-- Quick Stats -->
            <div class="member-archive-enhanced__stats">
                <?php
                $total_members = wp_count_posts('bcn_member')->publish;
                $featured_members = get_posts(array(
                    'post_type' => 'bcn_member',
                    'meta_query' => array(
                        array(
                            'key' => 'bcn_member_featured',
                            'value' => 1,
                        ),
                    ),
                    'posts_per_page' => -1,
                ));
                $featured_count = count($featured_members);
                ?>
                <div class="member-archive-enhanced__stat">
                    <span class="member-archive-enhanced__stat-number"><?php echo esc_html($total_members); ?></span>
                    <span class="member-archive-enhanced__stat-label"><?php esc_html_e('Total Members', 'bcn-wp-theme'); ?></span>
                </div>
                <div class="member-archive-enhanced__stat">
                    <span class="member-archive-enhanced__stat-number"><?php echo esc_html($featured_count); ?></span>
                    <span class="member-archive-enhanced__stat-label"><?php esc_html_e('Featured', 'bcn-wp-theme'); ?></span>
                </div>
                <div class="member-archive-enhanced__stat">
                    <span class="member-archive-enhanced__stat-number"><?php echo esc_html(wp_count_terms('bcn_membership_level')); ?></span>
                    <span class="member-archive-enhanced__stat-label"><?php esc_html_e('Membership Levels', 'bcn-wp-theme'); ?></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Enhanced Filter Section -->
    <section class="member-archive-enhanced__filters">
        <div class="member-archive-enhanced__filters-container">
            <form method="get" class="member-archive-enhanced__filter-form" id="member-filter-form">
                <div class="member-archive-enhanced__filter-group">
                    <div class="member-archive-enhanced__filter">
                        <label for="membership_level" class="member-archive-enhanced__filter-label">
                            <?php esc_html_e('Membership Level', 'bcn-wp-theme'); ?>
                        </label>
                        <select name="membership_level" id="membership_level" class="member-archive-enhanced__filter-select">
                            <option value=""><?php esc_html_e('All levels', 'bcn-wp-theme'); ?></option>
                            <?php
                            $current_level = isset($_GET['membership_level']) ? sanitize_key(wp_unslash($_GET['membership_level'])) : '';
                            $levels = get_terms(array(
                                'taxonomy' => 'bcn_membership_level',
                                'hide_empty' => false,
                            ));

                            foreach ($levels as $level) {
                                printf(
                                    '<option value="%1$s" %2$s>%3$s</option>',
                                    esc_attr($level->slug),
                                    selected($current_level, $level->slug, false),
                                    esc_html($level->name)
                                );
                            }
                            ?>
                        </select>
                    </div>

                    <div class="member-archive-enhanced__filter">
                        <label for="member-search" class="member-archive-enhanced__filter-label">
                            <?php esc_html_e('Search members', 'bcn-wp-theme'); ?>
                        </label>
                        <div class="member-archive-enhanced__search-container">
                            <input type="search" 
                                   id="member-search" 
                                   name="s" 
                                   value="<?php echo esc_attr(get_search_query()); ?>" 
                                   placeholder="<?php esc_attr_e('Search by name, business, or keyword...', 'bcn-wp-theme'); ?>"
                                   class="member-archive-enhanced__filter-search" />
                            <span class="member-archive-enhanced__search-icon" aria-hidden="true">🔍</span>
                        </div>
                    </div>

                    <div class="member-archive-enhanced__filter member-archive-enhanced__filter--checkbox">
                        <?php
                        $featured_checked = isset($_GET['featured_only']) ? (bool) $_GET['featured_only'] : false;
                        ?>
                        <label for="featured_only" class="member-archive-enhanced__checkbox-label">
                            <input type="checkbox" 
                                   name="featured_only" 
                                   id="featured_only" 
                                   value="1" 
                                   <?php checked($featured_checked); ?>
                                   class="member-archive-enhanced__checkbox" />
                            <span class="member-archive-enhanced__checkbox-text">
                                <?php esc_html_e('Show featured members only', 'bcn-wp-theme'); ?>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="member-archive-enhanced__filter-actions">
                    <button type="submit" class="member-archive-enhanced__filter-button member-archive-enhanced__filter-button--primary">
                        <span class="member-archive-enhanced__button-icon" aria-hidden="true">🔍</span>
                        <?php esc_html_e('Apply Filters', 'bcn-wp-theme'); ?>
                    </button>
                    <a class="member-archive-enhanced__filter-button member-archive-enhanced__filter-button--secondary" 
                       href="<?php echo esc_url(get_post_type_archive_link('bcn_member')); ?>">
                        <span class="member-archive-enhanced__button-icon" aria-hidden="true">↻</span>
                        <?php esc_html_e('Reset', 'bcn-wp-theme'); ?>
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- Results Section -->
    <section class="member-archive-enhanced__results">
        <div class="member-archive-enhanced__results-header">
            <div class="member-archive-enhanced__results-count">
                <span class="results-text">
                    <?php
                    global $wp_query;
                    $found_posts = $wp_query->found_posts;
                    printf(
                        esc_html(_n('Found %d member', 'Found %d members', $found_posts, 'bcn-wp-theme')),
                        $found_posts
                    );
                    ?>
                </span>
            </div>
            
            <div class="member-archive-enhanced__view-options">
                <button type="button" 
                        class="member-archive-enhanced__view-toggle member-archive-enhanced__view-toggle--grid active" 
                        data-view="grid"
                        aria-label="<?php esc_attr_e('Grid view', 'bcn-wp-theme'); ?>">
                    <span aria-hidden="true">⊞</span>
                </button>
                <button type="button" 
                        class="member-archive-enhanced__view-toggle member-archive-enhanced__view-toggle--list" 
                        data-view="list"
                        aria-label="<?php esc_attr_e('List view', 'bcn-wp-theme'); ?>">
                    <span aria-hidden="true">☰</span>
                </button>
            </div>
        </div>

        <?php if (have_posts()) : ?>
            <div class="member-archive-enhanced__grid" id="member-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'member-card-enhanced');
                endwhile;
                ?>
            </div>

            <!-- Pagination -->
            <div class="member-archive-enhanced__pagination">
                <?php
                the_posts_pagination(array(
                    'prev_text' => '<span class="pagination-icon" aria-hidden="true">←</span> ' . __('Previous', 'bcn-wp-theme'),
                    'next_text' => __('Next', 'bcn-wp-theme') . ' <span class="pagination-icon" aria-hidden="true">→</span>',
                    'class' => 'member-archive-enhanced__pagination-links',
                ));
                ?>
            </div>
        <?php else : ?>
            <section class="member-archive-enhanced__empty">
                <div class="member-archive-enhanced__empty-content">
                    <div class="member-archive-enhanced__empty-icon" aria-hidden="true">👥</div>
                    <h2><?php esc_html_e('No members found', 'bcn-wp-theme'); ?></h2>
                    <p><?php esc_html_e('Try adjusting your filters or search terms to find what you\'re looking for.', 'bcn-wp-theme'); ?></p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('bcn_member')); ?>" 
                       class="member-archive-enhanced__empty-button">
                        <?php esc_html_e('View All Members', 'bcn-wp-theme'); ?>
                    </a>
                </div>
            </section>
        <?php endif; ?>
    </section>

    <!-- Call to Action -->
    <section class="member-archive-enhanced__cta">
        <div class="member-archive-enhanced__cta-content">
            <h2><?php esc_html_e('Want to join our community?', 'bcn-wp-theme'); ?></h2>
            <p><?php esc_html_e('Connect with industry leaders, access exclusive resources, and grow your cannabis business.', 'bcn-wp-theme'); ?></p>
            <div class="member-archive-enhanced__cta-actions">
                <a href="<?php echo esc_url(home_url('/join')); ?>" 
                   class="member-archive-enhanced__cta-button member-archive-enhanced__cta-button--primary">
                    <?php esc_html_e('Become a Member', 'bcn-wp-theme'); ?>
                </a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" 
                   class="member-archive-enhanced__cta-button member-archive-enhanced__cta-button--secondary">
                    <?php esc_html_e('Learn More', 'bcn-wp-theme'); ?>
                </a>
            </div>
        </div>
    </section>
</main>

<?php
get_sidebar();
get_footer();